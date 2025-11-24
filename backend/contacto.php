<?php
session_start();

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitización
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email = trim($_POST['email'] ?? '');
    $subject = trim(htmlspecialchars($_POST['subject'] ?? ''));
    $message_body = trim(htmlspecialchars($_POST['message'] ?? ''));

    // Validación
    $errors = [];

    if (empty($name)) $errors[] = 'El nombre es obligatorio.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'El email es obligatorio y debe ser válido.';
    if (empty($subject)) $errors[] = 'El asunto es obligatorio.';
    if (empty($message_body)) $errors[] = 'El mensaje no puede estar vacío.';

    if (empty($errors)) {
        // Preparar correo
        $to = 'tu@email.com'; // 👈 cámbialo por tu dirección real
        $headers = [
            'From: ' . $email,
            'Reply-To: ' . $email,
            'Content-Type: text/plain; charset=UTF-8'
        ];

        $email_subject = "[Contacto] $subject";
        $email_body = "Nombre: $name\nEmail: $email\n\nMensaje:\n$message_body";

        // Enviar
        if (mail($to, $email_subject, $email_body, implode("\r\n", $headers))) {
            $success = true;
            $message = '✅ Mensaje enviado con éxito. Nos pondremos en contacto contigo pronto.';
            // Redirigir para evitar reenvío con F5
            $_SESSION['contact_success'] = $message;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $message = '❌ Hubo un error al enviar el mensaje. Inténtalo más tarde.';
        }
    } else {
        $message = '⚠️ ' . implode(' ', $errors);
    }
}

// Recuperar mensaje si redirigido
if (isset($_SESSION['contact_success'])) {
    $success = true;
    $message = $_SESSION['contact_success'];
    unset($_SESSION['contact_success']);
}
?>