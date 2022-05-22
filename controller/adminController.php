<?php
require_once "../model/adminModel.php";


$admin = new Admin();

switch ($_POST["service"]) {
    case 'mostrarTablas':

        $response = $admin->mostrarTablas($_POST["tipo"]);
        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }
        break;

    case 'delete':

        $response = $admin->deleteUsuario($_POST["id"], $_POST["tipo"]);
        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }
        break;

    case 'edit':

        switch ($_POST["tipo"]) {
            case 1:
                $dni = $_POST["dni_Usuario_edit"];
                $nombre = $_POST["nombre_Usuario_edit"];
                $apellidos = $_POST["apellidos_Usuario_edit"];
                $pais = $_POST["pais_Usuario_edit"];
                $telefono = $_POST["telefono_Usuario_edit"];
                $email = $_POST["email_Usuario_edit"];
                $usuario = $_POST["usuario_Usuario_edit"];
                $pass = md5($_POST["password_Usuario_edit"]);
                $puntos = $_POST["puntos_Usuario_edit"];

                switch ($_POST["rol_Usuario_edit"]) {
                    case 'usuario':
                        $rol = 0;
                        break;
                    case 'administrador':
                        $rol = 1;
                        break;
                    case 'aerolinea':
                        $rol = 2;
                        break;
                    case 'hotelera':
                        $rol = 3;
                        break;
                }

                $response = $admin->editar($dni, $nombre, $apellidos, $pais, $telefono, $email, $usuario, $pass, $puntos, $rol, $_POST["tipo"]);
                break;

            case 2:
                $nombre = $_POST["nombre_aerolinea_edit"];
                $telefono = $_POST["telefono_edit_aerolinea"];
                $email = $_POST["email_aerolinea_edit"];
                $usuario = $_POST["usuario_aerolinea_edit"];
                $pass = md5($_POST["password_aerolinea_edit"]);

                $response = $admin->editar($dni = null, $nombre, $apellidos = null, $pais = null, $telefono, $email, $usuario, $pass, $puntos = null, $rol = null, $_POST["tipo"]);
                break;

            case 3:
                $nombre = $_POST["nombre_hotelera_edit"];
                $telefono = $_POST["telefono_hotelera_edit"];
                $email = $_POST["email_hotelera_edit"];
                $usuario = $_POST["usuario_hotelera_edit"];
                $pass = md5($_POST["password_hotelera_edit"]);

                $response = $admin->editar($dni = null, $nombre, $apellidos = null, $pais = null, $telefono, $email, $usuario, $pass, $puntos = null, $rol = null, $_POST["tipo"]);
                break;
        }

        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }
        break;

    case 'añadir':

        switch ($_POST["sign_Tipo_Admin"]) {
            case 'usuario':
                $dni = $_POST["dni_Sing_Admin"];
                $nombre = $_POST["nombre_Sing_Admin"];
                $apellidos = $_POST["apellidos_Sing_Admin"];
                $pais = $_POST["pais_Sing_Admin"];
                $telefono = $_POST["telefono_Sing_Admin"];
                $email = $_POST["email_Sing_Admin"];
                $usuario = $_POST["usuario_Sing_Admin"];
                $pass = md5($_POST["pass_Sing_Admin"]);
                $puntos = $_POST["puntos_Sing_Admin"];
                $rol = $_POST["rol_Sing_Admin"];
                $tipo = $_POST["sign_Tipo_Admin"];

                $response = $admin->añadir($dni, $nombre, $apellidos, $pais, $telefono, $email, $usuario, $pass, $puntos, $rol, $tipo);
                break;
            case 'aerolinea':
                $nombre = strtolower($_POST["nombre_Sing_Admin"]);
                $telefono = $_POST["telefono_Sing_Admin"];
                $email = $_POST["email_Sing_Admin"];
                $usuario = $_POST["usuario_Sing_Admin"];
                $pass = md5($_POST["pass_Sing_Admin"]);
                $tipo = $_POST["sign_Tipo_Admin"];
                
                $response = $admin->añadir($dni = null, $nombre, $apellidos = null, $pais = null, $telefono, $email, $usuario, $pass, $puntos = null, $rol = null, $tipo);
                break;

                break;
            case 'hotelera':
                $nombre = strtolower($_POST["nombre_Sing_Admin"]);
                $telefono = $_POST["telefono_Sing_Admin"];
                $email = $_POST["email_Sing_Admin"];
                $usuario = $_POST["usuario_Sing_Admin"];
                $pass = md5($_POST["pass_Sing_Admin"]);
                $tipo = $_POST["sign_Tipo_Admin"];

                $response = $admin->añadir($dni = null, $nombre, $apellidos = null, $pais = null, $telefono, $email, $usuario, $pass, $puntos = null, $rol = null, $tipo);

                break;
        }

        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }
        break;
}
