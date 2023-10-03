<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cotecnova1";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

function http_get($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function http_post($url, $data) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function http_put($url, $data) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function http_delete($url, $data) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM clientes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    } else {
        echo "No se encontraron registros.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $nombre = $data['nombre'];
    $apellido = $data['apellido'];
    $direccion = $data['direccion'];

    $sql = "INSERT INTO clientes (nombre, apellido, direccion) VALUES ('$nombre', '$apellido', '$direccion')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro creado con éxito.";
    } else {
        echo "Error al crear el registro: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        $id = $data['id'];
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $direccion = $data['direccion'];

        $sql = "UPDATE clientes SET nombre='$nombre', apellido='$apellido', direccion='$direccion' WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "Registro actualizado con éxito.";
        } else {
            echo "Error al actualizar el registro: " . $conn->error;
        }
    } else {
        echo "ID no proporcionado en la solicitud.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        $id = $data['id'];

        $sql = "DELETE FROM clientes WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "Registro eliminado con éxito.";
        } else {
            echo "Error al eliminar el registro: " . $conn->error;
        }
    } else {
        echo "ID no proporcionado en la solicitud.";
    }
}

$conn->close();
?>



