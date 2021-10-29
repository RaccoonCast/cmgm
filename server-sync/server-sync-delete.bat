@echo off
echo.WARNING: This will remove files from the server if they have been deleted on local.
echo.
timeout 1 >nul
echo.Continue?
choice
cls
if %errorlevel% == 2 echo ----------------------------------------------
if %errorlevel% == 2 echo Proceeding with delete delete mode turned off.
if %errorlevel% == 2 echo ----------------------------------------------
if %errorlevel% == 2 call server-sync.bat
if %errorlevel% == 2 exit

echo.Okay... You asked for it.
echo.
for /f "tokens=1,2 delims==" %%G in (login-creds.hiddenpass) do set %%G=%%H
if exist "C:\Program Files (x86)\WinSCP\WinSCP.com" call :prgexe
if exist "%localappdata%\Programs\WinSCP\WinSCP.com" call :lclexe
echo.WinSCP could not be found.
pause >nul & exit

:prgexe
"C:\Program Files (x86)\WinSCP\WinSCP.com" /script=script-delete.txt
exit

:lclexe
"%localappdata%\Programs\WinSCP\WinSCP.com" /script=script-delete.txt
exit