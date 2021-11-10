<?php

namespace Src;

use Faker;
use PDOException;
use PDO;
use Src\Categorias;

class Articulos extends Conexion
{

    private $id;
    private $nombre;
    private $precio;
    private $categoria_id;

    /*
        Llamamos al constructor de la función Padre "Cone"
    */

    public function __construct()
    {
        parent::__construct();
    }

    /*
        #################
              CRUD
        #################
    */



    //Borrar un registro por id
    public function borra($id)
    {
        $sql = "DELETE FROM articulos WHERE id=$id";
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute([]);
        } catch (PDOException $ex) {
            die("Error al borrar registro... " . $ex->getMessage);
        }
        parent::$conexion = null;
    }




    //Esta funcion inserta elementos en nuestra BD
    public function crear()
    {
        $sql = "INSERT INTO articulos (nombre, precio, categoria_id) VALUES (:n,:p,:c)";
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':p' => $this->precio,
                ':c' => $this->categoria_id
            ]);
        } catch (PDOException $ex) {
            die("Error al insertar registro... " . $ex->getMessage);
        }
        parent::$conexion = null;
    }


    //Lee todos los registros
    public function leer_todos()
    {
        $sql = "SELECT * FROM articulos";
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al devolver todos los registros" . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt;
    }

    //Actualiza un registro pasado id por parámetro
    public function actualizar($id)
    {
        $sql = "UPDATE articulos SET nombre=:n, precio=:p, categoria_id=:c WHERE id={$id}";
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':p' => $this->precio,
                ':c' => $this->categoria_id
            ]);
        } catch (PDOException $ex) {
            die("Error al actualizar registro..." . $ex->getMessage());
        }
        parent::$conexion = null;
    }


    /*
        ###########################
              OTRAS FUNCIONES
        ###########################
    */

    //Traer un registro
    public function proximoid()
    {
        $sql = "SELECT * FROM articulos";
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute();
            while($ids=$stmt->fetch(PDO::FETCH_OBJ)){
                $proxid=$ids->id;
            }
            $proxid=$proxid+1;
        } catch (PDOException $ex) {
            die("Error en la devolución del ID" . $ex->getMessage());
        }
        return $proxid;
    }


    //Usando la librería Faker creamos datos que insertaremos en la BD
    public function generame($num)
    {
        $categoria = (new Categorias)->devuelvecategorias();

        for ($i = 0; $i < $num; $i++) {
            $nombre = Faker\Factory::create('es_ES')->sentence(1);
            $precio = Faker\Factory::create('es_ES')->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 300);
            $id_categoria = $categoria[array_rand($categoria, 1)];
            (new Articulos)->setNombre($nombre)->setPrecio($precio)->setCategoria_id($id_categoria)->crear();
        }
    }

    //Esta funcion devuelve el numero de registros que existen en nuestra BD
    public function contarregistros()
    {
        $sql = "SELECT * FROM articulos";
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute();
            $num = $stmt->rowCount();
        } catch (PDOException $ex) {
            die("Error al devolver el numero de registros... " . $ex->getMessage);
        }
        return $num;
    }

    //Traer un registro
    public function leeelemento($id)
    {
        $sql = "SELECT * FROM articulos WHERE id=$id";
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en la lectura del ID: $id" . $ex->getMessage());
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function existeid($id)
    {
        $sql = "SELECT * FROM articulos WHERE id=$id";
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en la lectura del ID: $id" . $ex->getMessage());
        }
        return ($stmt->rowCount()==1);
    }


  
    public function valorafiltrar($columna,$id)
    {
        $sql = "SELECT {$columna} FROM articulos WHERE id=$id";
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute();            

        } catch (PDOException $ex) {
            die("Error al devolver el filtro..." . $ex->getMessage());
        }       
        parent::$conexion = null;
        return $stmt;
    }
 
 
    public function filtrar($campo,$valor){
     $sql="SELECT * FROM articulos WHERE $campo='{$valor}'";
     var_dump($sql);
     $stmt=parent::$conexion->prepare($sql);
 
     try{
         $stmt->execute();
     }catch(PDOException $ex){
         die("Error al filtrar ".$ex->getMessage());
     }
     parent::$conexion = null;
     return $stmt;
 
 }
 




    /*
        ###########################

             GETTERS/SETTERS

        ###########################
    */


    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }


    /**
     * Set the value of precio
     *
     * @return  self
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }


    /**
     * Set the value of categoria_id
     *
     * @return  self
     */
    public function setCategoria_id($categoria_id)
    {
        $this->categoria_id = $categoria_id;

        return $this;
    }
}
