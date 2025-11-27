<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Para ver errores en pantalla (solo desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1); // Muestra errores en la respuesta

try {
    require_once __DIR__ . '/MessageManager.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    $manager = new MessageManager('../data/');
    $result = $manager->saveMessage($input);

    http_response_code($result['success'] ? 200 : 400);
    echo json_encode($result);

} catch (Throwable $e) {
    // Devuelve el error real en lugar de 500
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '❌ Error interno: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>