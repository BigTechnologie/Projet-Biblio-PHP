<?php 

// Définition du fuseau horaire
ini_set("date.timezone", "Europe/Paris");

// Déclaration des constantes répresentant les niveaux de log
const NOTICE = "NOTICE";
const ERROR = "ERROR";
const EMERGENCY = "EMERGENCY";

// Permet d'écrire le journal (des logs)
function writelog($level, $message, $params): string 
{
    // chemin vers le fichier log dans lequel on doit écrire
    $path = __DIR__.'/../bibliotheque/var/log/connexion.txt';

    $date = date_format(date_create(), 'c');
    // [date + heure] LEVEL: message {paramètres}
    $message = sprintf("[%s] [%s]: %s %s\n", $date, $level, $message, json_encode($params));

    file_put_contents($path, $message, FILE_APPEND);

    return $message;
}

function notice($message, $params = []): void 
{
    writelog(NOTICE, $message, $params);
}

function error($message, $params = []): void 
{
    writelog(ERROR, $message, $params);
}

function emergency($message, $params = []): void 
{
    writelog(EMERGENCY, $message, $params);

    if($_SERVER['SERVER_NAME'] !== 'localhost') {
        mail("dev.technologie2018@gmail", "Erreur Crite", $message);
    }
}



