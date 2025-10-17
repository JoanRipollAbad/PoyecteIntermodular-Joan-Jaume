<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];

    //Nombre
    $nombre = trim($_POST['nombre'] ?? '');
    if (empty($nombre)) {
        $errors[] = 'EL nombre es obligatorio.';
    }

    //Correo
    $correo = trim($_POST['correo'] ?? '');
    if (empty($correo)) {
        $errors[] = 'El correo es obligatorio.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) { //FILTER_VALIDATE_EMAIL función por defecto de PHP
        $errors[] = "El formato del correo no es válido.";
    }
    
    //Ciclo formativo
    $ciclos_validos = ['DAW', 'DAM', 'ASIX'];
    $ciclo = $_POST['ciclo'] ?? '';
    if (empty($cicle) || !in_array($ciclo, $ciclos_validos)) {
        $errors[] = "Debes seleccionar un ciclo formativo válido.";
    }

    //Teléfono
    $telefono = trim($_POST['telefono'] ?? '');
    if (empty($telefono)) {
        $errors[] = "El teléfono es obligatorio.";
    } elseif (!preg_match('/^[0-9\s\-\+()]{9,15}$/', $telefono)) {
        $errors[] = "El teléfono no tiene un formato válido.";
    }

    //Consentimiento
    if (!isset($_POST['consentimento']) || $_POST['consentimento'] !== '1') {
        $errors[] = "Debes aceptar el consentimiento sobre datos.";
    }

    //Si no hay errores, muestra el mensaje de éxtio
    if (empty($errors)) {
        echo "<h2>Formulario enviado correctamente!</h2>";
        echo "<p>Grácias, <strong>" . htmlspecialchars($nombre) . "</strong>.</p>";
    } else {
        echo "<h2>Hay errores en el formulario:</h2>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo '<a href="formulario.html">Volver al formulario</a>';
    }

} else {
    header("Location: formulario.html");
    exit();
}
?>