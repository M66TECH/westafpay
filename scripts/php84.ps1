param(
    [ValidateSet('test', 'analyse', 'format', 'all')]
    [string]$Task = 'all',

    [string]$Php = $env:PHP84_PATH
)

$ErrorActionPreference = 'Stop'

if ([string]::IsNullOrWhiteSpace($Php)) {
    $Php = 'C:\Users\MILKA-~1\AppData\Local\Temp\opencode\php84\php.exe'
}

$localPhp = Join-Path $PSScriptRoot '..\\.php84\\php.exe'
if (Test-Path -LiteralPath $localPhp) {
    $Php = (Resolve-Path -LiteralPath $localPhp).Path
}

if (-not (Test-Path -LiteralPath $Php)) {
    Write-Host "PHP 8.4 not found at '$Php'." -ForegroundColor Red
    Write-Host 'Download the NTS build from https://windows.php.net/download/, extract it and set PHP84_PATH.' -ForegroundColor Yellow
    exit 1
}

$version = & $Php -r 'echo PHP_VERSION;'

if (-not $version.StartsWith('8.4')) {
    Write-Host "Expected PHP 8.4, got $version ($Php)." -ForegroundColor Red
    exit 1
}

$root = (Resolve-Path -LiteralPath (Join-Path $PSScriptRoot '..')).Path

function Invoke-Step {
    param([string]$Name, [string[]]$Arguments)

    Write-Host "==> $Name" -ForegroundColor Cyan

    & $Php @Arguments

    if ($LASTEXITCODE -ne 0) {
        Write-Host "$Name failed (exit code $LASTEXITCODE)." -ForegroundColor Red
        exit $LASTEXITCODE
    }
}

Push-Location $root

try {
    if (-not (Test-Path -LiteralPath 'vendor\autoload.php')) {
        Write-Host 'Installing dependencies with Composer...' -ForegroundColor Yellow
        composer install --ignore-platform-reqs --no-interaction
    }

    switch ($Task) {
        'test' { Invoke-Step 'Pest' @('vendor/bin/pest') }
        'analyse' { Invoke-Step 'PHPStan' @('vendor/bin/phpstan', 'analyse', '--no-progress', '--memory-limit=1G') }
        'format' { Invoke-Step 'Pint' @('vendor/bin/pint') }
        'all' {
            Invoke-Step 'Pint' @('vendor/bin/pint', '--test')
            Invoke-Step 'PHPStan' @('vendor/bin/phpstan', 'analyse', '--no-progress', '--memory-limit=1G')
            Invoke-Step 'Pest' @('vendor/bin/pest')
        }
    }

    Write-Host 'Done.' -ForegroundColor Green
}
finally {
    Pop-Location
}
