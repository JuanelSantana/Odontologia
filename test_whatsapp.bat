@echo off
chcp 65001 > nul
echo ==================================================
echo Enviando mensaje de prueba vía Evolution API (CURL)...
echo ==================================================

curl -X POST "http://localhost:8080/message/sendText/clinica_cepin" ^
  -H "apikey: G5K34EFR55BB2YJH2AS72RB8" ^
  -H "Content-Type: application/json" ^
  -d "{\"number\": \"18092684228\", \"text\": \"Hola paciente. Esta es una prueba del nuevo sistema de Clinica Dr. Cepín.\"}"

echo.
echo ==================================================
echo Proceso finalizado.
echo ==================================================
pause
