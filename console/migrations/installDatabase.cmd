:anfang
@ECHO OFF
SET /p laufwerk=Geben sie den Laufwerksbuchstaben an, unter dem die Applikation gehostet wird:
CD /d %laufwerk%:/xampp/htdocs/yii2_ErkanImmo
php yii migrate
IF ERRORLEVEL 1 GOTO fehler
SET /p frage=Haben Sie den Vorgang abgebrochen(J/N)?
IF %frage% EQU J (
	ECHO Die Datenbank wurde nicht erstellt
) ELSE (
	ECHO Die Datenbank wurde erstellt
)
GOTO ende
:fehler
ECHO Ein Fehler ist augetreten
GOTO anfang
:ende
PAUSE
