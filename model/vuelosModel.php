<?php
require_once "connection.php";
require_once "utilidades.php";
session_start();



/**
 * @author SalvadorAsquit
 */

class Vuelos
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
            case 'vuelos':

                $sql = "SELECT 
                aerolinea as Aerolinea, salida_pais as 'Salida Pais', salida_ciudad as 'Salida Ciudad', destino_pais as 'Destino Pais', destino_ciudad as 'Destino Ciudad' , fecha_salida as Salida, fecha_llegada as LLegada, precio as Precio, precio_primera_clase as 'Primera Clase', Precio_kg_maleta as 'Precio Maleta (Kg)', matricula as 'Vuelo'
                FROM `vuelos`";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                return $response = procesar_Datatable($datos);

                break;

            case 'reservas':
                $usuario = $_SESSION["usuario"]["dni"];

                $sql = "SELECT 
                numero_de_la_reserva as Id, nombre_cliente as Nombre, dni_cliente as Dni, aerolinea as Aerolinea, matricula_avion as Vuelo , fecha_salida as Salida, fecha_llegada as LLegada, precio as Precio, numero_plazas_reservadas_normales as Reservas, numero_plazas_reservadas_primera_clase as 'Reservas 1º Clase', maletas as 'Maletas', pagado as Pagado
                FROM `reserva_vuelo`
                WHERE dni_cliente = '{$usuario}'";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
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
            case 'vuelos_Aerolinea':
                $aerolinea = $_SESSION["usuario"]["nombre"];

                $sql = "SELECT 
                matricula as Matricula, aerolinea as Aerolinea, salida_pais as 'Salida Pais', salida_ciudad as 'Salida Ciudad', destino_pais as 'Destino Pais', destino_ciudad as 'Destino Ciudad' , fecha_salida as Salida, fecha_llegada as LLegada, precio as Precio, precio_primera_clase as 'Primera Clase Precio', Precio_kg_maleta as 'Precio Maleta (Kg)', peso_max_maletas as 'Peso Maximo', numero_de_plazas_normales as Plazas, numero_de_plazas_primera_clase as 'Primera Clase'
                FROM `vuelos`
                Where aerolinea = '{$aerolinea}'";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                foreach ($datos as $key => $value) {

                    $editar = array("EDITAR" => "<button type='button' onclick='editar(this.id,0)' class='btn btn-sm btn-link edicion' id='{$value['Matricula']}+{$value['Salida']}' data-bs-toggle='modal' data-bs-target='#modal_editar'><img src='../lib/feather/edit.svg'></button>");
                    $eliminar = array("ELIMINAR" => "<button type='button' onclick='eliminar(this.id,0)' class='btn btn-sm btn-link edicion' id='{$value['Matricula']}+{$value['Salida']}'><img src='../lib/feather/trash-2.svg'></button>");

                    $datos[$key] = array_merge($editar, $eliminar, $value);
                }

                return $response = procesar_Datatable($datos);
                break;
            case 'reservas_Aerolinea':
                $aerolinea = $_SESSION["usuario"]["nombre"];

                $sql = "SELECT 
                numero_de_la_reserva as ID, nombre_cliente as Cliente, dni_cliente as 'Dni Cliente', aerolinea as Aerolinea, matricula_avion as Vuelo, fecha_salida as Salida ,fecha_llegada as LLegada, numero_plazas_reservadas_normales as Plazas, numero_plazas_reservadas_primera_clase as 'Primera Clase', maletas as Maletas, precio as Precio, pagado as Pagado
                FROM reserva_vuelo
                Where aerolinea = '{$aerolinea}'";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                return $response = procesar_Datatable($datos);
                break;
            case 'vuelos_Admin':
                $sql = "SELECT 
                aerolinea as Aerolinea, salida_pais as 'Salida Pais', salida_ciudad as 'Salida Ciudad', destino_pais as 'Destino Pais', destino_ciudad as 'Destino Ciudad' , fecha_salida as Salida, fecha_llegada as LLegada, precio as Precio, precio_primera_clase as 'Primera Clase', Precio_kg_maleta as 'Precio Maleta (Kg)', matricula as 'Vuelo', numero_de_plazas_normales as Plazas, numero_de_plazas_primera_clase as 'Plazas 1º Clase', peso_max_maletas as 'Peso Max'
                FROM `vuelos`";

                $result = $this->mysqli->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $datos[] = $row;
                }

                foreach ($datos as $key => $value) {

                    $editar = array("EDITAR" => "<button type='button' onclick='editar(this.id,1)' class='btn btn-sm btn-link edicion' id='{$value['Vuelo']}+{$value['Salida']}' data-bs-toggle='modal' data-bs-target='#modal_editar'><img src='../lib/feather/edit.svg'></button>");
                    $eliminar = array("ELIMINAR" => "<button type='button' onclick='eliminar(this.id,1)' class='btn btn-sm btn-link edicion' id='{$value['Vuelo']}+{$value['Salida']}'><img src='../lib/feather/trash-2.svg'></button>");

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
                    numero_de_la_reserva as ID, nombre_cliente as Cliente, dni_cliente as 'Dni Cliente', aerolinea as Aerolinea, matricula_avion as Vuelo, fecha_salida as Salida ,fecha_llegada as LLegada, numero_plazas_reservadas_normales as Plazas, numero_plazas_reservadas_primera_clase as 'Primera Clase', maletas as Maletas, precio as Precio, pagado as Pagado
                    FROM reserva_vuelo;";

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

    function reservar($matricula, $numBilletes, $numBilletesClase, $plazas, $plazasvips, $salida, $nombre, $dni, $aerolinea, $llegada, $maletas, $precio, $preciovip, $preciokg, $kgllevados)
    {
        $sql = "SELECT SUM(numero_plazas_reservadas_normales) as 'Plazas Normales', SUM(numero_plazas_reservadas_primera_clase) as 'Primera Clase' FROM `reserva_vuelo` WHERE matricula_avion = '{$matricula}' AND fecha_salida >= '{$salida}';";

        $result = $this->mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }


        $plazasRestantes = $plazas - $datos[0]["Plazas Normales"];
        $plazasRestantesvips = $plazasvips - $datos[0]["Primera Clase"];
        $total = ($precio * $numBilletes) + ($preciovip * $numBilletesClase) + ($preciokg * $kgllevados);

        if ($numBilletes < $plazasRestantes && $numBilletesClase < $plazasRestantesvips) {
            $sql = "INSERT INTO reserva_vuelo
                    VALUES ('','{$nombre}', '{$dni}','{$aerolinea}','{$matricula}','{$salida}','{$llegada}',{$numBilletes},{$numBilletesClase},{$maletas},{$total}, 'No');";

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
                "msg" => "Solo queda plazas normales: " . $plazasRestantes . " y Plazas Vips: " . $plazasRestantesvips
            );
            return $response;
        }
    }

    function delete($tipo, $id1, $id2)
    {
        switch ($tipo) {
            case 0:
                $sql = "DELETE FROM vuelos WHERE matricula = '{$id1}' and fecha_salida = '{$id2}';";

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
            case 1:
                $sql = "DELETE FROM vuelos WHERE matricula = '{$id1}' and fecha_salida = '{$id2}';";

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
                $sql = "DELETE FROM reserva_vuelo WHERE numero_de_la_reserva = {$id1};";

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

    function editar(
        $tipo,
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
    ) {
        switch ($tipo) {
            case 0:
                $sql = "UPDATE vuelos 
                SET salida_pais = '{$salidaPais}', salida_ciudad = '{$salidaCiudad}', destino_pais = '{$llegadaPais}', destino_ciudad = '{$llegadaCiudad}', fecha_llegada = '{$llegada}', precio = {$precio}, precio_primera_clase = {$precioVip}
                , precio_kg_maleta = {$precioMaleta}, peso_max_maletas = {$peso}, numero_de_plazas_normales = {$billete}, numero_de_plazas_primera_clase = {$billetesVips}
                WHERE
                matricula = '{$matricula}' and fecha_salida = '{$salida}'";



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
            case 1:
                $sql = "UPDATE vuelos 
                SET salida_pais = '{$salidaPais}', salida_ciudad = '{$salidaCiudad}', destino_pais = '{$llegadaPais}', destino_ciudad = '{$llegadaCiudad}', fecha_llegada = '{$llegada}', precio = {$precio}, precio_primera_clase = {$precioVip}
                , precio_kg_maleta = {$precioMaleta}, peso_max_maletas = {$peso}, numero_de_plazas_normales = {$billete}, numero_de_plazas_primera_clase = {$billetesVips}
                WHERE
                matricula = '{$matricula}' and fecha_salida = '{$salida}'";



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
        }
        return $response;
    }

    function añadir($matricula, $aerolinea, $salida_pais, $salida_ciudad, $destino_pais, $destino_ciudad, $fecha_salida, $fecha_llegada, $precio, $precio_primera_clase, $precio_kg_maleta, $peso_max_maletas, $numero_de_plazas_normales, $numero_de_plazas_primera_clase)
    {

        $sql = "SELECT
        * 
        FROM
        vuelos
        WHERE
        matricula = '{$matricula}' and fecha_salida = '{$fecha_salida}'";

        $result = $this->mysqli->query($sql);

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        if (!isset($datos)) {
            $sql = "INSERT INTO vuelos
            VALUES ('{$matricula}','{$aerolinea}', '{$salida_pais}','{$salida_ciudad}','{$destino_pais}','{$destino_ciudad}','{$fecha_salida}','{$fecha_llegada}',{$precio},{$precio_primera_clase},{$precio_kg_maleta},{$peso_max_maletas},{$numero_de_plazas_normales},{$numero_de_plazas_primera_clase});";

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
                "msg" => "Esa matricula con ese dia ya a sido creados "
            );
            return $response;
        }
    }

    function pagar($id)
    {
        $sql = "UPDATE reserva_vuelo 
        SET pagado = 'Si'
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
