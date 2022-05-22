<?php
require_once "../model/vuelosModel.php";

$vuelos = new Vuelos();

switch ($_POST["service"]) {
    case 'mostrartabla':

        $response = $vuelos->mostrarTablas($_POST["tipo"]);

        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }
        break;
    case 'reservar':

        if (!empty($_POST["billetes_reserva"]) > 0 || !empty($_POST["billetes1_reserva"]) > 0) {
            $billetes = $_POST["billetes_reserva"] + $_POST["billetes1_reserva"];
            if (($_POST["kg_max_reserva"] * $billetes) > $_POST["kg_reserva"]) {
                $matricula = $_POST["matricula_reserva"];
                $billetes = $_POST["billetes_reserva"];
                $billetesClase = $_POST["billetes1_reserva"];
                $plazas = $_POST["plazas"];
                $plazasvips = $_POST["plazasvips"];
                $salida = $_POST["salida_reserva"];
                $nombre = $_SESSION["usuario"]["nombre"];
                $dni = $_SESSION["usuario"]["dni"];
                $aerolinea = $_POST["aerolinea_reserva"];
                $llegada = $_POST["llegada_reserva"];
                $maleta = $_POST["maletas_reserva"];
                $precio = $_POST["precio_reserva"];
                $preciovip = $_POST["precio2_reserva"];
                $preciokg = $_POST["precio3_reserva"];
                $kgllevados = $_POST["kg_reserva"];

                $response = $vuelos->reservar($matricula, $billetes, $billetesClase, $plazas, $plazasvips, $salida, $nombre, $dni, $aerolinea, $llegada, $maleta, $precio, $preciovip, $preciokg, $kgllevados);
            } else {
                $response = array(
                    "status" => "Fail",
                    "msg" => "Fallo has superado los kg permitidos por usuario"
                );
            }
        } else {
            $response = array(
                "status" => "Fail",
                "msg" => "Fallo debe poner un billete al menos"
            );
        }
        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }
        break;


    case 'delete':
        if ($_POST["tipo"] == 2) {
            $response = $vuelos->delete($_POST["tipo"], $_POST["id"], null);
        } else {
            $id = explode("+", $_POST["id"]);

            $response = $vuelos->delete($_POST["tipo"], $id[0], $id[1]);
        }

        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }

        break;

    case 'editar':

        if (isset($_SESSION["usuario"]["administrador"]) == 1) {
            $_POST["tipo"] = 1;
        }



        switch ($_POST["tipo"]) {
            case 0:
                if ($_POST["salida_aerolinea"] == null) {
                    $salida = $_POST["salida_aerolineaold"];
                } else {
                    $salida = $_POST["salida_aerolinea"];
                    $salida = str_replace("T", " ", $salida);
                }
                if ($_POST["llegada_aerolinea"] == null) {
                    $llegada = $_POST["llegada_aerolineaold"];
                } else {
                    $llegada = $_POST["llegada_aerolinea"];
                    $llegada = str_replace("T", " ", $llegada);
                }

                $matricula = $_POST["matricula_aerolinea"];
                $salidaPais = $_POST["salida_pais_aerolinea"];
                $salidaCiudad = $_POST["salida_ciudad_aerolinea"];
                $llegadaPais = $_POST["llegada_pais_aerolinea"];
                $llegadaCiudad = $_POST["llegada_ciudad_aerolinea"];
                $precio = str_replace(",", " .", $_POST["precio_aerolinea"]);
                $precioVip = str_replace(",", " .", $_POST["precio2_aerolinea"]);
                $precioMaleta = str_replace(",", " .", $_POST["precio3_aerolinea"]);
                $billete = $_POST["billetes_aerolinea"];
                $billetesVips = $_POST["billetes1_aerolinea"];
                $peso = $_POST["kg_max_aerolinea"];



                $response = $vuelos->editar(
                    $_POST["tipo"],
                    $matricula,
                    $salida,
                    $llegada,
                    $salidaPais,
                    $salidaCiudad,
                    $llegadaPais,
                    $llegadaCiudad,
                    $precio,
                    $precioVip,
                    $precioMaleta,
                    $billete,
                    $billetesVips,
                    $peso
                );

                try {
                    echo json_encode($response, JSON_THROW_ON_ERROR);
                } catch (\JsonException $exception) {
                    echo $exception->getMessage();
                }

                break;

            case 1:
                if ($_POST["salida_aerolinea"] == null) {
                    $salida = $_POST["salida_aerolineaold"];
                } else {
                    $salida = $_POST["salida_aerolinea"];
                    $salida = str_replace("T", " ", $salida);
                }
                if ($_POST["llegada_aerolinea"] == null) {
                    $llegada = $_POST["llegada_aerolineaold"];
                } else {
                    $llegada = $_POST["llegada_aerolinea"];
                    $llegada = str_replace("T", " ", $llegada);
                }

                $matricula = $_POST["matricula_aerolinea"];
                $salidaPais = $_POST["salida_pais_aerolinea"];
                $salidaCiudad = $_POST["salida_ciudad_aerolinea"];
                $llegadaPais = $_POST["llegada_pais_aerolinea"];
                $llegadaCiudad = $_POST["llegada_ciudad_aerolinea"];
                $precio = str_replace(",", " .", $_POST["precio_aerolinea"]);
                $precioVip = str_replace(",", " .", $_POST["precio2_aerolinea"]);
                $precioMaleta = str_replace(",", " .", $_POST["precio3_aerolinea"]);
                $billete = $_POST["billetes_aerolinea"];
                $billetesVips = $_POST["billetes1_aerolinea"];
                $peso = $_POST["kg_max_aerolinea"];



                $response = $vuelos->editar(
                    $_POST["tipo"],
                    $matricula,
                    $salida,
                    $llegada,
                    $salidaPais,
                    $salidaCiudad,
                    $llegadaPais,
                    $llegadaCiudad,
                    $precio,
                    $precioVip,
                    $precioMaleta,
                    $billete,
                    $billetesVips,
                    $peso
                );

                try {
                    echo json_encode($response, JSON_THROW_ON_ERROR);
                } catch (\JsonException $exception) {
                    echo $exception->getMessage();
                }

                break;
        }


        break;
    case 'pay':
        $response = $vuelos->pagar($_POST["id"]);
        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }
        break;
    case 'añadir':
        $matricula = $_POST["matricula_aerolinea_add"];
        $aerolinea = $_SESSION["usuario"]["nombre"];
        $salidaPais = $_POST["salida_pais_aerolinea_add"];
        $salidaCiudad = $_POST["salida_ciudad_aerolinea_add"];
        $llegadaPais = $_POST["llegada_pais_aerolinea_add"];
        $llegadaCiudad = $_POST["llegada_ciudad_aerolinea_add"];
        $salida = str_replace("T", " ", $_POST["salida_aerolinea_add"]);
        $llegada = str_replace("T", " ", $_POST["llegada_aerolinea_add"]);
        $precio = $_POST["precio_aerolinea_add"];
        $precio = str_replace(",", " .", $precio);
        $precioVip = str_replace(",", " .", $_POST["precio2_aerolinea_add"]);
        $precioMaleta = str_replace(",", " .", $_POST["precio3_aerolinea_add"]);
        $billete = $_POST["billetes_aerolinea_add"];
        $billetesVips = $_POST["billetes1_aerolinea_add"];
        $peso = $_POST["kg_max_aerolinea_add"];

        $response = $vuelos->añadir($matricula, $aerolinea, $salidaPais, $salidaCiudad, $llegadaPais, $llegadaCiudad, $salida, $llegada, $precio, $precioVip, $precioMaleta, $peso, $billete, $billetesVips);
        try {
            echo json_encode($response, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            echo $exception->getMessage();
        }

        break;
}
