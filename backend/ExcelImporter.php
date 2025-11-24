<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelImporter
{
    private $dataDir;
    private $uploadDir;
    private $jsonFile;
    private $logFile;

    public function __construct($uploadDir = '../uploads/', $dataDir = '../data/')
    {
        $this->uploadDir = $uploadDir;
        $this->dataDir = $dataDir;
        $this->jsonFile = $dataDir . 'products.json';
        $this->logFile = __DIR__ . '/logs/import.log';

        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }
        if (!is_dir(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
    }

    public function import($fitxer, $jsonServerUrl = 'http://localhost:3003/productes')
    {
        $resultat = [
            'errors' => 0,
            'ignorades' => 0,        
            'duplicats' => 0,       
            'llegits' => 0,          
            'enviats' => 0,
            'errorsCurl' => 0,
            'productes' => [],
        ];

        // Fer còpia de seguretat
        $backupPath = $this->dataDir . 'products.json.backup';
        if (file_exists($this->jsonFile)) {
            copy($this->jsonFile, $backupPath);
        }

        $extensio = strtolower(pathinfo($fitxer, PATHINFO_EXTENSION));
        $lector = IOFactory::createReader(ucfirst($extensio));

        try {
            $spreadsheet = $lector->load($fitxer);
            $worksheet = $spreadsheet->getActiveSheet();
            $files = iterator_to_array($worksheet->getRowIterator());

            if (count($files) < 1) {
                $this->log("No hi ha cap fila al fitxer '$fitxer'.");
                $resultat['errors']++;
                return $resultat;
            }

            // Obtenir capçalera
            $capcelera = [];
            $fila1 = $files[1];
            $cellIterator = $fila1->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $cell) {
                $capcelera[] = trim($cell->getValue() ?? '');
            }

            // Validar columnes
            $columnesEsperades = ['id', 'sku', 'nom', 'descripcio', 'img', 'preu', 'estoc'];
            $columnesFalten = array_diff($columnesEsperades, $capcelera);

            if (!empty($columnesFalten)) {
                $this->log("Falten columnes: " . implode(', ', $columnesFalten));
                $resultat['errors']++;
                return $resultat;
            }

            $index = array_flip($capcelera);

            foreach ($files as $i => $row) {
                if ($i === 1) continue; // Saltar capçalera

                $resultat['llegits']++;

                $fila = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $j = 0;
                foreach ($cellIterator as $cell) {
                    $clau = $capcelera[$j] ?? "col$j";
                    $valor = $cell ? $cell->getValue() : null;
                    $fila[$clau] = $valor ?? ''; // converteix null a cadena buida
                    $j++;
                }

                $preu = $fila['preu'] ?? null;
                $estoc = $fila['estoc'] ?? null;

                if (!is_numeric($preu) || !is_numeric($estoc)) {
                    $resultat['ignorades']++;
                    continue;
                }

                $producte = [
                    'id' => (int)$fila['id'],
                    'sku' => $fila['sku'],
                    'nom' => $fila['nom'],
                    'descripcio' => $fila['descripcio'],
                    'img' => $fila['img'],
                    'preu' => (float)$preu,
                    'estoc' => (int)$estoc,
                    'importat_a' => date('Y-m-d H:i:s'),
                    'usuari' => $_SERVER['REMOTE_ADDR'] ?? 'desconegut'
                ];

                $resultat['productes'][] = $producte;
            }

            // Evitar duplicats
            $dadesActuals = [];
            if (file_exists($this->jsonFile)) {
                $dadesActuals = json_decode(file_get_contents($this->jsonFile), true);
                $dadesActuals = $dadesActuals['productes'] ?? [];
            }

            $skuMap = [];
            foreach ($dadesActuals as $p) {
                $skuMap[$p['sku']] = true;
            }

            $productesUnics = [];
            foreach ($resultat['productes'] as $p) {
                if (isset($skuMap[$p['sku']])) {
                    $this->log("Producte duplicat ignorat: " . $p['sku']);
                    $resultat['duplicats']++;
                    continue;
                }
                $skuMap[$p['sku']] = true;
                $productesUnics[] = $p;
            }

            $resultat['productes'] = $productesUnics;

            // Guardar JSON
            $dades = ['productes' => $resultat['productes']];
            file_put_contents($this->jsonFile, json_encode($dades, JSON_PRETTY_PRINT));

            // Enviar a json-server
            foreach ($resultat['productes'] as $producte) {
                $data = json_encode($producte);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $jsonServerUrl);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode !== 201) {
                    $this->log("Error enviant producte a json-server: " . $producte['nom']);
                    $resultat['errorsCurl']++;
                } else {
                    $resultat['enviats']++;
                }
            }

        } catch (Exception $e) {
            $this->log("Error llegint fitxer: " . $e->getMessage());
            $resultat['errors']++;
        }

        return $resultat;
    }

    private function log($missatge)
    {
        $data = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconegut';
        file_put_contents($this->logFile, "[$data] [$ip] $missatge\n", FILE_APPEND);
    }
}