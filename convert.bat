@echo off
set /p input=<dustbin\data.txt
if exist dustbin\lat.txt del dustbin\lat.txt
if exist dustbin\long.txt del dustbin\long.txt
set input=%input:	=,%
if not exist dustbin\lat.txt if "%input:~0,22%" == "https://cellmapper.net" call :cmgmapserror
if not exist dustbin\lat.txt if "%input:~0,26%" == "https://www.cellmapper.net" call :cmgmaps
if not exist dustbin\lat.txt if "%input:~-4%" == "maps" call :gmapscm
if not exist dustbin\lat.txt if "%input:~0,16%" == "google.com/maps/" call :gmapscm
if not exist dustbin\lat.txt call :latlong
echo|set /p="%lat:~0,10%">dustbin\latitude.txt
echo|set /p="%long:~0,10%">dustbin\longitude.txt
if defined carrier echo|set /p="%carrier%">dustbin\carrier.txt
exit /b

::CellMapper to Google Maps
:cmGMAPS
::carrier detection (beta) only works in usa
set "sub=%input:~35,11%
set "country=%sub:~0,3%"
set "network=%sub:~-3%"
if %country%%network% == 310260 set "carrier=T-Mobile"
if %country%%network% == 310410 set "carrier=AT&T"
if %country%%network% == 310120 set "carrier=Sprint"
if %country%%network% == 311480 set "carrier=Verizon"
set "LAT=%input:~63,18%"
call :latTrim
set "LONG=%input:~90,18%"
call :longTrim
exit /b

:cmgmapserror
@echo on
echo.Add www. to domains
exit /b

:latTrim
SET "var="&for /f "delims=0123456789-" %%i in ("%lat:~0,1%") do set var=%%i
if defined var (set lat=%lat:~1,21%) else (exit /b)
goto latTrim

:longTrim
SET "var="&for /f "delims=0123456789-" %%i in ("%long:~0,1%") do set var=%%i
if defined var (set long=%long:~1,21%) else (exit /b)
goto longTrim

::Google Maps to CellMapper
:GMAPScm
set input=%input:~9%
:loopie1
if "%input:~0,2%" == "/@" goto loopie1done
set input=%input:~1%
goto loopie1

:loopie1done
set latlong=%input:~2%
for %%a in ("%latlong:,=" "%") do (
if not defined long if defined lat set long=%%~a & goto :dostuff
if not defined lat set lat=%%~a
)
:dostuff
set lat="%lat%"
set long="%long:~0,-1%"
exit /b

::LatLong
:latlong
for %%a in ("%input:,=" "%") do (
if not defined lat set lat=%%~a
if defined lat set long=%%~a
)
if not defined lat goto :latLong2
exit /b

:latlong2
for %%a in ("%input: =" "%") do (
if not defined lat set lat=%%~a
if defined lat set long=%%~a
)
exit /b
