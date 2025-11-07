<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carregar Composer
require_once '../vendor/autoload.php';
require_once 'ExcelImporter.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['fitxer']) && $_FILES['fitxer']['error'] === UPLOAD_ERR_OK) {
        $fitxer = $_FILES['fitxer'];
        $desti = '../uploads/' . basename($fitxer['name']);

        // Crear carpeta uploads si no existeix
        if (!is_dir('../uploads')) {
            mkdir('../uploads', 0755, true);
        }

        // Moure fitxer
        if (move_uploaded_file($fitxer['tmp_name'], $desti)) {
            // Instanciar ExcelImporter
            $importer = new ExcelImporter('../uploads/', '../data/');
            $resultat = $importer->import($desti, 'http://localhost:3001/productes');

            // Mostrar resum
            echo "<h2>Resum de la importació:</h2>";
            echo "<p>Productes llegits: " . count($resultat['productes']) . "</p>";
            echo "<p>Productes enviats a json-server: " . $resultat['enviats'] . "</p>";
            echo "<p>Errors d'enviament: " . $resultat['errorsCurl'] . "</p>";
            echo "<p>Files ignorades: " . $resultat['ignorades'] . "</p>";
            echo "<p>Errors: " . $resultat['errors'] . "</p>";
            echo "<p>Còpia de seguretat a: ../data/products.json.backup</p>";
            echo "<p>Registre d'errors a: ../backend/logs/import.log</p>";

        } else {
            echo "Error: no s'ha pogut moure el fitxer.";
        }
    } else {
        echo "Error: no s'ha pujat cap fitxer o ha hagut un error.";
    }
} else {
    // Si no és POST, mostra missatge o redirigeix
    echo "<h2>Error: Aquesta pàgina només accepta dades des del formulari.</h2>";
    echo "<p>Si us plau, visita <a href='../frontend/upload.html'>el formulari</a> per pujar un fitxer.</p>";
}
?>