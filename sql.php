<?php 

$host = 'localhost';
$username = 'root';
$password = '';
$database = null;
$port = 3306;

echo "<p>Connexion au SGBD</p>";

$c = mysqli_connect($host, $username, $password, $database, $port);

// Si erreur
if(mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
}

echo "<p>Connexion établie</p>";

echo "<p>Création de la BDD</p>";
mysqli_set_charset($c, 'utf8mb4');

$database = "biblio_2026w3";
$sql = "CREATE DATABASE IF NOT EXISTS ".$database;

// Si erreur
if(!mysqli_query($c, $sql)) {
    echo mysqli_error($c);
    mysqli_close($c);
    exit();
}

echo "<p>Connexion à la BDD</p>";
mysqli_select_db($c, $database);

echo "<p>Création de la table livre</p>";
$sql = <<<SQL
CREATE TABLE IF NOT EXISTS livre (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(50) NOT NULL,
    `resume` TEXT,
    date_parution DATE,
    image VARCHAR(255)
) ENGINE=InnoDB;
SQL;

// Si erreur
if(!mysqli_query($c, $sql)) {
    echo mysqli_error($c);
    mysqli_close($c);
    exit();
}

echo "<p>Création de la table utilisateur</p>";
$sql = <<<SQL
CREATE TABLE IF NOT EXISTS utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username varchar(50) not null,
    password varchar(150) not null,
    `name` varchar(50)
) ENGINE=InnoDB;
SQL;

// Si erreur
if(!mysqli_query($c, $sql)) {
    echo mysqli_error($c);
    mysqli_close($c);
    exit();
}

echo "<p>Insertion utilisateur</p>";
$password = password_hash('admin', PASSWORD_DEFAULT);
$sql = "INSERT INTO utilisateur (`username`, `password`, `name`) VALUE ('admin', '".$password."', 'John Doe')";

// Si erreur
if(!mysqli_query($c, $sql)) {
    echo mysqli_error($c);
    mysqli_close($c);
    exit();
}

mysqli_close($c);










