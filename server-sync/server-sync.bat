@echo off
for /f "tokens=1,2 delims==" %%G in (login-creds.hiddenpass) do set %%G=%%H
if exist "C:\Program Files (x86)\WinSCP\WinSCP.com" call :prgexe
if exist "%localappdata%\Programs\WinSCP\WinSCP.com" call :lclexe
echo.WinSCP could not be found.
pause >nul & exit

:prgexe
"C:\Program Files (x86)\WinSCP\WinSCP.com" /script=script.txt
exit

:lclexe
"%localappdata%\Programs\WinSCP\WinSCP.com" /script=script.txt
exit