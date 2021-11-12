<?php

session_start();

require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Src\Categorias;
use Src\Articulos;

// pattern = "^[0-9]{0,3}+([,|.][0-9]{0,2}+)?$"



if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $_SESSION['id'] = $id;
} else {
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
    } else {
        header("Location:index.php");
    }
}


$articulo = (new Articulos)->leeelemento($id);
$categorias = (new Categorias)->devuelveNombre();


function hayError($n, $d)
{
    global $id;
    $error = false;
    $l = strlen($d);

    if (strlen($n) == 0) {
        $error = true;
        $_SESSION['errornombre'] = "Nombre artículo vacío";
    }

    if (strlen($d) == 0) {
        $_SESSION['errorprecio'] = "Error precio vacío";
    }
    $haypunto = substr($d, ($l - 3), 1);
    //Con esto comprobamos que exise el punto antes de los ultimos dos decimales
    if (strcmp($haypunto, ".") != 0) {
        $_SESSION['errorprecio'] = "Error en el formato del numero";
        $error = true;
    }


    return $error;
}

if (isset($_POST['actualizar'])) {
    $nombre = trim(ucwords($_POST['nombre']));
    $precio = trim(ucwords($_POST['precio']));
    $categoria = $_POST['categoria'];

    if (!hayError($nombre, $precio)) {
        (new Articulos)->setNombre($nombre)->setPrecio($precio)->setCategoria_id($categoria)->actualizar($id);
        unset($_SESSION['id']);
        // $_SESSION['borrado'] = "<h4><b>Artículo modificado</b><h4>";
        header("Location:index.php");
    } else {
        // $_SESSION['id'] = $id;
        header("Location:uarticulo.php");
    }
} else {



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Actualizar Artículos</title>
</head>
<!-- CSS -->
<link rel="stylesheet" href="../bootstrap.css" type="text/css">

<style>
label {
    padding-top: 10px;
    padding-bottom: 10px
}

body {
    background: rgb(34, 193, 195);
    background: linear-gradient(0deg, rgba(34, 193, 195, 1) 0%, rgba(45, 116, 253, 1) 100%);
}

.card {
    width: 50%;
    margin-left: auto;
    margin-right: auto;
}
</style>


<body style="margin:20px">

    <h1>Actualizar Artículo <?php echo $id ?></h1>


    <form name="actualiza" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="card">
            <div class="card-body">

                <div class="form-group">
                    <label for="iden">ID</label>
                    <input class="form-control" disabled type="text" id="iden" name="id" value="<?php echo $id ?>">
                </div>

                <div class="form-group">
                    <label for="name">Nombre Articulo</label>
                    <input class="form-control" type="text" id="nombre" name="nombre" requiered
                        value="<?php echo $articulo->nombre ?>" placeholder="Nombre">
                    <?php
                        if (isset($_SESSION['errornombre'])) {
                            echo <<<TEXTO
                                <div class="alert alert-warning mt-2" role="alert">
                                {$_SESSION['errornombre']}
                                </div>
                                TEXTO;
                            unset($_SESSION['errornombre']);
                        }
                        ?>
                </div>

                <div class="form-group">
                    <label for="descrip">Precio Artículo</label>
                    <input class="form-control" type="text" id="precio" pattern="^[0-9]{0,3}([,\.][0-9]{0,2})?"
                        requiered name="precio" value="<?php echo $articulo->precio ?>"
                        placeholder="Precio formato 000.00">
                    <?php
                        if (isset($_SESSION['errorprecio'])) {
                            echo <<<TEXTO
                                <div class="alert alert-warning mt-2" role="alert">
                                {$_SESSION['errorprecio']}
                                </div>
                                TEXTO;
                            unset($_SESSION['errorprecio']);
                        }
                        ?>
                </div>

                <div class="form-group">
                    <label for="categoria">ID Categoría</label>
                    <select class="form-control" name="categoria">
                        <?php
                            while ($fila = $categorias->fetch(PDO::FETCH_OBJ)) {
                                echo "<option value=\"{$fila->id}\">{$fila->nombre}</option>";
                            }
                            ?>
                    </select>
                </div>


                <button class="btn btn-secondary m-2" type="button" onclick="borracampos()">Borrar</button>
                <button name="actualizar" class="btn btn-primary m-2" type="submit">Actualizar</button>
            </div>
        </div>


    </form>

    <script>
    function borracampos() {
        document.getElementById("nombre").value = "";
        document.getElementById("precio").value = "";
    }
    </script>




</body>

</html>

<?php } ?>