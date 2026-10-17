param(
    [Parameter(Mandatory = $true)][string]$Path,
    [switch]$Online
)

$token = [Environment]::GetEnvironmentVariable('STREAM_WEBHOOK_TOKEN', 'Process')
if ([string]::IsNullOrWhiteSpace($token)) {
    throw 'STREAM_WEBHOOK_TOKEN is not configured for MediaMTX.'
}

$body = @{
    path = $Path
    online = if ($Online) { '1' } else { '0' }
}
$headers = @{ Authorization = "Bearer $token" }

for ($attempt = 1; $attempt -le 3; $attempt++) {
    try {
        Invoke-RestMethod -Method Post -Uri 'http://127.0.0.1:8000/api/streaming/status' -Headers $headers -Body $body -ContentType 'application/x-www-form-urlencoded' -TimeoutSec 5 | Out-Null
        exit 0
    } catch {
        if ($attempt -eq 3) { throw }
        Start-Sleep -Seconds 1
    }
}
