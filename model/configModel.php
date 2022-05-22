<?php
require_once "connection.php";

session_start();

/**
 * @author SalvadorAsquit
 */

class Config
{

    /**
     * constructor
     * @param String $ip es la ip de la base de datos a la que nos conectaremos
     * @param String $usu es el usuario de la base de datos
     * @param String $pass es la contraseña para acceder a la base de datos
     * @param String $bd la base de datos para conectar
     */
    public function __construct()
    {


        //coneccion para la base de datos
        $coneccion = new Connection();
        $this->mysqli = $coneccion->coneccion_Mysqli();
    }

    /**
     * @param String $nombre es el nombre del usuario
     * @param String $apellidos son los apellidos de los usuarios
     * @param String $pais es el pais del usuario
     * @param String $telefono es el telefono del usuario
     * @param String $email es el correo del usuario
     * @param String $usuario es el usuario con el que se logea
     * @param String $password es la contraseña que cambiara
     */
    function edit_Parametros($nombre, $apellidos, $pais, $telefono, $email, $usuario, $password, $tipo) 
    {

        switch ($tipo) {
            case 'usuario':
                if ($password == "") {
                    $sql = "UPDATE usuario 
                    SET nombre = '{$nombre}', apellidos = '{$apellidos}', pais = '{$pais}', telefono = {$telefono}, email = '{$email}'
                    WHERE
                    usuario = '{$usuario}'";
        
                    $result = $this->mysqli->query($sql);
                } else {
                    $pass = md5($password);
        
                    $sql = "UPDATE usuario 
                    SET nombre = '{$nombre}', apellidos = '{$apellidos}', pais = '{$pais}', telefono = {$telefono}, email = '{$email}', password = '{$pass}'
                    WHERE
                    usuario = '{$usuario}'";
        
                    $result = $this->mysqli->query($sql);
                $_SESSION["usuario"]["password"] = $password;
        
                }
        
                $_SESSION["usuario"]["nombre"] = $nombre;
                $_SESSION["usuario"]["apellidos"] = $apellidos;
                $_SESSION["usuario"]["pais"] = $pais;
                $_SESSION["usuario"]["telefono"] = $telefono;
                $_SESSION["usuario"]["email"] = $email;
        
        
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
                break;

            case 'hotelera':

                if ($password == "") {
                    $sql = "UPDATE hotelera 
                    SET nombre = '{$nombre}',telefono = {$telefono}, email = '{$email}'
                    WHERE
                    usuario = '{$usuario}'";
        
                    $result = $this->mysqli->query($sql);
                } else {
                    $pass = md5($password);
        
                    $sql = "UPDATE hotelera 
                    SET nombre = '{$nombre}', password = '{$pass}', telefono = {$telefono}, email = '{$email}'
                    WHERE
                    usuario = '{$usuario}'";
        
                    $result = $this->mysqli->query($sql);
                $_SESSION["usuario"]["password"] = $password;
        
                }
        
                $_SESSION["usuario"]["nombre"] = $nombre;
                $_SESSION["usuario"]["telefono"] = $telefono;
                $_SESSION["usuario"]["email"] = $email;
        
        
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
                break;
            case 'aerolinea':
                if ($password == "") {
                    $sql = "UPDATE aerolinea 
                    SET nombre = '{$nombre}',telefono = {$telefono}, email = '{$email}'
                    WHERE
                    usuario = '{$usuario}'";
        
                    $result = $this->mysqli->query($sql);
                } else {
                    $pass = md5($password);
        
                    $sql = "UPDATE hotelera 
                    SET nombre = '{$nombre}', password = '{$pass}', telefono = {$telefono}, email = '{$email}'
                    WHERE
                    usuario = '{$usuario}'";
        
                    $result = $this->mysqli->query($sql);
                $_SESSION["usuario"]["password"] = $password;
        
                }
        
                $_SESSION["usuario"]["nombre"] = $nombre;
                $_SESSION["usuario"]["telefono"] = $telefono;
                $_SESSION["usuario"]["email"] = $email;
        
        
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
                break;
        }


        
    }
}
