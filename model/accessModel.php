<?php
require_once "connection.php";
require_once "utilidades.php";
require_once "../lib/PHPMailer/src/PHPMailer.php";
require_once "../lib/PHPMailer/src/SMTP.php";

session_start();


/**
 * @author SalvadorAsquit
 */

class Access
{
    public $email;
    public $pass;



    /**
     * constructor
     * @param ip $String es la ip de la base de datos a la que nos conectaremos
     * @param usu $String es el usuario de la base de datos
     * @param pass $String es la contraseña para acceder a la base de datos
     * @param bd $String la base de datos para conectar
     */
    // public function __construct($ip, $usu, $pass, $bd)
    public function __construct()

    {


        //coneccion para la base de datos
        $coneccion = new Connection();
        $this->mysqli = $coneccion->coneccion_Mysqli();
    }

    function Login($email, $pass)
    {
        $datos = self::filtraUsuario($email);
        $response = array(
            "usuario" => " ",
            "status" => "Fail",
            "msg" => "Email o Pass incorrecta"
        );

        if (is_string($datos)) {

            $datos = verificaEmail($email, "aerolinea");

            if ($datos["status"] == "404") {
                $datos = verificaEmail($email, "hotelera");
                if ($datos["status"] == "404") {
                    return $response;
                } else {
                    $datos = $datos["datos"];
                    if (strtolower($datos[0]["email"]) == strtolower($email) && $datos[0]["password"] == $pass) { 
                        $_SESSION["usuario"] = $datos[0];
                        $_SESSION["usuario"]["rol"] = "hotelera";
                        $response = array(
                            "usuario" => $datos[0]["usuario"],
                            "status" => "Login",
                            "login" => "hotelera",
                            "tipo" => "3"
                        );
                        return $response;
                    } else {
                        $response = array(
                            "usuario" => " ",
                            "status" => "Fail",
                            "msg" => "Email o Pass incorrecta"
                        );
                        return $response;
                    }
                }
            } else {
                $datos = $datos["datos"];
                if (strtolower($datos[0]["email"]) == strtolower($email) && $datos[0]["password"] == $pass) { 
                    $_SESSION["usuario"] = $datos[0];
                    $_SESSION["usuario"]["rol"] = "aerolinea";
                    $response = array(
                        "usuario" => $datos[0]["usuario"],
                        "status" => "Login",
                        "login" => "aerolinea",
                        "tipo" => "2"
                    );
                    return $response;
                } else {
                    $response = array(
                        "usuario" => " ",
                        "status" => "Fail",
                        "msg" => "Email o Pass incorrecta"
                    );
                    return $response;
                }
            }
        } else {
            if (strtolower($datos[0]["email"]) == strtolower($email) && $datos[0]["password"] == $pass) {
                switch ($datos[0]["administrador"]) {
                    case '0':
                        $_SESSION["usuario"] = $datos[0];
                        $response = array(
                            "usuario" => $datos[0]["usuario"],
                            "status" => "Login",
                            "login" => "usuario",
                            "tipo" => "0"
                        );
                        return $response;
                        break;

                    case '1':
                        $_SESSION["usuario"] = $datos[0];
                        $response = array(
                            "usuario" => $datos[0]["usuario"],
                            "status" => "Login",
                            "login" => "administrador",
                            "tipo" => "1"
                        );
                        return $response;
                        break;

                    default:
                        $response = array(
                            "usuario" => " ",
                            "status" => "Fail",
                            "msg" => "Fallo en la base de datos pongase en contacto con un administrador"
                        );
                        return $response;
                        break;
                }
            } else {
                return  $response;
            }
        }
    }

    function signUp($email, $usuario, $pass, $dni, $nombre, $apellido, $pais, $phone, $tipo)
    {
        $datos = self::buscarConcidencias($email, $dni, $usuario);

        if ($datos["status"] == "Fail") {
            return $datos;
        } else {
            $verificado = verificacion($usuario, $email);
            $usuario = strtolower($usuario);
            $nombre = strtolower($nombre);
            $dni = strtolower($dni);
            $email = strtolower($email);
            $apellido = strtolower($apellido);


            if ($verificado == "OK") {
                $sql = "INSERT INTO `usuario` 
                VALUES ('{$dni}', '{$nombre}', '{$apellido}', '{$pais}', {$phone}, '{$email}', '{$usuario}', '{$pass}', 0, 0);";

                $result = $this->mysqli->query($sql);

                if ($result) {
                    $response = array(
                        "status" => "success",
                        "msg" => "Registrado con exito"
                    );
                    return $response;
                } else {
                    $response = array(
                        "status" => "Fail",
                        "msg" => "Fallo en la base de datos"
                    );
                    return $response;
                }
            } else {
                $verificado["status"] = "Fail";
                return $verificado;
            }
        }
    }

    function recovery($email)
    {
        $datos = self::filtraUsuario($email);

        if ($datos == "usuario no en contrado") {
            $response = [
                "status" => "Fail",
                "msg" => "Email incorrecto"
            ];
            return $response;
        } else {
            $_SESSION["code"] = random_int(100, 999);
            $_SESSION["email_Recovery"] = $email;
            self::mail($email);
            return "";
        }
    }

    function changePass($pass)
    {
        $email = $_SESSION["email_Recovery"];

        $sql = "UPDATE usuario 
        SET `password` = '{$pass}' 
        WHERE
            email = '{$email}'";

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

    function mail($email)
    {
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';

        $body = "<p>Su codigo de recuperacion: ".$_SESSION["code"]."</p> <p>Solo sera valido durante 5 minutos, si no lo introduce su contraseña permanecera inalterable </p>";

        $mail->IsSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->SMTPDebug  = 1;
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ViajesSoter@gmail.com';
        $mail->Password   = 'ViajesSoter1234';
        $mail->SetFrom('ViajesSoter@gmail.com', "ViajesSoter");
        $mail->AddReplyTo('no-reply@mycomp.com', 'no-reply');
        $mail->Subject    = 'Codigo de recuperacion';
        $mail->MsgHTML($body);

        $mail->AddAddress($email, 'Gianni');
        $mail->send();
    }

    function buscarConcidencias($email, $dni, $usu)
    {
        $sql = "SELECT
        * 
        FROM
            usuario 
        WHERE
            email = '{$email}'";

        $result = $this->mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        if (!isset($datos)) {
            $sql = "SELECT
            * 
            FROM
                usuario 
                WHERE
            dni = '{$dni}'";

            $result = $this->mysqli->query($sql);

            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
            if (!isset($datos)) {
                $sql = "SELECT
                * 
                FROM
                    usuario 
                    WHERE
                usuario = '{$usu}'";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }
                if (!isset($datos)) {
                    $response = array(
                        "status" => "success",
                        "msg" => "No Existe el Usuario"
                    );
                    return $response;
                } else {
                    $response = array(
                        "status" => "Fail",
                        "msg" => "Fallo ese usuario ya existe"
                    );
                    return $response;
                }
            } else {
                $response = array(
                    "status" => "Fail",
                    "msg" => "Fallo ese dni ya existe"
                );
                return $response;
            }
        } else {
            $response = array(
                "status" => "Fail",
                "msg" => "Fallo ese Email ya existe"
            );
            return $response;
        }
    }

    function filtraUsuario($email)
    {
        $sql = "SELECT
        * 
        FROM
        usuario 
        WHERE
        email = '{$email}'";

        $result = $this->mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        if (!isset($datos)) {
            return "usuario no en contrado";
        } else {
            return $datos;
        }
    }
}
