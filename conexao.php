<?php
$host = "pgsql_desafio";
$db = "applicationphp";
$user = "root";
$pw = "root";


try {
    $myPDO = new PDO("pgsql:host=$host;dbname=$db", $user, $pw);
} catch (PDOException $e) {
    echo "Falha ao conectar com o banco de dados <br/>";
    die($e->getMessage());
}