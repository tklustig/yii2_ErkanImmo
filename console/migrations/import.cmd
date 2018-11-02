@echo off
SET root=e:
SET pfad="E:\xampp\mysql\bin"
SET username="root"
SET host="localhost"
SET database="yii2_KanatImmo"
SET pfad2Script="C:\Users\tklustig\Desktop\perswitch_Testdaten\"
SET ImportFile="yii2_kanatimmo.sql"
%root%
cd %pfad%
mysql -u %username% -h %host% -p %database%<%pfad2Script%\%ImportFile%
echo\
echo Der Dump %ImportFile% wurde importiert.
echo Bitte ueberpruefen Sie das mittels folgender Queries
echo use ba;
echo SHOW TABLES;
echo\
mysql -u %username% -h %host%
PAUSE
REM mysql -u %username% -h %host%

REM MySql-Befehl zum Import einer CSV-Datei

REM LOAD DATA LOCAL INFILE 'C:\\Dumps\\ba_berufe.csv' 
REM INTO TABLE ba_berufe
REM FIELDS TERMINATED BY ';' 

REM MySql-Befehl zum Import eines SQL-Dumps\\ba_berufe

REM mysql -u %username% -h %host% -p %database%<%pfad2Script%\%ImportFile%
