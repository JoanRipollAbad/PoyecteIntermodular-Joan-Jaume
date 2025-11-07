    <?php
    session_start();
    require_once '../includes/json_connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuari = trim($_POST['usuari'] ?? '');
        $contrasenya = $_POST['contrasenya'] ?? '';

        if (empty($usuari) || empty($contrasenya)) {
            $error = 'Usuari i contrasenya són obligatoris.';
        } else {
            $client = new JsonConnect();
            $usuaris = $client->get('users') ?: [];
            $usuariTrobat = null;

            foreach ($usuaris as $u) {
                if ($u['usuari'] === $usuari) {
                    $usuariTrobat = $u;
                    break;
                }
            }

            if ($usuariTrobat && password_verify($contrasenya, $usuariTrobat['contrasenya'])) {
                $_SESSION['usuari_id'] = $usuariTrobat['id'];
                $_SESSION['usuari'] = $usuariTrobat['usuari'];
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
        <label for="usuari">Usuari:</label>
        <input type="text" name="usuari" id="usuari" required><br><br>

        <label for="contrasenya">Contrasenya:</label>
        <input type="password" name="contrasenya" id="contrasenya" required><br><br>

        <button type="submit">Iniciar sessió</button>
    </form>

    <p><a href="register.php">Encara no tens compte? Registra’t</a></p>
    </body>
    </html>