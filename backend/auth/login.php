<?php
session_start();
require_once '../includes/json_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_usuari = trim($_POST['nom_usuari'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';

    if (empty($nom_usuari) || empty($contrasenya)) {
        $error = 'Usuari i contrasenya són obligatoris.';
    } else {
        $client = new JsonConnect();
        
        // Consulta eficient per nom d'usuari (no carregar tots els usuaris)
        $usuaris = $client->get('usuaris', ['nom_usuari' => $nom_usuari]);
        $usuariTrobat = $usuaris[0] ?? null;

        if ($usuariTrobat && password_verify($contrasenya, $usuariTrobat['contrasenya'])) {
            // Credencials correctes
            session_regenerate_id(true); // Seguretat: regenerar ID de sessió
            
            // Emmagatzemar dades a la sessió
            $_SESSION['user_id'] = $usuariTrobat['id'];
            $_SESSION['user_nom'] = $usuariTrobat['nom'];
            $_SESSION['user_rol'] = $usuariTrobat['rol'] ?? 'usuari';
            
            // Crear cookie d'identificació (com diu l'enunciat)
            setcookie('user_id', $usuariTrobat['id'], time() + 3600, "/");

            header('Location: profile.php');
            exit;
        } else {
            $error = 'Credencials incorrectes.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Inici de sessió</title>
</head>
<body>
    <h1>Inici de sessió</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nom_usuari">Nom d'usuari:</label>
        <input type="text" name="nom_usuari" id="nom_usuari" required><br><br>

        <label for="contrasenya">Contrasenya:</label>
        <input type="password" name="contrasenya" id="contrasenya" required><br><br>

        <button type="submit">Iniciar sessió</button>
    </form>

    <p><a href="register.php">Encara no tens compte? Registra’t</a></p>
</body>
</html>