<?php

session_start();

require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Src\Categorias;

function hayError($n, $d)
{
    $error = false;
    if (strlen($n) == 0) {
        $error = true;
        $_SESSION['errornombre'] = "Valor nombre vacío";
    }else{
        if ((new Categorias)->uniquenombre($n) == 1) {
        $error = true;
        $_SESSION['duplicado'] = "Este nombre de Categoría ya existe !!";
    }
    }

    if (strlen($d) == 0) {
        $error = true;
        $_SESSION['errordescripcion'] = "Valor descripción vacío";
    }
    

    return $error;
}

if (isset($_POST['crear'])) {
    $nombre = trim(ucwords($_POST['nombre']));
    $descripcion = trim(ucwords($_POST['descripcion']));

    if (!hayError($nombre, $descripcion)) {
        (new Categorias)->setNombre($nombre)->setDescription($descripcion)->crear();
        $_SESSION['mensaje']="<b>Categoría creada</b>";
        header("Location:index.php");
    } else {
        header("Location:ccategoria.php");
    }
} else {



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mi Tienda</title>
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

h1 {
    text-align: center;
}
</style>



<body style="margin:20px">

    <h1>CREAR CATEGORÍA</h1>


    <form name="crea_categoria" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="card">
            <?php if (isset($_SESSION['duplicado'])) {

                    echo <<<TEXTO
                    <div class="alert alert-warning mt-2" role="alert">

                    {$_SESSION['duplicado']}

                    </div>
                    TEXTO;
                    unset($_SESSION['duplicado']);
                }
                ?>
            <div class="card-body">



                <div class="form-group">
                    <label for="name">NOMBRE CATEGORÍA</label>
                    <input class="form-control" type="text" id="name" name="nombre" placeholder="Nombre">
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
                    <div class="card-body">

                    </div>

                    <div class="form-group">
                        <label for="descrip">DESCRIPCIÓN CATEGORÍA</label>
                        <input class="form-control" type="text" id="descrip" name="descripcion"
                            placeholder="Descripcion">
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



                    <button name="crear" class="btn btn-primary m-2" type="submit">Crear</button>
                    <button class="btn btn-secondary m-2" type="reset">Borrar</button>
                </div>
            </div>


    </form>




</body>

</html>

<?php } ?>