<?php
require_once "connection.php";
require_once "utilidades.php";

/**
 * @author SalvadorAsquit
 */

class Admin
{
    public $email;
    public $pass;



    /**
     * constructor
     */
    // public function __construct($ip, $usu, $pass, $bd)
    public function __construct()
    {


        //coneccion para la base de datos
        $coneccion = new Connection();
        $this->mysqli = $coneccion->coneccion_Mysqli();
    }

    function mostrarTablas($tipo)
    {
        switch ($tipo) {
            case 'usuarios':

                $sql = "SELECT 
                dni as DNI, nombre as Nombre, apellidos as Apellidos, pais as Pais, telefono as Telefono , email as Email, usuario as Usuario, password as Password, puntos as Puntos, administrador as Rol
                FROM `usuario`";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                foreach ($datos as $key => $value) {

                    $editar = array("EDITAR" => "<button type='button' onclick='editar(this.id,1)' class='btn btn-sm btn-link edicion' id='{$value['Usuario']}' data-bs-toggle='modal' data-bs-target='#modal_usuario_edit'><img src='./lib/feather/edit.svg'></button>");
                    $eliminar = array("ELIMINAR" => "<button type='button' onclick='eliminar(this.id,1)' class='btn btn-sm btn-link edicion' id='{$value['Usuario']}'><img src='./lib/feather/trash-2.svg'></button>");

                    $datos[$key] = array_merge($editar, $eliminar, $value);
                }

                return $response = procesar_Datatable($datos);
                break;

            case 'aerolinea':

                $sql = "SELECT 
                nombre as Nombre, usuario as Usuario, password as Password, telefono as Telefono, email as Email
                FROM `aerolinea`";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                foreach ($datos as $key => $value) {

                    $editar = array("EDITAR" => "<button type='button' onclick='editar(this.id,2)' class='btn btn-sm btn-link edicion' id='{$value['Usuario']}' data-bs-toggle='modal' data-bs-target='#modal_aerolinea_edit'><img src='./lib/feather/edit.svg'></button>");
                    $eliminar = array("ELIMINAR" => "<button type='button' onclick='eliminar(this.id,2)' class='btn btn-sm btn-link edicion' id='{$value['Usuario']}'><img src='./lib/feather/trash-2.svg'></button>");

                    $datos[$key] = array_merge($editar, $eliminar, $value);
                }


                return $response = procesar_Datatable($datos);
                break;

            case 'hotelera':

                $sql = "SELECT 
                nombre as Nombre, usuario as Usuario, password as Password, telefono as Telefono, email as Email
                FROM `hotelera`";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                foreach ($datos as $key => $value) {

                    $editar = array("EDITAR" => "<button type='button' onclick='editar(this.id,3)' class='btn btn-sm btn-link edicion' id='{$value['Usuario']}' data-bs-toggle='modal' data-bs-target='#modal_hotelera_edit'><img src='./lib/feather/edit.svg'></button>");
                    $eliminar = array("ELIMINAR" => "<button type='button' onclick='eliminar(this.id,3)' class='btn btn-sm btn-link edicion' id='{$value['Usuario']}'><img src='./lib/feather/trash-2.svg'></button>");

                    $datos[$key] = array_merge($editar, $eliminar, $value);
                }

                return $response = procesar_Datatable($datos);

                break;
        }
    }
    function deleteUsuario($id, $tipo)
    {
        switch ($tipo) {
            case 1:
                $sql = "DELETE FROM usuario WHERE usuario = '{$id}';";

                $result = $this->mysqli->query($sql);

                if ($result) {
                    return "success";
                } else {
                    return $result;
                }
                break;
            case 2:
                $sql = "DELETE FROM aerolinea WHERE usuario = '{$id}';";

                $result = $this->mysqli->query($sql);

                if ($result) {
                    return "success";
                } else {
                    return $result;
                }
                break;
            case 3:
                $sql = "DELETE FROM hotelera WHERE usuario = '{$id}';";

                $result = $this->mysqli->query($sql);

                if ($result) {
                    return "success";
                } else {
                    return $result;
                }
                break;
        }
    }

    function editar($dni, $nombre, $apellidos, $pais, $telefono, $email, $usuario, $pass, $puntos, $rol, $tipo)
    {
        switch ($tipo) {
            case 1:
                $sql = "UPDATE usuario 
                SET nombre = '{$nombre}', apellidos = '{$apellidos}', pais = '{$pais}', telefono = {$telefono}, email = '{$email}', usuario = '{$usuario}', password = '{$pass}', puntos = {$puntos}, administrador = {$rol}
                WHERE
                dni = '{$dni}'";

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
                }

                break;
            case 2:
                $sql = "UPDATE aerolinea 
                    SET nombre = '{$nombre}', telefono = {$telefono}, email = '{$email}', usuario = '{$usuario}', password = '{$pass}'
                    WHERE
                    usuario = '{$usuario}'";

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
                }

                break;
            case 3:
                $sql = "UPDATE hotelera 
                        SET nombre = '{$nombre}', telefono = {$telefono}, email = '{$email}', usuario = '{$usuario}', password = '{$pass}'
                        WHERE
                        usuario = '{$usuario}'";

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
                }
        }

        return $response;
    }

    function añadir($dni, $nombre, $apellidos, $pais, $telefono, $email, $usuario, $pass, $puntos, $rol, $tipo)
    {
        switch ($tipo) {
            case "usuario":

                $verificacion = verificacion($usuario, $email);

                if (verificacion($usuario, $email) == "OK") {
                    $sql = "INSERT INTO usuario
                    VALUES ('{$dni}','{$nombre}', '{$apellidos}','{$pais}',{$telefono},'{$email}','{$usuario}','{$pass}',{$puntos},{$rol});";

                    $result = $this->mysqli->query($sql);

                    if ($result) {
                        $response = array(
                            "status" => "success",
                            "msg" => "Añadido con exito"
                        );
                        return $response;
                    } else {
                        $response = array(
                            "status" => "Fail",
                            "msg" => "Fallo en la base de datos"
                        );
                        return $response;
                    }
                }else {
                    $verificacion["status"] = "Fail";
                    return $verificacion;
                }

                break;
            case "aerolinea":

                $verificacion = verificacion($usuario, $email);

                if (verificacion($usuario, $email) == "OK") {

                    $verificacion = verificaNombre($nombre, "aerolinea");
                    if ($verificacion["status"] == "404") {
                        $sql = "INSERT INTO aerolinea
                        VALUES ('{$nombre}','{$usuario}','{$pass}',{$telefono},'{$email}');";
    
                        $result = $this->mysqli->query($sql);
    
                        if ($result) {
                            $response = array(
                                "status" => "success",
                                "msg" => "Añadido con exito"
                            );
                            return $response;
                        } else {
                            $response = array(
                                "status" => "Fail",
                                "msg" => "Fallo en la base de datos"
                            );
                            return $response;
                        }
                    }else {
                        $verificacion["status"] = "Fail";
                        return $verificacion;
                    }
                }else {
                    $verificacion["status"] = "Fail";
                    return $verificacion;
                }
                break;

            case "hotelera":
                $verificacion = verificacion($usuario, $email);

                if (verificacion($usuario, $email) == "OK") {

                    $verificacion = verificaNombre($nombre, "hotelera");
                    if ($verificacion["status"] == "404") {
                        $sql = "INSERT INTO hotelera
                        VALUES ('{$nombre}','{$usuario}','{$pass}',{$telefono},'{$email}');";
    
                        $result = $this->mysqli->query($sql);
    
                        if ($result) {
                            $response = array(
                                "status" => "success",
                                "msg" => "Añadido con exito"
                            );
                            return $response;
                        } else {
                            $response = array(
                                "status" => "Fail",
                                "msg" => "Fallo en la base de datos"
                            );
                            return $response;
                        }
                    }else {
                        $verificacion["status"] = "Fail";
                        return $verificacion;
                    }
                }else {
                    $verificacion["status"] = "Fail";
                    return $verificacion;
                }
        }
    }
}
