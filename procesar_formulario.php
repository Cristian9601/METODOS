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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'create') {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $direccion = $_POST['direccion'];

        $sql = "INSERT INTO clientes (nombre, apellido, direccion) VALUES ('$nombre', '$apellido', '$direccion')";

        if ($conn->query($sql) === TRUE) {
            echo "Cliente creado con éxito. <a href='index.html'>Volver al CRUD</a>";
        } else {
            echo "Error al crear el cliente: " . $conn->error;
        }
    } elseif ($action === 'update') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $direccion = $_POST['direccion'];

        $sql = "UPDATE clientes SET nombre='$nombre', apellido='$apellido', direccion='$direccion' WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "Cliente actualizado con éxito. <a href='index.html'>Volver al CRUD</a>";
        } else {
            echo "Error al actualizar el cliente: " . $conn->error;
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'];

        $sql = "DELETE FROM clientes WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "Cliente eliminado con éxito. <a href='index.html'>Volver al CRUD</a>";
        } else {
            echo "Error al eliminar el cliente: " . $conn->error;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'read') {
    $sql = "SELECT * FROM clientes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        // Establece el tipo de contenido JSON en la respuesta
        header('Content-Type: application/json');

        // Devuelve los datos como JSON
        echo json_encode($rows);
    } else {
        echo "No se encontraron registros.";
    }
}

$conn->close();
?>

