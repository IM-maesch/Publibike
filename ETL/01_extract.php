<?php

// DB-Konfiguration
require_once '../config.php';

try {
    // Verbinden zur Datenbank
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}

// Liste der Standort-IDs
$location_ids = [105, 217, 233, 353, 506, 513, 872, 873];

// Fetch Daten aus der Publibike API
$url = 'https://api.publibike.ch/v1/public/partner/stations';
$data = json_decode(file_get_contents($url), true);

if (!$data || !isset($data['stations'])) {
    die("Failed to fetch data from the Publibike API.");
}

// Von den gewünschten Standorten alle Velo-IDs auslesen und eintragen
foreach ($data['stations'] as $location) {
    if (in_array($location['id'], $location_ids)) {
        // Fetch vehicles für den aktuellen Standort
        $vehicles = isset($location['vehicles']) ? $location['vehicles'] : [];

        // Vehicles Daten in die Tabelle einfügen
        foreach ($vehicles as $vehicle) {
            $bike_id = isset($vehicle['id']) ? $vehicle['id'] : null;
            $standort_id = $location['id'];

            if ($bike_id) {
                // SQL statement
                $stmt = $pdo->prepare("INSERT INTO publibike_api_lesen (bike_id, standort_id) VALUES (:bike_id, :standort_id)");

                // Bind parameters and execute the statement
                $stmt->bindParam(':bike_id', $bike_id);
                $stmt->bindParam(':standort_id', $standort_id);

                $stmt->execute();
            }
        }
    }
}

echo "Data insertion completed successfully.";


// Funktion aufrufen, um Daten zu erhalten und in die Datenbank einzufügen
fetchDataAndInsertIntoDatabase($pdo);

// Definiere das Enddatum als 1. Juli 2024
$end_date = strtotime('2024-07-01');

// Warte bis zum Enddatum, bevor das Skript nicht mehr ausgeführt wird
while (time() < $end_date) {
    // Warte 30 Minuten, bevor die Funktion erneut aufgerufen wird
    $interval = 30 * 60; // 30 Minuten in Sekunden
    sleep($interval);
    fetchDataAndInsertIntoDatabase($pdo);
}

?>
