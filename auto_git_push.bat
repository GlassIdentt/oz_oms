@echo off
chcp 65001 > nul
cd /d "d:\ProJectHome\ozitsolution\oz_oms"

echo [%date% %time%] Auto Git Push Started >> auto_push.log

git add .
git diff --cached --quiet
if %errorlevel% equ 0 (
    echo [%date% %time%] No changes to commit >> auto_push.log
) else (
    git commit -m "Auto-commit: %date% %time%"
    git push origin main
    echo [%date% %time%] Changes pushed to GitHub >> auto_push.log
)

echo [%date% %time%] Auto Git Push Completed >> auto_push.log
echo. >> auto_push.log
