<?php

namespace Src;

use PDO;
use PDOException;

class Conexion
{
    //Todos los objetos que se instancien van a tener este atributo
    protected static $conexion;


    public function __construct()
    {
        //Con esto garantizamos que solo tenemos una conexion a la BD
        if (self::$conexion == null) {
            self::crearConexion();
        }
    }

    public static function crearConexion()
    {
        $fichero=dirname(__DIR__,1)."\.config";
        $opciones=parse_ini_file($fichero);
        $dbname=$opciones['dbname'];
        $host=$opciones['host'];
        $usuario=$opciones['user'];
        $pass=$opciones['pass'];
        //Descriptor del nombre de servicio
        $dns="mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        //Creamos la conexion

        try{
            self::$conexion=new PDO($dns,$usuario,$pass);
            //Solo en el desarrollo
            self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $ex){
            die("Error en la conexión: ".$ex->getMessage());

        }
    }
}



