<?php

use Src\Articulos;
require dirname(__DIR__, 2)."/vendor/autoload.php";
 
session_start();

if(!isset($_POST['id'])){
    header("Location:index.php");
}else{
    (new Articulos)->borra($_POST['id']);
    $_SESSION['mensaje']="ARTÍCULO ".$_POST['id']." BORRADO CON ÉXITO";
    header("Location:index.php");

}

?>