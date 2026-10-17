<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\ChurchBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ChurchBranchController extends Controller
{
    private function church(): Church
    {
        return Church::query()->firstOrFail();
    }

    public function index()
    {
        $church = $this->church();
        $branches = ChurchBranch::withCount('users')->forChurch($church->id)->orderByDesc('is_headquarters')->orderBy('name')->get();

        return view('admin.branches.index', compact('church', 'branches'));
    }

    public function create()
    {
        return view('admin.branches.form', ['branch' => new ChurchBranch(), 'editing' => false]);
    }

    public function store(Request $request)
    {
        $church = $this->church();
        $data = $this->validated($request, $church->id);
        $data['church_id'] = $church->id;
        $data['slug'] = $this->uniqueSlug($data['name'], $church->id);
        $data['is_headquarters'] = false;
        $data['is_active'] = $request->boolean('is_active', true);
        ChurchBranch::create($data);

        return redirect('/admin/branches')->with('success', 'Branch created. It is now available on guest registration.');
    }

    public function edit($id)
    {
        $branch = ChurchBranch::forChurch($this->church()->id)->findOrFail($id);

        return view('admin.branches.form', ['branch' => $branch, 'editing' => true]);
    }

    public function update(Request $request, $id)
    {
        $church = $this->church();
        $branch = ChurchBranch::forChurch($church->id)->findOrFail($id);
        $data = $this->validated($request, $church->id, $branch->id);
        $data['slug'] = $this->uniqueSlug($data['name'], $church->id, $branch->id);
        $data['is_active'] = $branch->is_headquarters ? true : $request->boolean('is_active');
        $branch->update($data);

        return redirect('/admin/branches')->with('success', 'Branch details updated.');
    }

    public function toggle($id)
    {
        $branch = ChurchBranch::forChurch($this->church()->id)->findOrFail($id);
        abort_if($branch->is_headquarters, 422, 'Headquarters must remain active.');
        $branch->update(['is_active' => !$branch->is_active]);

        return back()->with('success', $branch->is_active ? 'Branch is active and available for registration.' : 'Branch deactivated. Existing member accounts were kept.');
    }

    private function validated(Request $request, int $churchId, ?int $branchId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('church_branches', 'name')->where('church_id', $churchId)->ignore($branchId)],
            'description' => ['nullable', 'string', 'max:2000'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:150'],
            'timezone' => ['required', 'timezone'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }

    private function uniqueSlug(string $name, int $churchId, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'branch';
        $slug = $base;
        $counter = 2;
        while (ChurchBranch::query()->forChurch($churchId)->where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
