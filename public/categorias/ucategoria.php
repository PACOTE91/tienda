<?php

session_start();

require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Src\Categorias;



if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $_SESSION['id'] = $id;
    $nom = $_POST['nom'];
} else {
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
    } else {
        header("Location:index.php");
    }
}


$categoria = (new Categorias)->leerTodos($id)->fetch(PDO::FETCH_OBJ);



function hayError($n, $d)
{
    //$id como global para poder usarla en nuestra funcion
    global $id;

    //Inicializamos el error en false
    $error = false;

    // Si la longitud del parámetro $n es cero, error=true y creamos la variable de sesión
    if (strlen($n) == 0) {
        $error = true;
        $_SESSION['errornombre'] = "Campo nombre vacío";
    }

    // Si la longitud del parámetro $d es cero, error=true y creamos la variable de sesión
    if (strlen($d) == 0) {
        $error = true;
        $_SESSION['errordescripcion'] = "Campo descripcion vacío";
    }

    //Creamos una variable que llama a la funcion uniqueid que devuelve 1 si encuentra ya un registro de nombre
    //O devuelve 0 si no existe registro coincidente
    $nombres = (new Categorias)->setId($id)->uniquenombre($n);

    //Si ya existe un registro con ese nombre error=true
    if ($nombres == 1) {
        $_SESSION['errordescripcion'] = "Campo duplicado";
        $error = true;
    }

    return $error;
}

//Si se ha procesado el formulario...
if (isset($_POST['actualizar'])) {
    //Almacenamos los valores de los campos del formulario pasados por $_POST
    $nombre = trim(ucwords($_POST['nombre']));
    $descripcion = trim(ucwords($_POST['descripcion']));
    //Eliminamos espacios y convertimos a mayuscula la primera letra

    //Si la funcion anterior devuelve false creamos un objeto Categorias y actualizamos llamando a la funcion actualizar de la clase categorías
    if (!hayError($nombre, $descripcion)) {
        (new Categorias)->setNombre($nombre)->setDescription($descripcion)->setId($id)->actualizar();
        //Si se ha actualizado el elemento correctamente, eliminamos la variable de Sesión ID y creamos la de mensaje
        unset($_SESSION['id']);
        $_SESSION['mensaje'] = "<b>CATEGORÍA \"{$_SESSION['nom']} \" MODIFICADA </b>";
        header("Location:index.php");
    } else {
        $_SESSION['id'] = $id;
        header("Location:ucategoria.php");
    }
} else {



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar categoría</title>
</head>
<!-- CSS -->
<link rel="stylesheet" href="../bootstrap.css" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" rel="stylesheet">


<style>
label {
    padding-top: 10px;
    padding-bottom: 10px
}

body {
    background: rgb(34, 193, 195);
    background: linear-gradient(0deg, rgba(34, 193, 195, 1) 0%, rgba(45, 116, 253, 1) 100%);
}
</style>




<body style="margin:20px">


    <h3 style="text-align:center">ACTUALIZAR CATEGORÍA <i class="fas fa-arrow-right"></i>
        <?php echo $id . "," . strtoupper($nom) ?></h3>

    <div style="width:50%; margin-left:auto;margin-right:auto" class="card">

        <div class="card-body">
            <form name="actualiza" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">

                <div class="form-group">
                    <label for="iden"><b>ID</b></label>
                    <input class="form-control" disabled type="text" id="iden" name="id" value="<?php echo $id ?>">
                </div>

                <div class="form-group">
                    <label for="name"><b>NOMBRE CATEGORÍA</b></label>
                    <input class="form-control" type="text" id="name" name="nombre"
                        value="<?php echo $categoria->nombre ?>" placeholder="Nombre">
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
                    <?php
                        if (isset($_SESSION['duplicado'])) {
                            echo <<<TEXTO
                                <div class="alert alert-warning mt-2" role="alert">
                                {$_SESSION['duplicado']}
                                </div>
                                TEXTO;
                            unset($_SESSION['duplicado']);
                        }
                        ?>

                </div>

                <div class="form-group">
                    <label for="descrip"><b>DESCRIPCION CATEGORÍA</b></label>
                    <textarea id="descrip" class="form-control" name="descripcion" id="" cols="30"
                        rows="10"><?php echo $categoria->descripcion ?></textarea>
                    <?php
                        if (isset($_SESSION['errordescripcion'])) {
                            echo <<<TEXTO
                                <div class="alert alert-warning mt-2" role="alert">
                                {$_SESSION['errordescripcion']}
                                </div>
                                TEXTO;
                            unset($_SESSION['errordescripcion']);
                        }
                        ?>

                </div>
                <button class="btn btn-secondary m-2" type="button" onclick="borracampos()">Borrar</button>
                <button name="actualizar" class="btn btn-primary m-2" type="submit">Actualizar</button>
            </form>

        </div>
    </div>



    <script>
    function borracampos() {
        document.getElementById("descrip").value = "";
        document.getElementById("name").value = "";

    }
    </script>




</body>

</html>

<?php } ?>