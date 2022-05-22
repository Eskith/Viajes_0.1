<?php
require_once "../model/configModel.php";

$config = new Config();

switch ($_POST["service"]) {
    case 'Edit':

        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["usuario"] == "hotelera") {
                $nombre = ($_POST["nombre_Edit"] == "") ? $_POST["nombre_Usuario"] : $_POST["nombre_Edit"];
                $telefono = ($_POST["telefono_Edit"] == "") ? $_POST["telefono_Usuario"] : $_POST["telefono_Edit"];
                $email = ($_POST["email_Edit"] == "") ? $_POST["email_Usuario"] : $_POST["email_Edit"];
                $usuario = $_POST["usuario_Usuario"];
                $password = $_POST["password_Edit"];
                $tipo = "hotelera";
                
                $response = $config->edit_Parametros($nombre, $apellidos = null, $pais = null, $telefono, $email, $usuario, $password, $tipo);

            } else {
                $nombre = ($_POST["nombre_Edit"] == "") ? $_POST["nombre_Usuario"] : $_POST["nombre_Edit"];
                $telefono = ($_POST["telefono_Edit"] == "") ? $_POST["telefono_Usuario"] : $_POST["telefono_Edit"];
                $email = ($_POST["email_Edit"] == "") ? $_POST["email_Usuario"] : $_POST["email_Edit"];
                $usuario = $_POST["usuario_Usuario"];
                $password = $_POST["password_Edit"];
                $tipo = "aerolinea";

                $response = $config->edit_Parametros($nombre, $apellidos = null, $pais = null, $telefono, $email, $usuario, $password, $tipo);

            }
        } else {
            $nombre = ($_POST["nombre_Edit"] == "") ? $_POST["nombre_Usuario"] : $_POST["nombre_Edit"];
            $apellidos = ($_POST["apellidos_Edit"] == "") ? $_POST["apellidos_Usuario"] : $_POST["apellidos_Edit"];
            $pais = ($_POST["pais_Edit"] == "") ? $_POST["pais_Usuario"] : $_POST["pais_Edit"];
            $telefono = ($_POST["telefono_Edit"] == "") ? $_POST["telefono_Usuario"] : $_POST["telefono_Edit"];
            $email = ($_POST["email_Edit"] == "") ? $_POST["email_Usuario"] : $_POST["email_Edit"];
            $usuario = $_POST["usuario_Usuario"];
            $password = $_POST["password_Edit"];
            $tipo = "usuario";

            $response = $config->edit_Parametros($nombre, $apellidos, $pais, $telefono, $email, $usuario, $password, $tipo);

        }

        
        echo json_encode($response);

        break;
}
