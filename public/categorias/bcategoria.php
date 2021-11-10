<?php

if(!isset($_POST['id']) || !isset($_POST['nombre'])){    
    header("Location:index.php");
}
session_start();

use Src\Categorias;

require dirname(__DIR__, 2) . "/vendor/autoload.php";

(new Categorias)->borra($_POST['id']);
$_SESSION['mensaje'] = "<b>CATEGORÍA \"({$_POST['id']}) {$_POST['nombre']}\" BORRADA</b>";
header("Location:index.php");