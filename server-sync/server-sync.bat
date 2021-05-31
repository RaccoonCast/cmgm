@echo off
for /f "tokens=1,2 delims==" %%G in (login-creds.hiddenpass) do set %%G=%%H
"C:\Program Files (x86)\WinSCP\WinSCP.com" /script=script.txt