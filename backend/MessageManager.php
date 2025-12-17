<?php

class MessageManager
{
    private string $dataDir;
    private string $jsonFile;
    private string $logFile;

    public function __construct(string $dataDir = '../data/')
    {
        // Convertir a ruta absoluta desde el directorio actual (backend)
        $this->dataDir = realpath(__DIR__ . '/' . $dataDir) . '/';

        if ($this->dataDir === false) {
            throw new RuntimeException("Carpeta de datos no encontrada: " . $dataDir);
        }

        $this->jsonFile = $this->dataDir . 'mensajes.json';
        $this->logFile = __DIR__ . '/../logs/contact.log'; // logs en PI/logs/

        // Crear carpetas si no existen
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }
        if (!is_dir(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
    }

    /**
     * Guarda un nuevo mensaje de contacto en el archivo JSON.
     *
     * @param array $datos ['nombre', 'email', 'asunto', 'mensaje']
     * @return array Resultado con éxito/errores
     */
    public function saveMessage(array $datos): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'errors' => [],
        ];

        // Validar campos obligatorios
        $campos = ['nombre', 'email', 'asunto', 'mensaje'];
        foreach ($campos as $campo) {
            if (empty($datos[$campo])) {
                $result['errors'][] = "El campo '$campo' es obligatorio.";
            }
        }

        // Validar email
        if (!empty($datos['email']) && !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $result['errors'][] = "El email no es válido.";
        }

        if (!empty($result['errors'])) {
            $errorMsg = implode(' ', $result['errors']);
            $this->log("Error de validación: $errorMsg");
            $result['message'] = '!!! ' . $errorMsg;
            return $result;
        }

        // Preparar datos limpios
        $mensaje = [
            'nombre' => trim(htmlspecialchars($datos['nombre'])),
            'email' => trim($datos['email']),
            'asunto' => trim(htmlspecialchars($datos['asunto'])),
            'mensaje' => trim(htmlspecialchars($datos['mensaje'])),
            'fecha' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'desconegida',
        ];

        // Leer mensajes existentes
        $mensajesExistentes = [];
        if (file_exists($this->jsonFile)) {
            $contenido = file_get_contents($this->jsonFile);
            $datosJson = json_decode($contenido, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $mensajesExistentes = $datosJson['mensajes'] ?? [];
            } else {
                $this->log("JSON corrupto en {$this->jsonFile}. Se reiniciará.");
            }
        }

        // Añadir nuevo mensaje
        $mensajesExistentes[] = $mensaje;

        // Guardar
        $datosParaGuardar = ['mensajes' => $mensajesExistentes];
        $json = json_encode($datosParaGuardar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if (file_put_contents($this->jsonFile, $json) === false) {
            $error = "No se pudo escribir en {$this->jsonFile}";
            $this->log($error);
            $result['message'] = 'Error al guardar el mensaje. Verifica los permisos.';
            return $result;
        }

        $this->log("Nuevo mensaje guardado de: {$mensaje['email']}");
        $result['success'] = true;
        $result['message'] = 'Mensaje guardado con éxito.';
        return $result;
    }

    /**
     * Obtiene todos los mensajes guardados.
     */
    public function getMessages(): array
    {
        if (!file_exists($this->jsonFile)) {
            return [];
        }

        $contenido = file_get_contents($this->jsonFile);
        $datos = json_decode($contenido, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->log("Error al leer JSON: " . json_last_error_msg());
            return [];
        }

        return $datos['mensajes'] ?? [];
    }

    /**
     * Registra un mensaje en el log.
     */
    private function log(string $mensaje): void
    {
        $fecha = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconegida';
        $contenido = "[$fecha] [$ip] $mensaje" . PHP_EOL;
        file_put_contents($this->logFile, $contenido, FILE_APPEND);
    }
}