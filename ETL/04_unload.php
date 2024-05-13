<?php

// Datenbankkonfiguration einbinden
require_once 'config.php';

// Header setzen, um JSON-Inhaltstyp zurückzugeben
header('Content-Type: application/json');


try {
    // Erstellt eine neue PDO-Instanz mit der Konfiguration aus config.php
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);

     // SQL-Query, um Daten basierend auf dem Standort auszuwählen, sortiert nach Zeitstempel
    $sql = "SELECT standort_id, timestamp, standortaktivitaet FROM publibike_bewegungen WHERE standort_id IN (506, 233, 353, 217, 513, 105, 872, 873) ORDER BY standort_id, timestamp";


    // Bereitet die SQL-Anweisung vor
    $stmt = $pdo->prepare($sql);

    // Führt die Abfrage mit der Standortvariablen aus, die in einem Array übergeben wird
    // Die Standortvariable ersetzt das erste Fragezeichen in der SQL-Anweisung
    $stmt->execute();

    // Holt alle passenden Einträge
    $results = $stmt->fetchAll();

    // Initialisiere ein leeres Array, um Daten nach Standorten zu gruppieren
    $groupedData = [];

    // Gruppiere die Daten nach standort_id
    foreach ($results as $row) {
        $standort_id = $row['standort_id'];
        $timestamp = $row['timestamp'];
        $standortaktivitaet = $row['standortaktivitaet'];

        // Überprüfe, ob der Standort bereits im Array vorhanden ist
        if (!isset($groupedData[$standort_id])) {
            // Wenn nicht, initialisiere ein leeres Array für diesen Standort
            $groupedData[$standort_id] = [];
        }

        // Füge den Datensatz dem entsprechenden Standort hinzu
        $groupedData[$standort_id][] = ['timestamp' => $timestamp, 'standortaktivitaet' => $standortaktivitaet];
    }

    // Gibt die Ergebnisse im JSON-Format zurück
    echo json_encode($groupedData);
} catch (PDOException $e) {
    // Gibt eine Fehlermeldung zurück, wenn etwas schiefgeht
    echo json_encode(['error' => $e->getMessage()]);
}