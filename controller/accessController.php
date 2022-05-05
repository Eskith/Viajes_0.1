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

        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
        // exit;

        $email = strtolower($_POST["email_register"]);
        $usu = strtolower($_POST["usu_register"]);
        $pass = md5($_POST["pass_register"]);
        $dni = $_POST["dni_register"];
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
        }else{
            $response = "codigo Erroneo";
            echo json_encode($response);
        }

        break;
    default:
        # code...
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
