@echo off
for /f "tokens=1,2 delims==" %%G in (login-creds.hiddenpass) do set %%G=%%H
WinSCP.com /script=script.txt