@echo off
echo =================================
echo  SISTEM ATK PALJAYA - DEMO MODE
echo =================================
echo.

echo [1/4] Starting Laravel Server...
start "Laravel Server" cmd /c "php artisan serve --host=0.0.0.0 --port=8000"

echo [2/4] Waiting for server to start...
timeout /t 3

echo [3/4] Starting ngrok tunnel...
start "ngrok" cmd /c "ngrok http 8000"

echo [4/4] Opening browser...
timeout /t 5
start http://localhost:4040

echo.
echo ================================
echo Demo is starting!
echo ================================
echo - Laravel Server: http://localhost:8000
echo - ngrok Dashboard: http://localhost:4040
echo - Check ngrok dashboard for public URL
echo.
echo Press any key to continue...
pause >nul