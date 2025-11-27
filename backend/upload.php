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
            $resultat = $importer->import($desti, 'http://localhost:3002/productes');

            // Mostrar resum
            echo "<h2>Resum de la importació:</h2>";
            echo "<p><strong>Files processades (excl. capçalera):</strong> " . $resultat['llegits'] . "</p>";
            echo "<p><strong>Productes nous afegits:</strong> " . count($resultat['productes']) . "</p>";
            echo "<p><strong>Productes duplicats (ja existien):</strong> " . $resultat['duplicats'] . "</p>";
            echo "<p><strong>Files ignorades (dades invàlides):</strong> " . $resultat['ignorades'] . "</p>";
            echo "<p><strong>Productes enviats a json-server:</strong> " . $resultat['enviats'] . "</p>";
            echo "<p><strong>Errors d'enviament a json-server:</strong> " . $resultat['errorsCurl'] . "</p>";
            echo "<p><strong>Errors crítics (fitxer incorrecte, etc.):</strong> " . $resultat['errors'] . "</p>";
            echo "<p><strong>Còpia de seguretat:</strong> ../data/products.json.backup</p>";
            echo "<p><strong>Registre detallat:</strong> ../backend/logs/import.log</p>";

        } else {
            echo "Error: no s'ha pogut moure el fitxer.";
        }
    } else {
        echo "Error: no s'ha pujat cap fitxer o ha hagut un error.";
    }
} else {
    // Si no és POST, mostra missatge o redirigeix
    echo "<h2>Error: Aquesta pàgina només accepta dades des del formulari.</h2>";
    echo "<p>Si us plau, visita <a href='../frontend/html/upload.html'>el formulari</a> per pujar un fitxer.</p>";
}
?>