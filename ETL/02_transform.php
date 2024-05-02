<?php

// Inkludieren der Datenbankkonfiguration
require_once '../config.php';


try {
    // Verbinde mit der Datenbank unter Verwendung der Konstanten aus config.php
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);

    // Schritt 0: Lösche Zeilen, die älter als zwei Stunden sind, aus der Tabelle publibike_api_lesen
    $two_hours_ago = date('Y-m-d H:i:s', strtotime('-2 hours'));
    $stmt = $pdo->prepare("DELETE FROM publibike_api_lesen WHERE current_time < :two_hours_ago");
    $stmt->execute(['two_hours_ago' => $two_hours_ago]);

    // Schritt 1: Holen Sie die Zeilen mit dem aktuellsten Zeitstempel aus der Tabelle publibike_api_lesen
    $stmt = $pdo->query("SELECT * FROM publibike_api_lesen WHERE current_time = (SELECT MAX(current_time) FROM publibike_api_lesen)");
    $recent_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Schritt 2: Holen Sie die Zeilen mit dem zweitaktuellsten Zeitstempel aus der Tabelle publibike_api_lesen
    $stmt = $pdo->query("SELECT * FROM publibike_api_lesen WHERE current_time = (SELECT DISTINCT current_time FROM publibike_api_lesen ORDER BY current_time DESC LIMIT 1, 1)");
    $second_recent_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Schritt 3: Vergleichen Sie die Listen und berechnen Sie die Unterschiede
    $bewegungsdaten = [];

    foreach ($recent_rows as $recent_row) {
        $standort_id = $recent_row['standort_id'];

        $aktuelle_bike_ids = array_column($recent_rows, 'bike_id');
        $vorherige_bike_ids = array_column($second_recent_rows, 'bike_id');

        $velo_plus = count(array_diff($aktuelle_bike_ids, $vorherige_bike_ids));
        $velo_minus = count(array_diff($vorherige_bike_ids, $aktuelle_bike_ids));


        // Berechnen Sie die Gesamtbewegungen
        $gesamt_bewegungen = $velo_plus + $velo_minus;

        // Speichern Sie die Bewegungsdaten für jede standort_id
        $bewegungsdaten[] = [
            'standort_id' => $standort_id,
            'velo_plus' => $velo_plus,
            'velo_minus' => $velo_minus,
            'bewegungen' => $gesamt_bewegungen
        ];
    }

    // Schritt 4: Fügen Sie die Bewegungsdaten in die Tabelle publibike_bewegungen ein
    foreach ($bewegungsdaten as $daten) {
        $stmt = $pdo->prepare("INSERT INTO publibike_bewegungen (standort_id, velo_plus, velo_minus, bewegungen) VALUES (:standort_id, :velo_plus, :velo_minus, :bewegungen)");
        $stmt->execute($daten);
    } 


    echo "Transformationsphase erfolgreich abgeschlossen.";

} catch (PDOException $e) {
    die("Fehler bei der Verbindung zur Datenbank: " . $e->getMessage());
}

?>
