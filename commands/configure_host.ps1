# Definir las rutas de los archivos
$vhostsPath = "C:\xampp\apache\conf\extra\httpd-vhosts.conf"
$hostsPath = "C:\Windows\System32\drivers\etc\hosts"

# Contenido a agregar a httpd-vhosts.conf
$vhostsContent = @"
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/nova/public"
    ServerName nova.local
</VirtualHost>

<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs"
    ServerName localhost
</VirtualHost>
"@

# Contenido a agregar al archivo hosts
$hostsContent = "127.0.0.1 nova.local `n127.0.0.1 localhost"

# Verificar y agregar el contenido al archivo httpd-vhosts.conf si no existe
if (-not (Get-Content $vhostsPath | Select-String -Pattern "ServerName nova.local")) {
    Add-Content -Path $vhostsPath -Value $vhostsContent
    Write-Host "Configuración agregada a httpd-vhosts.conf"
} else {
    Write-Host "La configuración ya existe en httpd-vhosts.conf"
}

# Verificar y agregar el contenido al archivo hosts si no existe
if (-not (Get-Content $hostsPath | Select-String -Pattern "nova.local")) {
    Add-Content -Path $hostsPath -Value "`n$hostsContent"
    Write-Host "Entrada agregada al archivo hosts"
} else {
    Write-Host "La entrada ya existe en el archivo hosts"
}

# Reiniciar Apache
$apachePath = "C:\xampp\apache\bin\httpd.exe"
Start-Process -NoNewWindow -Wait -FilePath $apachePath -ArgumentList "-k restart"
Write-Host "Apache reiniciado"

Read-Host -Prompt "Presiona Enter para salir..."
