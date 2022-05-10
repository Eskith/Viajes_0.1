<?php
require_once "../model/comentarioModel.php";

$coment = new Coment("localhost", "root", "", "Viajes_0.2");

switch ($_POST["service"]) {
    case 'comentar':

        $usuario = $_POST["usuario"];
        $puntuacion = $_POST["puntuacion"];
        $destino = $_POST["destino"];
        $comentario = $_POST["comentario"];

        $response = $coment->comnetar($usuario, $puntuacion, $destino, $comentario);

        echo json_encode($response);

        break;
    case 'tablaReseñas':

        $response = $coment->mostrarTablaComentarios($_POST["tipo"]);
        echo json_encode($response);

        break;
    case 'edit':
       
        $id = $_POST["referencia_Edit"];
        $puntuacion = $_POST["puntuacion_Edit"];
        $comentario = $_POST["comentario_Edit"];
        $destino = $_POST["destino_Edit"];

        $response = $coment->editarComentario($id, $puntuacion, $comentario, $destino);
        echo json_encode($response);

        break;

        case 'delete':

            $id = $_POST["id"];
            $response = $coment->eliminarComentario($id);
            echo json_encode($response);
            
            break;
}
