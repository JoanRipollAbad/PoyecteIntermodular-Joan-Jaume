    <?php
    session_start();
    if (!isset($_SESSION['usuari'])) {
        header('Location: login.php');
        exit;
    }

    require_once '../includes/json_connect.php';

    $usuari = $_SESSION['usuari'];
    ?>

    <!DOCTYPE html>
    <html lang="ca">
    <head>
    <meta charset="UTF-8">
    <title>Perfil d'usuari</title>
    </head>
    <body>
    <h1>Benvingut, <?= htmlspecialchars($usuari) ?></h1>
    <p>Aquesta és la pàgina del teu perfil.</p>
    <a href="logout.php">Tancar sessió</a>
    </body>
    </html>