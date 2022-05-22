<?php
require_once "connection.php";
require_once "utilidades.php";


session_start();


/**
 * @author SalvadorAsquit
 */

class Coment
{

    /**
     * constructor
     * @param String $ip es la ip de la base de datos a la que nos conectaremos
     * @param String $usu es el usuario de la base de datos
     * @param String $pass es la contraseña para acceder a la base de datos
     * @param String $bd la base de datos para conectar
     */
    // public function __construct($ip, $usu, $pass, $bd)
    public function __construct()

    {


        //coneccion para la base de datos
        $coneccion = new Connection();
        $this->mysqli = $coneccion->coneccion_Mysqli();
    }

    /**
     * @param String $usuario un usuario de la base de datos
     * @param String $puntuacion una puntuacion del comentario
     * @param String $destino el destino del usuario
     * @param String $comnetario el comnetario del usuario
     */
    function comnetar($usuario, $puntuacion, $destino, $comnetario)
    {
        $fecha = new DateTime();
        $fecha = $fecha->format('Y-m-d\ H:i:s');

        $sql = "INSERT INTO `comentarios` VALUES ('','{$usuario}', '{$comnetario}', {$puntuacion}, '{$destino}', '{$fecha}');";

        $result = $this->mysqli->query($sql);

        if ($result) {
            $response = array(
                "status" => "success",
                "msg" => "Comentado con exito"
            );
            return $response;
        } else {
            $response = array(
                "status" => "Fail",
                "msg" => "Fallo en la base de datos"
            );
            return $response;
        }
    }
    /** 
    * @param String $admin se le pasa usu o admin dependiendo de si es admin
     */
    function mostrarTablaComentarios($admin)
    {

        $sql = "SELECT 
        numero_de_comentario as Referencia, usuario as Usuario, experiencia as Comentario, puntuacion as Puntuacion, destino as Destino , fecha as Fecha
        FROM `comentarios`";

        $result = $this->mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        if ($admin == "admin") {
            foreach ($datos as $key => $value) {

                $editar = array("EDITAR" => "<button type='button' onclick='editarComentario(this.id)' class='btn btn-sm btn-link edicion' id='{$value['Referencia']}' data-bs-toggle='modal' data-bs-target='#modal_Editar'><img src='../lib/feather/edit.svg'></button>");
                $eliminar = array("ELIMINAR" => "<button type='button' onclick='eliminarRegistro(this.id)' class='btn btn-sm btn-link edicion' id='{$value['Referencia']}'><img src='../lib/feather/trash-2.svg'></button>");
    
                $datos[$key] = array_merge($editar, $eliminar, $value);
            }
        }

        return $response = procesar_Datatable($datos);
    }
    function editarComentario($id, $puntuacion, $comentario, $destino)
    {
        $sql = "UPDATE comentarios 
        SET experiencia = '{$comentario}', puntuacion = {$puntuacion}, destino = '{$destino}'
        WHERE
        numero_de_comentario = {$id}";

        $result = $this->mysqli->query($sql);

        if ($result) {
            $response = array(
                "status" => "success",
                "msg" => "Modificado con exito"
            );
            return $response;
        } else {
            $response = array(
                "status" => "Fail",
                "msg" => "Fallo en la base de datos"
            );
            return $response;
        }
    }
    function eliminarComentario($id)
    {
        $sql = "DELETE FROM comentarios WHERE numero_de_comentario = {$id};";

        $result = $this->mysqli->query($sql);

        if ($result) {
            $response = array(
                "status" => "success",
                "msg" => "Eliminado con exito"
            );
            return $response;
        } else {
            $response = array(
                "status" => "Fail",
                "msg" => "Fallo en la base de datos"
            );
            return $response;
        }
    }
}
