<?php
session_start();
require_once '../includes/json_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$client = new JsonConnect();
$usuari_id = $_SESSION['user_id'];
$usuaris = $client->get("usuaris", ['id' => $usuari_id]);
$usuari = $usuaris[0] ?? null;

if (!$usuari) {
    session_destroy();
    setcookie('user_id', '', time() - 3600, "/");
    header('Location: login.php?error=user_not_found');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Perfil d'usuari</title>
</head>
<body>
    <h1>Perfil d'usuari</h1>
    
    <div>
        <h2>Hola, <?= htmlspecialchars($usuari['nom_usuari']) ?>!</h2>
        <p><strong>Nom:</strong> <?= htmlspecialchars($usuari['nom'] ?? '') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($usuari['email']) ?></p>
        <p><strong>Registre:</strong> <?= date('d/m/Y', strtotime($usuari['data_registre'])) ?></p>
        <p><strong>Rol:</strong> <?= htmlspecialchars($usuari['rol'] ?? 'usuari') ?></p>
    </div>
    
    <div style="margin-top: 20px;">
        <a href="logout.php">Tancar sessió</a>
    </div>
</body>
</html>