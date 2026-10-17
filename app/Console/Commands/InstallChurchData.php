<?php

namespace App\Console\Commands;

use App\Models\Church;
use App\Models\User;
use App\Models\Usergroup;
use App\Models\Userprofile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InstallChurchData extends Command
{
    protected $signature = 'church:install-data {data} {--base64 : Data is base64 encoded}';

    protected $description = 'Create initial church and admin user from installation data';

    public function handle(): int
    {
        $payload = (string) $this->argument('data');
        if ($this->option('base64')) {
            $decoded = base64_decode($payload, true);
            if ($decoded === false) {
                $this->error('Invalid installation data.');
                return self::FAILURE;
            }
            $payload = $decoded;
        }

        $data = json_decode($payload, true);
        if (!is_array($data) || json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid installation data.');
            return self::FAILURE;
        }

        try {
            validator($data, [
                'church_name' => ['required', 'string', 'max:255'],
                'church_email' => ['nullable', 'email', 'max:255'],
                'church_phone' => ['nullable', 'string', 'max:50'],
                'admin_email' => ['required', 'email', 'max:255'],
                'admin_password' => ['required', 'string', 'min:8'],
            ])->validate();
        } catch (ValidationException $exception) {
            $this->error('Installation data is incomplete or invalid.');
            return self::FAILURE;
        }

        $marker = storage_path('installed');
        if (file_exists($marker) || Church::query()->exists()) {
            $this->error('Installation is already initialized. No data was changed.');
            return self::FAILURE;
        }

        $adminGroup = Usergroup::query()->where('name', 'Admin')->first();
        if (!$adminGroup) {
            $this->error('Required Admin user group is missing. Run the core seeders first.');
            return self::FAILURE;
        }

        $envPath = base_path('.env');
        if (!is_file($envPath) || !is_writable($envPath)) {
            $this->error('Environment file is unavailable or not writable.');
            return self::FAILURE;
        }
        $originalEnv = file_get_contents($envPath);
        $markerCreated = false;

        try {
            DB::transaction(function () use ($data, $adminGroup, $marker, &$markerCreated) {
                // Recheck under the transaction to reduce the chance of two installer runs racing.
                if (Church::query()->lockForUpdate()->exists()) {
                    throw new \RuntimeException('Installation is already initialized.');
                }

                $church = Church::create([
                    'name' => trim($data['church_name']),
                    'address' => '',
                    'pincode' => '',
                    'slug' => Str::slug($data['church_name']) ?: 'church-' . Str::random(8),
                    'status' => 1,
                ]);

                $name = Str::before($data['admin_email'], '@');
                $user = User::create([
                    'name' => $name,
                    'email' => $data['admin_email'],
                    'password' => Hash::make($data['admin_password']),
                    'mobile_no' => $data['church_phone'] ?? '',
                    'church_id' => $church->id,
                    'usergroup_id' => $adminGroup->id,
                ]);

                Userprofile::create([
                    'user_id' => $user->id,
                    'church_id' => $church->id,
                    'firstname' => $name,
                    'lastname' => 'Administrator',
                    'profession' => 'admin',
                    'membership_type' => 'member',
                ]);

                $this->updateEnvFile($church->id);
                if (!is_dir(dirname($marker)) && !mkdir(dirname($marker), 0755, true) && !is_dir(dirname($marker))) {
                    throw new \RuntimeException('Could not prepare installation marker directory.');
                }
                if (file_put_contents($marker, now()->toDateTimeString(), LOCK_EX) === false) {
                    throw new \RuntimeException('Could not write installation marker.');
                }
                $markerCreated = true;
            });
        } catch (\Throwable $exception) {
            if ($markerCreated && is_file($marker)) {
                @unlink($marker);
            }
            $temporaryPath = $envPath . '.tmp';
            if (file_put_contents($temporaryPath, $originalEnv, LOCK_EX) !== false) {
                @rename($temporaryPath, $envPath);
            }
            $this->error('Installation records were rolled back because setup could not be finalized.');
            return self::FAILURE;
        }

        $this->info('Church and administrator were created successfully.');
        return self::SUCCESS;
    }

    private function updateEnvFile(int $churchId): void
    {
        $envPath = base_path('.env');
        if (!is_file($envPath) || !is_writable($envPath)) {
            throw new \RuntimeException('Environment file is unavailable or not writable.');
        }

        $contents = file_get_contents($envPath);
        $line = 'PRIMARY_CHURCH_ID=' . $churchId;
        if (preg_match('/^PRIMARY_CHURCH_ID=.*$/m', $contents)) {
            $contents = preg_replace('/^PRIMARY_CHURCH_ID=.*$/m', $line, $contents, 1);
        } else {
            $contents = rtrim($contents) . PHP_EOL . $line . PHP_EOL;
        }

        $temporaryPath = $envPath . '.tmp';
        if (file_put_contents($temporaryPath, $contents, LOCK_EX) === false || !rename($temporaryPath, $envPath)) {
            @unlink($temporaryPath);
            throw new \RuntimeException('Could not update the environment file.');
        }
    }
}
