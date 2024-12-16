<?php

set_include_path("./src");

require_once("PathInfoRouter.php");
include_once("../../../private/mysql_config.php");
include_once("model/AnimalStorageMySQL.php");
session_start();

try {
    $pdo = new PDO("mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DB.";charset=utf8", MYSQL_USER, MYSQL_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    throw new PDOException("Connection a eu un problème: " . $e->getMessage());
}

$AnimalStorageSession = new AnimalStorageMySQL($pdo);

$router = new PathInfoRouter($AnimalStorageSession);

$router->main();

?>