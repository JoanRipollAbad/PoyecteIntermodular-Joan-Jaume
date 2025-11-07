    <?php
    session_start();
    require_once '../includes/json_connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuari = trim($_POST['usuari'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $contrasenya = $_POST['contrasenya'] ?? '';

        // Validació bàsica
        if (empty($usuari) || empty($email) || empty($contrasenya)) {
            $error = 'Tots els camps són obligatoris.';
        } elseif (strlen($contrasenya) < 6) {
            $error = 'La contrasenya ha de tenir almenys 6 caràcters.';
        } else {
            // Comprovar si l'usuari ja existeix
            $client = new JsonConnect();
            $usuaris = $client->get('users') ?: [];
            $usuariExistent = array_filter($usuaris, fn($u) => $u['usuari'] === $usuari || $u['email'] === $email);

            if (!empty($usuariExistent)) {
                $error = 'L’usuari o l’email ja existeix.';
            } else {
                // Crear nou usuari
                $nouUsuari = [
                    'usuari' => $usuari,
                    'email' => $email,
                    'contrasenya' => password_hash($contrasenya, PASSWORD_DEFAULT),
                    'rol' => 'usuari',
                    'creat_a' => date('Y-m-d H:i:s')
                ];

                $resultat = $client->post('users', $nouUsuari);
                if ($resultat) {
                    $success = 'Usuari registrat correctament.';
                } else {
                    $error = 'Error al registrar l’usuari. Detalls: ' . json_encode($nouUsuari);
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
        <label for="usuari">Usuari:</label>
        <input type="text" name="usuari" id="usuari" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="contrasenya">Contrasenya:</label>
        <input type="password" name="contrasenya" id="contrasenya" required><br><br>

        <button type="submit">Registrar</button>
        </form>
    <?php endif; ?>
    </body>
    </html>