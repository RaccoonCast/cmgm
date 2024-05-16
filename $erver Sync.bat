@echo off
cd %~dp0
set looplimit=50
:loop
set /a loop+=1
if %loop% gtr %loopLimit% exit
cmd /k "@cd server-sync & @call server-sync.bat"
call :loop