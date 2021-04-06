@echo off
echo.WARNING: This will remove files from the server if they have been deleted on local.
echo.
timeout 1 >nul
echo.Continue?
choice
if %errorlevel% == 2 exit
cls
echo.Are you sure? 
timeout 2 >nul
choice 
if %errorlevel% == 2 exit

echo.Okay... You asked for it.
echo.
for /f "tokens=1,2 delims==" %%G in (login-creds.hiddenpass) do set %%G=%%H
WinSCP.com /script=script-delete.txt