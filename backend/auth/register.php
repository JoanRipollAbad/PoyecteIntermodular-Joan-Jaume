<?php
session_start();
require_once '../includes/json_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_usuari = trim($_POST['nom_usuari'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';

    // Validació bàsica
    if (empty($nom_usuari) || empty($email) || empty($contrasenya)) {
        $error = 'Tots els camps són obligatoris.';
    } elseif (strlen($contrasenya) < 6) {
        $error = 'La contrasenya ha de tenir almenys 6 caràcters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format d\'email invàlid.';
    } else {
        $client = new JsonConnect();
        
        // Descarregar tots els usuaris per comprovar duplicats
        $usuaris = $client->get('usuaris') ?: [];
        $usuariExistent = null;

        foreach ($usuaris as $u) {
            if ($u['nom_usuari'] === $nom_usuari || $u['email'] === $email) {
                $usuariExistent = $u;
                break;
            }
        }

        if ($usuariExistent) {
            $error = 'El nom d\'usuari o l\'email ja existeix.';
        } else {
            // Generar nou ID
            $maxId = 0;
            foreach ($usuaris as $u) {
                if (isset($u['id']) && $u['id'] > $maxId) {
                    $maxId = $u['id'];
                }
            }
            $nouId = $maxId + 1;

            // Crear nou usuari
            $nouUsuari = [
                'id' => $nouId,
                'nom_usuari' => $nom_usuari,
                'contrasenya' => password_hash($contrasenya, PASSWORD_DEFAULT),
                'email' => $email,
                'data_registre' => date('c'),
                'rol' => 'usuari'
            ];

            $resultat = $client->post('usuaris', $nouUsuari);
            if ($resultat) {
                $success = 'Usuari registrat correctament.';
            } else {
                $error = 'Error al connectar amb el servidor. Comprova que JSON Server estigui engegat.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Registre d'usuari</title>
</head>
<body>
    <h1>Registre d'usuari</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <a href="login.php">Inicia sessió</a>
    <?php else: ?>
        <form method="POST">
            <label for="nom_usuari">Nom d'usuari:</label>
            <input type="text" name="nom_usuari" id="nom_usuari" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br><br>

            <label for="contrasenya">Contrasenya:</label>
            <input type="password" name="contrasenya" id="contrasenya" required><br><br>

            <button type="submit">Registrar</button>
        </form>

         <p><a href="login.php">Ya tens sessió? Inicia sessió</a></p>
    <?php endif; ?>
</body>
</html>