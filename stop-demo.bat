@echo off
echo =================================
echo  STOPPING DEMO SERVICES
echo =================================
echo.

echo [1/3] Stopping Laravel Server...
taskkill /f /im php.exe >nul 2>&1

echo [2/3] Stopping ngrok...
taskkill /f /im ngrok.exe >nul 2>&1

echo [3/3] Clearing cache...
php artisan cache:clear
php artisan view:clear

echo.
echo ================================
echo Demo services stopped!
echo ================================
echo.
pause