<?php

require_once '../config.php';
require_once 'compare.php';

$pdo = new PDO($dsn, $db_user, $db_pass, $options);

print_r($bewegungen);
writeToDatabase($bewegungen, $pdo);


function writeToDatabase($bewegungen, $pdo) {

    // Über jede Standort-ID iterieren
    foreach ($bewegungen as $standort) {
        // Anzahl der hinzugefügten und entfernten Fahrräder zählen
        $velo_plus = $standort['added'];
        $velo_minus = $standort['removed'];
        $standort_id = $standort['standortid'];

        // Gesamtbewegungszahl berechnen
        $standortaktivitaet = $velo_plus + $velo_minus;

        // SQL-Abfrage vorbereiten und ausführen, um Daten in die Tabelle publibike_bewegungen einzufügen
        $stmt = $pdo->prepare("INSERT INTO publibike_bewegungen (standort_id, velo_plus, velo_minus, standortaktivitaet) VALUES (:standort_id, :velo_plus, :velo_minus, :standortaktivitaet)");
        $stmt->execute([
            'standort_id' => $standort_id,
            'velo_plus' => $velo_plus,
            'velo_minus' => $velo_minus,
            'standortaktivitaet' => $standortaktivitaet
        ]);
    }
}


?>