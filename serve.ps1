param(
    [string]$BindAddress = '127.0.0.1',
    [int]$Port = 8080
)

$phpCommand = Get-Command php -ErrorAction SilentlyContinue
$phpPath = if ($phpCommand) { $phpCommand.Source } else { $null }

if (-not $phpPath) {
    $candidates = @(
        'C:\\xampp\\php\\php.exe',
        'C:\\php\\php.exe',
        'C:\\Program Files\\PHP\\php.exe',
        'C:\\Program Files (x86)\\PHP\\php.exe',
        'C:\\tools\\php\\php.exe',
        'C:\\laragon\\bin\\php\\php-8.2.0-Win32-vs16-x64\\php.exe'
    )

    foreach ($candidate in $candidates) {
        if (Test-Path $candidate) {
            $phpPath = $candidate
            break
        }
    }
}

if (-not $phpPath) {
    throw 'PHP nao encontrado. Instale o PHP 8.2+ ou adicione o executavel ao PATH.'
}

$projectRoot = $PSScriptRoot
$publicDir = Join-Path $projectRoot 'public'

if (-not (Test-Path $publicDir)) {
    throw "Diretorio public nao encontrado: $publicDir"
}

$storageSessions = Join-Path $projectRoot 'storage\\sessions'
if (-not (Test-Path $storageSessions)) {
    New-Item -ItemType Directory -Path $storageSessions -Force | Out-Null
}

Write-Host "Iniciando Elo 42 em http://$BindAddress`:$Port"
Write-Host "Usando PHP: $phpPath"

& $phpPath -S "$BindAddress`:$Port" -t $publicDir
