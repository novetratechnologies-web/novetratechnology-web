$ErrorActionPreference = 'Stop'

$version = '1.21.2'
$expectedSha256 = 'a84224f5a490855684e62ba6fb71e80ea2a5e16aed61883bb58380290e93e467'
$runtime = Join-Path $PSScriptRoot 'runtime'
$executable = Join-Path $runtime 'mediamtx.exe'
$archive = Join-Path $runtime "mediamtx_v${version}_windows_amd64.zip"
$projectEnv = Join-Path (Split-Path $PSScriptRoot -Parent) '.env'

if (-not (Test-Path -LiteralPath $projectEnv)) {
    throw 'Project .env file not found. Configure the Laravel environment first.'
}

$envLines = Get-Content -LiteralPath $projectEnv
$tokenLine = $envLines | Where-Object { $_ -match '^STREAM_WEBHOOK_TOKEN=' } | Select-Object -Last 1
$token = if ($tokenLine) { $tokenLine.Substring('STREAM_WEBHOOK_TOKEN='.Length).Trim('"', "'") } else { '' }
if ([string]::IsNullOrWhiteSpace($token) -or $token -like 'replace-with-*') {
    throw 'Set a long random STREAM_WEBHOOK_TOKEN in .env before starting MediaMTX.'
}
[Environment]::SetEnvironmentVariable('STREAM_WEBHOOK_TOKEN', $token, 'Process')

if (-not (Test-Path -LiteralPath $executable)) {
    New-Item -ItemType Directory -Path $runtime -Force | Out-Null
    $downloadUrl = "https://github.com/bluenviron/mediamtx/releases/download/v${version}/mediamtx_v${version}_windows_amd64.zip"
    Write-Host "Downloading MediaMTX v$version..."
    Invoke-WebRequest -Uri $downloadUrl -OutFile $archive
    $actualSha256 = (Get-FileHash -LiteralPath $archive -Algorithm SHA256).Hash.ToLowerInvariant()
    if ($actualSha256 -ne $expectedSha256) {
        Remove-Item -LiteralPath $archive -Force
        throw 'MediaMTX download checksum did not match the official release checksum.'
    }
    Expand-Archive -LiteralPath $archive -DestinationPath $runtime -Force
    Remove-Item -LiteralPath $archive -Force
}

Write-Host 'Starting MediaMTX. Press Ctrl+C to stop the RTMP/HLS server.'
$configTemplate = Get-Content -LiteralPath (Join-Path $PSScriptRoot 'mediamtx.windows.yml') -Raw
$scriptDirectory = $PSScriptRoot -replace '\\', '/'
$runtimeConfig = Join-Path $runtime 'mediamtx.windows.yml'
$configTemplate.Replace('__STREAMING_SCRIPT__', $scriptDirectory) | Set-Content -LiteralPath $runtimeConfig -Encoding utf8
& $executable $runtimeConfig
if ($LASTEXITCODE -ne 0) {
    throw "MediaMTX stopped with exit code $LASTEXITCODE."
}
