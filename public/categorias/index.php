<?php

session_start();
require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Src\Categorias;

(new Categorias)->categorias_generator(25);
$categorias = (new Categorias)->leerTodos();

?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Index Categorías</title>
</head>
<!-- CSS -->
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../bootstrap.css" type="text/css">
<link rel="stylesheet" href="../index.css" type="text/css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.11.3/af-2.3.7/r-2.2.9/datatables.min.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.11.3/af-2.3.7/r-2.2.9/datatables.min.js"></script>

<style> 
  #cont {
    width: 70%;
    margin-left: auto;
    margin-right: auto;
    <?php
    if(isset($_SESSION['mensaje'])){
      echo     "margin-top: 15%;";

    }else{
      echo     "margin-top: 8%;";
    }

    ?>
    font-size: 100%
  }
</style>


<body>
  <div id="navbar">
    <h1>CATEGORÍAS</h1>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Barra Navegación</a>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-link" href="../index.php">Inicio</a>
            <a class="nav-link" href="ccategoria.php">Crear Categoría</a>
            <a class="nav-link" href="../articulos/index.php">Artículos</a>
          </div>
        </div>
      </div>
    </nav>
    <?php
    if (isset($_SESSION['mensaje'])) {
      echo <<<TEXTO
                              <div class="alert alert-success mt-2" role="alert">
                              {$_SESSION['mensaje']}
                              </div>
                              TEXTO;
      unset($_SESSION['mensaje']);
    }
    ?>
  </div>



  <div id="cont">
    <h4 style="text-align:center;
         background-color:teal;
         padding:5px;
         border-radius: 15px;">TABLA CATEGORIAS</h4>

    <table id="categorias" class="table table-dark">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Nombre</th>
          <th scope="col">Descripcion</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($categoria = $categorias->fetch(PDO::FETCH_OBJ)) {
          echo <<<TEXTO
    <tr>
      <th style="width:10%" scope="row">{$categoria->id}</th>
      <td>{$categoria->nombre}</td>
      <td>{$categoria->descripcion}</td>
      <td style="width:10%">
      
          <div>      
          <form style="display:inline"  action='bcategoria.php' method='POST'>
                  <input type='hidden' name='id' value='{$categoria->id}'>
                  <input type='hidden' name='nombre' value='{$categoria->nombre}'>
                  <button type="submit" class="btn btn-danger" onclick="return confirm('Borrar Categoría {$categoria->id} ')"><i class="far fa-trash-alt"></i></button>

            </form>     
            <form style="display:inline" action='ucategoria.php' method='POST'>
                  <input type='hidden' name='id' value='{$categoria->id}'>
                  <input type='hidden' name='nom' value='{$categoria->nombre}'>
                  <button type="submit" class="btn btn-warning"><i class="far fa-edit"></i></button>

            </form>    
          </div>  
      
      </td>

    </tr>
  TEXTO;
        }
        ?>

      </tbody>
    </table>
  </div>
  <script>
    $(document).ready(function() {
      $('#categorias').DataTable();
    });
  </script>
</body>

</html>