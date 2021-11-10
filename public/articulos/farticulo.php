<?php

session_start();
require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Src\Articulos;

if(!isset($_POST['tipo']) || !isset($_POST['id'])){
    header("Location:darticulo.php");
}

$columna=$_POST['tipo'];
$id=$_POST['id'];

// var_dump($columna)."<br>";
// var_dump($id)."<br>";

//Almacenamos el valor para el que queremos buscar valores iguales
$valor=(new Articulos)->valorafiltrar($columna,$id)->fetch(PDO::FETCH_OBJ);
$valor=$valor->$columna;

var_dump($valor)."<br>";


//Vector con todos los regitros que coinciden con el valor que queremos filtrar
$valorescoincidentes=(new Articulos)->filtrar($columna,$valor);

?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Index Articulos</title>
</head>
<!-- CSS -->
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../bootstrap.css" type="text/css">
<link rel="stylesheet" href="../index.css" type="text/css">
<link rel="stylesheet" href="../../../../FontAwesome/css/all.css" type="text/css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.11.3/af-2.3.7/r-2.2.9/datatables.min.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.11.3/af-2.3.7/r-2.2.9/datatables.min.js"></script>

<style>
  #cont {
    width: 70%;
    margin-left: auto;
    margin-right: auto;
    <?php
    if (isset($_SESSION['mensaje'])) {
      echo "margin-top: 15%;";
    } else {
      echo "margin-top: 8%;";
    }
    ?>
    font-size: 100%
  }
</style>


<body>

  <div id="navbar">
    <h1 style="display:block">FILTRO</h1>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Barra Navegación</a>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-link" href="index.php">Inicio</a>            
            <a class="nav-link" href="darticulo.php?id=<?php echo $id ?>">Volver a detalles</a>
            <a class="nav-link" href="carticulo.php">Crear artículo</a>
          </div>
        </div>
      </div>
    </nav>
    <?php
    if (isset($_SESSION['mensaje'])) {
      echo <<<TEXTO
                                
                                <div class="alert alert-success" role="alert">
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
         border-radius: 15px;">FILTRANDO VALORES POR <?php echo '"'.strtoupper($columna).'"' ?></h4>
    <table id="categorias" class="table table-dark">


      <!-- Mostramos mensaje proveniente de $_SESSION-->

      <thead>
        <tr>
          <th scope="col">ID</th>
          <th style="text-align:center" scope="col">Nombre artículo</th>
          <th scope="col">Precio</th>
          <th scope="col">Categoría</th>
          <th scope="col">Acciones</th>

        </tr>
      </thead>
      <tbody>

        <?php
        while ($fila = $valorescoincidentes->fetch(PDO::FETCH_OBJ)) {
          echo <<<TEXTO
    <tr>
      <th style="width:auto scope="row">
      <a href="darticulo.php?id={$fila->id}">$fila->id</a>
      </th>
      <td style="text-align:center;width:auto">{$fila->nombre}</td>
      <td style="width:auto">{$fila->precio} €</td>
      <td style="width:10%">{$fila->categoria_id}</td>
      <td style="width:11%">
      <form style="display:inline" action='barticulo.php' method='POST'>
            <input type='hidden' name='id' value='{$fila->id}'>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Borrar Artículo {$fila->id} ')"><i class="far fa-trash"></i></button>

      </form>     
      <form style="display:inline" action='uarticulo.php' method='POST'>
            <input type='hidden' name='id' value='{$fila->id}'>
            <button type="submit" class="btn btn-warning"><i class="fal fa-edit"></i></button>
      </form>    
      
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