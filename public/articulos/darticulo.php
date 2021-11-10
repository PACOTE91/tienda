<?php

if (!isset($_GET['id'])) {
    header('location:index.php');
    die();
}

require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Src\Categorias;
use Src\Articulos;

if ((new Articulos)->existeid($_GET['id'])) {
    $articulo = (new Articulos)->leeelemento($_GET['id']);
    $categoria = (new Categorias)->nombre($articulo->categoria_id)->fetch(PDO::FETCH_OBJ);
} else {
    header("Location:index.php");
}



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detalles Artículo</title>
</head>
<!-- CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" rel="stylesheet">



<style type="text/css">
body {
    margin: 20px;
    background: linear-gradient(90deg, rgba(180, 0, 36, 0.6) 0%, rgba(200, 9, 121, 0.6) 35%, rgba(150, 212, 255, 0.6) 100%);
}

h1 {
    font-family: 'Bebas Neue', cursive;
    font-size: 260%;
}

.card {
    margin-left: auto;
    margin-right: auto;
}

.card-text {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 120%;
}
</style>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Barra Navegación</a>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" href="index.php">Volver a Artículos</a>
                    <a class="nav-link" href="carticulo.php">Crear artículo</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="card w-50"
        style="border-radius:20px;color:white;padding:10px;background: linear-gradient(90deg, rgba(2,0,36,0.5) 0%, rgba(9,9,121,0.5) 35%, rgba(0,212,255,0.5) 100%);">
        <h1>Detalle Artículo <?php echo $articulo->id ?></h1>
        <img class="card-img-top"
            src="https://www.noegasystems.com/wp-content/uploads/Sistemas-de-almacenamiento-con-estanterias-para-palets.jpg"
            height=300 alt="Card image cap">
        <div class="card-body">

            <div style="width:40%;display:block;float:left">
                <p class="card-text"><b>NOMBRE </b><i class="fas fa-angle-double-right"></i>
                    <?php echo $articulo->nombre ?></p>
                <p class="card-text"><b>PRECIO </b><i class="fas fa-angle-double-right"></i>
                    <?php echo $articulo->precio ?>€</p>
                <p class="card-text"><b>CATEGORÍA </b><i class="fas fa-angle-double-right"></i>
                    <?php echo $categoria->nombre ?></p>
            </div>


            <div style="width:40%;display:block;float:right">
                <h1>FILTRAR IGUALES POR:</h1>
                <form name="filtrar" method="POST" action="farticulo.php">
                    <select name="tipo" class="form-select" aria-label="Default select example">
                        <option value="nombre">Nombre Artículo</option>
                        <option value="categoria_id">ID Categoría</option>
                        <option value="precio">Precio</option>
                    </select>

                    <input type="text" name="id" hidden value="<?php echo $articulo->id ?>" />

                    <button name="btnFiltrar" type="submit">Filtrar</button>

            </div>

        </div>
    </div>



</body>

</html>