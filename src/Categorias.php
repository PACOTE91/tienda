<?php

namespace Src;

use PDOException;
use Faker;
use PDO;



class Categorias extends Conexion
{

    private $id;
    private $nombre;
    private $description;



    /*
    #####################
        Constructor
    #####################

    Llama al constructor de la función padre (Conexión)
    */
    public function __construct()
    {
        parent::__construct();
    }



    /*
    #####################
            CRUD
    #####################
    */

    //Crea una categoría
    public function crear()
    {
        $sql = "INSERT INTO categorias (nombre,descripcion) VALUES (:n,:d)";
        $stmt = parent::$conexion->prepare($sql);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->description

            ]);
        } catch (PDOException $ex) {
            die("Error al insertar categorías " . $ex->getMessage());
        }
        parent::$conexion = null;
    }



    //Elimina un elemento que se pasa por parámetro
    public function borra($id)
    {
        $sql = "DELETE FROM categorias WHERE id=:id";
        $stmt = parent::$conexion->prepare($sql);
        try {
            $stmt->execute([
                ':id' => $id

            ]);
        } catch (PDOException $ex) {
            die("Error al borrar elemento " . $ex->getMessage());
        }
        parent::$conexion = null;
    }



    //Si le pasamos el id por parametro nos devolverá un registro, sino nos devolverá todos los registros
    public function leerTodos($id = null)
    {
        if ($id != null) {
            $sql = "SELECT * FROM categorias WHERE id=$id";
        } else {
            $sql = "SELECT * FROM categorias";
        }
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al devolver la categoría " . $ex->getMessage());
        }
        parent::$conexion = null;

        return $stmt;
    }

    //Función que actualiza los elementos
    public function actualizar()
    {
        $sql = "UPDATE categorias SET nombre=:n, descripcion=:d WHERE id={$this->id}";

        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->description

            ]);
        } catch (PDOException $ex) {
            die("Error actualizando" . $ex->getMessage());
        }
        parent::$conexion = null;
    }

    /*
    #####################
        FIN  CRUD
    #####################
    */

    /*
    #####################
       OTRAS FUNCIONES
    #####################
    */


    //Funcion que nos devuelve el nombre y el id de las categorías (para poder usarlos en artículos)
    public function devuelveNombre()
    {
        $sql = "SELECT id, nombre FROM categorias";
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al comprobar si la base de datos está vacía");
        }
        parent::$conexion = null;
        return $stmt;
    }





    //Con esta función generamos en nuestra base de datos elementos para tabla categorías
    public function categorias_generator($number)
    {
        //Si la base de ddatos esta vacía, generamos con Faker datos
        if ($this->bdvacia()) {
            for ($i = 0; $i < $number; $i++) {
                $nombre = Faker\Factory::create('es_ES')->unique()->sentence(1);
                $description = Faker\Factory::create('es_ES')->text(100);
                (new Categorias)->setDescription($description)->setNombre($nombre)->crear();
            }
        }
    }




    //Esta función nos devolvera true (si la base de datos esta vacía) o falso si ya contiene datos.
    public function bdvacia()
    {
        $sql = "SELECT * FROM categorias";
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute();
            $filas = $stmt->rowCount();
            if ($filas == 0) {
                $bool = true;
            } else {
                $bool = false;
            }
        } catch (PDOException $ex) {
            die("Error al comprobar si la base de datos está vacía");
        }
        parent::$conexion = null;
        return $bool;
    }

    //Función que devuelve el numero de registros con ese nombre o ese id, en este caso pasamos el nombre de categoria por parametro
    //y el id por set 
    //Despues evaluaremos que si es 0, no existe registro, de lo contrario hay un valor duplicado.
    public function uniquenombre($nombre)
    {
        if(isset($this->id)){
            $sql = "SELECT * FROM categorias WHERE id!={$this->id} AND nombre=:n";
        }else{
            $sql = "SELECT * FROM categorias WHERE nombre=:n";
        }
        $stmt = parent::$conexion->prepare($sql);

        try {
            $stmt->execute([
                ':n' => $nombre
            ]);
            $filas = $stmt->rowCount();
        } catch (PDOException $ex) {
            die("Error al comprobar Nombre unico..." . $ex->getMessage());
        }
        parent::$conexion = null;
        return $filas;
    }

    //Esta funcion la usaremos en el Faker de Articulos para generar arrays ids aleatorios con los valores ya creados
    public function devuelvecategorias()
    {
        $sql = "SELECT id FROM categorias";
        $stmt = parent::$conexion->prepare($sql);
        try {
            $stmt->execute([]);
        } catch (PDOException $ex) {
            die("Error al comprobar Nombre unico..." . $ex->getMessage());
        }
        $id = [];
        while ($fila = $stmt->fetch(PDO::FETCH_OBJ)) {
            $id[] = $fila->id;
        }
        parent::$conexion = null;
        return $id;
    }


   //Funcion que nos devuelve el nombre y el id de las categorías (para poder usarlos en artículos)
   public function nombre($id)
   {
       $sql = "SELECT nombre FROM categorias WHERE id=$id";
       $stmt = parent::$conexion->prepare($sql);

       try {
           $stmt->execute();
       } catch (PDOException $ex) {
           die("Error al comprobar si la base de datos está vacía");
       }
       parent::$conexion = null;
       return $stmt;
   }




   public function valorafiltrar($categoria,$id)
   {
       $sql = "SELECT $categoria FROM categorias WHERE id=$id";
       $stmt = parent::$conexion->prepare($sql);
       try {
           $stmt->execute();
       } catch (PDOException $ex) {
           die("Error al devolver el filtro..." . $ex->getMessage());
       }       
       parent::$conexion = null;
       return $stmt->fetch(PDO::FETCH_OBJ);
   }


   public function filtrar($campo,$valor){
    $sql="SELECT * WHERE $campo=$valor";
    $stmt=parent::$conexion->prepare($sql);

    try{
        $stmt->execute();
    }catch(PDOException $ex){
        die("Error al filtrar ".$ex->getMessage());
    }
    parent::$conexion = null;
    return $stmt;

}

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
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
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }



    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

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
}