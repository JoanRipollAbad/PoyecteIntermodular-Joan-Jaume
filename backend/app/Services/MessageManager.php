<?php

namespace App\Services;

use RuntimeException;

class MessageManager
{
    private string $jsonFile;
    private string $logFile;

    public function __construct()
    {
        $dataDir = storage_path('app/data/');
        $logDir = storage_path('logs/');

        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }

        $this->jsonFile = $dataDir . 'mensajes.json';
        $this->logFile = $logDir . 'contact.log';
    }

    public function saveMessage(array $datos): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'errors' => [],
        ];

        $campos = ['nombre', 'email', 'asunto', 'mensaje'];
        foreach ($campos as $campo) {
            if (empty($datos[$campo])) {
                $result['errors'][] = "El campo '$campo' es obligatorio.";
            }
        }

        if (!empty($datos['email']) && !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $result['errors'][] = "El email no es válido.";
        }

        if (!empty($result['errors'])) {
            $errorMsg = implode(' ', $result['errors']);
            $this->log("Error de validación: $errorMsg");
            $result['message'] = '!!! ' . $errorMsg;
            return $result;
        }

        $mensaje = [
            'nombre' => trim(htmlspecialchars($datos['nombre'])),
            'email' => trim($datos['email']),
            'asunto' => trim(htmlspecialchars($datos['asunto'])),
            'mensaje' => trim(htmlspecialchars($datos['mensaje'])),
            'fecha' => date('Y-m-d H:i:s'),
            'ip' => request()->ip(),
        ];

        // Leer mensajes existentes
        $mensajesExistentes = [];
        if (file_exists($this->jsonFile)) {
            $contenido = file_get_contents($this->jsonFile);
            $datosJson = json_decode($contenido, true);
            $mensajesExistentes = $datosJson['mensajes'] ?? [];
        }

        $mensajesExistentes[] = $mensaje;

        // Guardar
        $json = json_encode(['mensajes' => $mensajesExistentes], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if (file_put_contents($this->jsonFile, $json) === false) {
            $this->log("Error al escribir en {$this->jsonFile}");
            $result['message'] = 'Error al guardar el mensaje.';
            return $result;
        }

        $this->log("Nuevo mensaje guardado de: {$mensaje['email']}");
        $result['success'] = true;
        $result['message'] = 'Mensaje guardado con éxito.';
        return $result;
    }

    private function log(string $mensaje): void
    {
        $fecha = date('Y-m-d H:i:s');
        $ip = request()->ip();
        $contenido = "[$fecha] [$ip] $mensaje" . PHP_EOL;
        file_put_contents($this->logFile, $contenido, FILE_APPEND);
    }
}