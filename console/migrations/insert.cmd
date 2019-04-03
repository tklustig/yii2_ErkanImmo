@ECHO off
REM for instance D:
SET /p folder=Enter partition from mysql:  
%folder%
REM for instance D:\xampp\mysql\bin 
SET /p path2Mysq=Enter path to mysql: 
CD %path2Mysq%
SET /p nameOfDump=Enter name of dump:
REM with backslash after last folder 
SET /p path2dump=Enter path to dump: 
Set /p database=Enter name of database:
mysql -u root -p %database% --default-character-set=utf8mb4 < %path2dump%%nameOfDump% 
pause
