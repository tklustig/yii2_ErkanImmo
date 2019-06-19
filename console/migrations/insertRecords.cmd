@ECHO off
REM for instance D:
SET /p folder=Enter partition from mysql (ends with doublepoint):  
%folder%
REM for instance D:\xampp\mysql\bin 
SET /p path2Mysq=Enter path to mysql bin folder: 
CD %path2Mysq%
SET nameOfDump=yii2_kundenDaten.sql
REM with backslash after last folder 
SET /p path2dump=Enter path to dump(incl. dumpname): 
SET database=yii2_kanatimmo
mysql -u root -p %database% --default-character-set=utf8mb4 < %path2dump%
SET /p frage=Kam es zu einem Fehler(J/N)?
IF %frage% EQU J (
	ECHO Die Records wurden nicht integriert
) ELSE (
	ECHO Die Records wurden integriert
)
PAUSE
