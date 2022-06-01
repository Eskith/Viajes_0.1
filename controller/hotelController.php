<?php
require_once "../model/hotelModel.php";

$hotel = new Hotel();

switch ($_POST["service"]) {
    case 'mostrartabla':


        $response = $hotel->mostrarTablas($_POST["tipo"]);

        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }
        break;
    case 'reservar':

        if (!empty($_POST["habitaciones_reserva"]) > 0 && !empty($_POST["entrada_reserva"])  && !empty($_POST["salida_reserva"])) {
            if ($_POST["entrada_reserva"] < $_POST["salida_reserva"]) {

                $cliente = $_SESSION["usuario"]["nombre"];
                $dni =  $_SESSION["usuario"]["dni"];
                $hotelnombre = $_POST["hotel_nombre_reserva"];
                $numeroHabitaciones = $_POST["habitaciones_reserva"];
                $precio =  $_POST["precio_reserva"];
                $entrada = $_POST["entrada_reserva"];
                $entrada = str_replace("T", " ", $entrada);
                $salida = $_POST["salida_reserva"];
                $salida = str_replace("T", " ", $salida);

                $hotel = new Hotel();
                $response = $hotel->reservar($cliente, $dni, $hotelnombre, $numeroHabitaciones, $precio, $entrada, $salida);
            } else {
                $response = array(
                    "status" => "Fail",
                    "msg" => "No Puedes Salir Antes De Entrar ¬¬"
                );
            }
        } else {
            $response = array(
                "status" => "Fail",
                "msg" => "Fallo debe rellenar todos los campos"
            );
        }

        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }
        break;


    case 'delete':

        $response = $hotel->delete($_POST["tipo"], $_POST["id"]);

        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }

        break;

    case 'editar':

        $nombre = $_POST["nombre_hotel"];
        $compañia = $_POST["compañia"];
        $pais = $_POST["pais_hotel"];
        $ciudad = $_POST["ciudad_hotel"];
        $ubicacion = $_POST["ubicacion_hotel"];
        $estrellas = $_POST["estrellas_hotel"];
        $habitaciones = $_POST["habitaciones"];
        $precio = str_replace(",", " .", $_POST["Precio"]);


        $response = $hotel->editar($nombre, $compañia, $pais, $ciudad, $ubicacion, $estrellas, $habitaciones, $precio);


        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }



        break;
    case 'pay':
        $response = $hotel->pagar($_POST["id"]);
        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }
        break;
    case 'añadir':

        $nombre = $_POST["nombre_hotel_add"];
        $compañia = $_SESSION["usuario"]["nombre"];
        $pais = $_POST["pais_hotel_add"];
        $ciudad = $_POST["ciudad_hotel_add"];
        $ubicacion = $_POST["ubicacion_hotel_add"];
        $estrellas = $_POST["estrellas_hotel_add"];
        $habitaciones = $_POST["habitaciones_add"];
        $precio = str_replace("T", " ", $_POST["Precio_add"]);
        

        $response = $hotel->añadir($nombre, $compañia, $pais, $ciudad, $ubicacion, $estrellas, $habitaciones, $precio);
        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }

        break;
}
