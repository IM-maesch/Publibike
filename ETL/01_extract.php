<?php

// Inkludieren der Datenbankkonfiguration
require_once '../config.php';

try {
    // Verbindung zur Datenbank herstellen
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);

    // Daten von der Publibike-API abrufen
    $url = 'https://api.publibike.ch/v1/public/partner/stations';
    $data = json_decode(file_get_contents($url), true);

    if (!$data || !isset($data['stations'])) {
        die("Fehler beim Abrufen der Daten von der Publibike-API.");
    }

    // Liste der Standort-IDs nach Größe sortiert
    $location_ids = [105, 217, 233, 353, 506, 513, 872, 873];

    // Durch die Standorte iterieren und nach Standort-IDs filtern
    foreach ($data['stations'] as $location) {
        if (in_array($location['id'], $location_ids)) {
            // Fahrzeuge für den aktuellen Standort abrufen
            $vehicles = isset($location['vehicles']) ? $location['vehicles'] : [];

            // Fahrzeugdaten in die Datenbank einfügen
            foreach ($vehicles as $vehicle) {
                $bike_id = isset($vehicle['id']) ? $vehicle['id'] : null;
                $standort_id = $location['id'];

                if ($bike_id) {
                    // SQL-Anweisung vorbereiten
                    $stmt = $pdo->prepare("INSERT INTO publibike_api_lesen (bike_id, standort_id) VALUES (:bike_id, :standort_id)");

                    // Parameter binden und die Anweisung ausführen
                    $stmt->bindParam(':bike_id', $bike_id);
                    $stmt->bindParam(':standort_id', $standort_id);

                    $stmt->execute();
                }
            }
        }
    }

    echo "Extrakt-Phase erfolgreich abgeschlossen.";

} catch (PDOException $e) {
    die("Fehler bei der Verbindung zur Datenbank: " . $e->getMessage());
}

?>
