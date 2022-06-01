<?php
require_once "connection.php";
require_once "utilidades.php";
session_start();



/**
 * @author SalvadorAsquit
 */

class Hotel
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
            case 'hoteles':

                $sql = "SELECT 
                nombre as Nombre, hotelera as Compañia, pais as Pais, ciudad as Ciudad, ubicacion as Ubicacion , estrellas as Estrellas, precio as Precio
                FROM `hotel`";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                return $response = procesar_Datatable($datos);

                break;

            case 'reservas':
                $usuario = $_SESSION["usuario"]["dni"];

                $sql = "SELECT 
                numero_de_la_reserva as Id, nombre_cliente as Nombre, dni_cliente as Dni, nombre_hotel as Hotel, numero_habitacion as 'Personas por Reserva' , precio as Precio, fecha_entrada as Entrada, fecha_salida as Salida, pagada as Pagada
                FROM `reserva_habitacion`
                WHERE dni_cliente = '{$usuario}'";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                return $response = procesar_Datatable($datos);

                break;

            case 'hoteles_Reserva':
                $sql = "SELECT 
                nombre as Nombre, hotelera as Compañia, pais as Pais, ciudad as Ciudad, ubicacion as Ubicacion , estrellas as Estrellas, nuemero_habitaciones as Habitaciones, precio as Precio
                FROM `hotel`;";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                foreach ($datos as $key => $value) {

                    $editar = array("Reservar" => "<button type='button' onclick='reservar(this.id)' class='btn btn-sm btn-link edicion' id='{$value['Nombre']}' data-bs-toggle='modal' data-bs-target='#modal_Reservar'><img src='../lib/feather/shopping-cart.svg'></button>");

                    $datos[$key] = array_merge($editar, $value);
                }

                return $response = procesar_Datatable($datos);

                break;
            case 'hoteles_hotelera':
                $hotelera = $_SESSION["usuario"]["nombre"];

                $sql = "SELECT 
                nombre as Nombre, hotelera as Compañia, pais as Pais, ciudad as Ciudad, ubicacion as Ubicacion , estrellas as Estrellas, nuemero_habitaciones as Habitaciones, precio as Precio
                FROM `hotel`
                Where hotelera = '{$hotelera}'";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                foreach ($datos as $key => $value) {

                    $editar = array("EDITAR" => "<button type='button' onclick='editar(this.id,0)' class='btn btn-sm btn-link edicion' id='{$value['Nombre']}' data-bs-toggle='modal' data-bs-target='#modal_editar'><img src='../lib/feather/edit.svg'></button>");
                    $eliminar = array("ELIMINAR" => "<button type='button' onclick='eliminar(this.id,0)' class='btn btn-sm btn-link edicion' id='{$value['Nombre']}'><img src='../lib/feather/trash-2.svg'></button>");

                    $datos[$key] = array_merge($editar, $eliminar, $value);
                }

                return $response = procesar_Datatable($datos);
                break;
            case 'reservas_hotel':
                $compañia = $_SESSION["usuario"]["nombre"];

                $sql = "SELECT nombre FROM `hotel` WHERE hotelera = '{$compañia}';";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                foreach ($datos as $key => $value) {

                    $sql = "SELECT 
                    numero_de_la_reserva as ID, nombre_cliente as Cliente, dni_cliente as 'Dni', nombre_hotel as Hotel, numero_habitacion as Reservas, precio as Precio ,fecha_entrada as Entrada, fecha_salida as Salida, pagada as Pagada
                    FROM reserva_habitacion
                    Where nombre_hotel = '{$value["nombre"]}'";

                    $result = $this->mysqli->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                }

                return $response = procesar_Datatable($data);
                break;
            case 'hoteles_Admin':
                $sql = "SELECT 
                nombre as Nombre, hotelera as Compañia, pais as Pais, ciudad as Ciudad, ubicacion as Ubicacion , estrellas as Estrellas, nuemero_habitaciones as Habitaciones, precio as Precio
                FROM `hotel`";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                foreach ($datos as $key => $value) {

                    $editar = array("EDITAR" => "<button type='button' onclick='editar(this.id,2)' class='btn btn-sm btn-link edicion' id='{$value['Nombre']}' data-bs-toggle='modal' data-bs-target='#modal_editar'><img src='../lib/feather/edit.svg'></button>");
                    $eliminar = array("ELIMINAR" => "<button type='button' onclick='eliminar(this.id,0)' class='btn btn-sm btn-link edicion' id='{$value['Nombre']}'><img src='../lib/feather/trash-2.svg'></button>");

                    $datos[$key] = array_merge($editar, $eliminar, $value);
                }

                return $response = procesar_Datatable($datos);
                break;
            case 'vuelos_Reservar':
                $sql = "SELECT 
                aerolinea as Aerolinea, salida_pais as 'Salida Pais', salida_ciudad as 'Salida Ciudad', destino_pais as 'Destino Pais', destino_ciudad as 'Destino Ciudad' , fecha_salida as Salida, fecha_llegada as LLegada, precio as Precio, precio_primera_clase as 'Primera Clase Precio', Precio_kg_maleta as 'Precio Maleta (Kg)', matricula as 'Vuelo', numero_de_plazas_normales as Plazas, numero_de_plazas_primera_clase as 'Primera Clase', ROUND((peso_max_maletas / (numero_de_plazas_normales + numero_de_plazas_primera_clase)),0) as 'Kg Maximo' 
                FROM `vuelos`";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                foreach ($datos as $key => $value) {

                    $editar = array("Reservar" => "<button type='button' onclick='reservar(this.id)' class='btn btn-sm btn-link edicion' id='{$value['Vuelo']}' data-bs-toggle='modal' data-bs-target='#modal_Reservar'><img src='../lib/feather/shopping-cart.svg'></button>");

                    $datos[$key] = array_merge($editar, $value);
                }

                return $response = procesar_Datatable($datos);

                break;
            case 'reservas_Admin':

                $sql = "SELECT 
                    numero_de_la_reserva as ID, nombre_cliente as Cliente, dni_cliente as 'Dni Cliente', nombre_hotel as Hotel, numero_habitacion as 'Habitaciones Reservadas', precio as Precio ,fecha_entrada as LLegada, fecha_salida as Salida, pagada as Pagada
                    FROM reserva_habitacion;";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                if (isset($datos)) {
                    foreach ($datos as $key => $value) {

                        $editar = array("PAGAR" => "<button type='button' onclick='pagar(this.id)' class='btn btn-sm btn-link edicion' id='{$value['ID']}'><img src='../lib/feather/credit-card.svg'></button>");
                        $eliminar = array("ELIMINAR" => "<button type='button' onclick='eliminar(this.id,2)' class='btn btn-sm btn-link edicion' id='{$value['ID']}'><img src='../lib/feather/trash-2.svg'></button>");

                        $datos[$key] = array_merge($editar, $eliminar, $value);
                    }

                    return $response = procesar_Datatable($datos);
                }

                return $response = $response = array(
                    "status" => "Fail",
                    "msg" => "Fallo en la base de datos"
                );;
                break;
        }
    }

    function reservar($cliente, $dni, $hotel, $numeroHabitaciones, $precio, $entrada, $salida)
    {
        $fecha = explode(" ", $entrada);
        $fecha = $fecha[0];
        $precio = $precio * $numeroHabitaciones;

        $sql = "SELECT SUM(numero_habitacion) as 'Reservas' FROM `reserva_habitacion` WHERE nombre_hotel = '{$hotel}' AND fecha_entrada >= '{$fecha}';";

        $result = $this->mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        $sql = "SELECT nuemero_habitaciones as 'Habitaciones' FROM `hotel` WHERE nombre = '{$hotel}';";

        $result = $this->mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        $reservas = $datos[0]["Reservas"] + $numeroHabitaciones;
        if ($reservas < $datos[1]["Habitaciones"]) {
            $sql = "INSERT INTO reserva_habitacion
                      VALUES ('','{$cliente}', '{$dni}','{$hotel}',{$numeroHabitaciones},{$precio},'{$salida}','{$entrada}', 'No');";

            $result = $this->mysqli->query($sql);


            if ($result) {
                $response = array(
                    "status" => "success",
                    "msg" => "Reservado con exito"
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
            $response = array(
                "status" => "Fail",
                "msg" => "Fallo No Quedan Habitaciones"
            );
            return $response;
        }
    }

    function delete($tipo, $id1)
    {
        switch ($tipo) {
            case 0:
                $sql = "DELETE FROM hotel WHERE nombre = '{$id1}';";

                $result = $this->mysqli->query($sql);

                if ($result) {
                    $response = array(
                        "status" => "success",
                        "msg" => "Eliminado con exito"
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
                $sql = "DELETE FROM reserva_habitacion WHERE numero_de_la_reserva = {$id1};";

                $result = $this->mysqli->query($sql);

                if ($result) {
                    $response = array(
                        "status" => "success",
                        "msg" => "Eliminado con exito"
                    );
                    return $response;
                } else {
                    $response = array(
                        "status" => "Fail",
                        "msg" => "Fallo en la base de datos"
                    );
                }
                break;
        }
    }

    function editar($nombre, $compañia, $pais, $ciudad, $ubicacion, $estrellas, $habitaciones, $precio)
    {

        $sql = "UPDATE hotel
                SET hotelera = '{$compañia}', pais = '{$pais}', ciudad = '{$ciudad}', ubicacion = '{$ubicacion}', estrellas = {$estrellas}, nuemero_habitaciones = {$habitaciones}, precio = {$precio}
                WHERE
                nombre = '{$nombre}';";



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


        return $response;
    }

    function añadir($nombre, $compañia, $pais, $ciudad, $ubicacion, $estrellas, $habitaciones, $precio)
    {

        $sql = "SELECT
        * 
        FROM
        hotel
        WHERE
        nombre = '{$nombre}';";

        $result = $this->mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        if (!isset($datos)) {
            $sql = "INSERT INTO hotel
            VALUES ('{$nombre}','{$compañia}', '{$pais}','{$ciudad}','{$ubicacion}',{$estrellas},{$habitaciones},{$precio});";

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
        } else {
            $response = array(
                "status" => "Fail",
                "msg" => "Ese nombre ya ha sido creado"
            );
            return $response;
        }
    }

    function pagar($id)
    {
        $sql = "UPDATE reserva_habitacion 
        SET pagada = 'Si'
        WHERE
        numero_de_la_reserva = {$id};";



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
}
