<?php
require_once "../model/accessModel.php";


$access = new Access("localhost", "root", "", "Viajes_0.2");

switch ($_POST["service"]) {
    case 'login':
        $pass = md5($_POST["pass_login"]);
        $email = $_POST["email_login"];

        $response = $access->Login($email, $pass);

        echo json_encode($response);
        break;

    case 'register':

        $email = strtolower($_POST["email_register"]);
        $usu = strtolower($_POST["usu_register"]);
        $pass = md5($_POST["pass_register"]);
        $dni = strtolower($_POST["dni_register"]);
        $name = strtolower($_POST["nombre_register"]);
        $subname = strtolower($_POST["apellidos_register"]);
        $location = $_POST["pais_register"];
        $phone = $_POST["telefono_register"];

        $response = $access->signUp($email, $usu, $pass, $dni, $name, $subname, $location, $phone, 0);

        echo json_encode($response);

        break;

    case 'recovery':
        $email = $_POST["email_recovery"];

        $response = $access->recovery($email);

        echo json_encode($response);

        break;

    case 'change':
        $codeUsuario = $_POST["codigo_recovery"];
        $newPass = md5($_POST["pass_new"]);

        if ($codeUsuario == $_SESSION["code"]) {

            $response = $access->changePass($newPass);
            echo json_encode($response);
        } else {
            $response = "codigo Erroneo";
            echo json_encode($response);
        }

        break;

    case 'usuarioLogeado':
        if (!isset($_SESSION["usuario"]["usuario"])) {
            $response = [
                "status" => "usuario no logeado",
                "usuario" => "usuario no logeado",
                "tipo" => "usuario no logeado"
            ];
        }else{
            $response = [
                "status" => "logeado",
                "usuario" => $_SESSION["usuario"]["usuario"],
                "tipo" => $_SESSION["usuario"]["administrador"]
            ];
        }
        echo json_encode($response);

        break;
        case 'mostrar_Datos':          
            if (!isset($_SESSION["usuario"]["usuario"])) {
                $response = [
                    "status" => "usuario no logeado",
                    "usuario" => "usuario no logeado",
                ];
            }else{
                $response = [
                    "status" => "logeado",
                    "dni" => $_SESSION["usuario"]["dni"],
                    "nombre" => $_SESSION["usuario"]["nombre"],
                    "apellidos" => $_SESSION["usuario"]["apellidos"],
                    "pais" => $_SESSION["usuario"]["pais"],
                    "telefono" => $_SESSION["usuario"]["telefono"],
                    "email" => $_SESSION["usuario"]["email"],
                    "usuario" => $_SESSION["usuario"]["usuario"],
                    "password" => "********"
                ];
            }
            echo json_encode($response);
            break;
    case 'logout':

        session_destroy();
        $response = [
            "status" => "success",
            "msg" => "Logout"
        ];
        echo json_encode($response);
        break;

}









































// echo json_encode($_POST);

// $mysqli = new mysqli("localhost", "root", "", "test");

//         // comprueba que no falle la coneccion
//         if ($mysqli->connect_error) {
//             die("Connection failed: " . $mysqli->connect_error);
//         }

//         $sql = "SELECT
//         *
//     FROM
//         basic";

// $resultado = $mysqli->query($sql);

// $mysqli->close();

// // creamos la array con los datos y con las cabeceras
// while ($row = $resultado->fetch_assoc()) {
//     $data[] = $row;
// }


// $columns = array_keys($data[0]);
        

// $i = 0;

// $bool = false;

// foreach ($columns as $key => $value) {

//     $columns[$key] = array('data' => $value);
//     $columnsDefs[] = array('title' => $value, 'targets' => $i, 'visible' => true, 'searchable' => true);
//     $i++;
// }

// $datos = array(
//     'data' => $data,
//     'columns' => $columns,
//     'columnsDefs' => $columnsDefs,
// );

// echo json_encode($datos);
