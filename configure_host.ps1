$vhostsPath = "C:\xampp\apache\conf\extra\httpd-vhosts.conf"
Add-Content -Path $vhostsPath -Value @"
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/nova/public"
    ServerName nova.local
</VirtualHost>

<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs"
    ServerName localhost
</VirtualHost>
"@

# Agregar entrada al archivo hosts
$hostsPath = "C:\Windows\System32\drivers\etc\hosts"
Add-Content -Path $hostsPath -Value "`n127.0.0.1 nova.local"

# Reiniciar Apache
Start-Process -NoNewWindow -Wait -FilePath "C:\xampp\apache\bin\httpd.exe" -ArgumentList "-k restart"