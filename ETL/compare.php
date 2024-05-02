<?php

require_once '../config.php';
$pdo = new PDO($dsn, $db_user, $db_pass, $options);

$newData = getNewData();
// print_r($newData);

// echo "<br><br><br>";

$oldData = getOldData();
// print_r($oldData);

$bewegungen = compare($oldData, $newData);
print_r($bewegungen);

function compare($oldData, $newData){
    $addedBikeIds = [];
    $removedBikeIds = [];
    
    // Compare each standort_id in the new data with the old data
    foreach ($newData as $standort_id => $newBikeIds) {
        // If the standort_id exists in the old data
        if (isset($oldData->{$standort_id})) {
            // Find the difference in bike IDs between the old and new data
            $addedBikeIds[$standort_id] = array_diff($newBikeIds, $oldData->{$standort_id});
            $removedBikeIds[$standort_id] = array_diff($oldData->{$standort_id}, $newBikeIds);
        } else {
            // If the standort_id is new in the new data, all bike IDs are considered added
            $addedBikeIds[$standort_id] = $newBikeIds;
        }
    }
    
    // Find standort_ids that are present in the old data but not in the new data
    $missingStandortIds = array_diff(array_keys((array)$oldData), array_keys((array)$newData));
    foreach ($missingStandortIds as $standort_id) {
        // All bike IDs for missing standort_ids are considered removed
        $removedBikeIds[$standort_id] = $oldData->{$standort_id};
    }
    
    return ['added' => $addedBikeIds, 'removed' => $removedBikeIds];
}



function getOldData(){
    global $pdo; // Access the PDO object declared outside the function
    
    try {
        // Prepare and execute the SQL query to fetch data from the database
        $stmt = $pdo->query('SELECT standort_id, bike_id FROM publibike_api_lesen');
        
        // Initialize an empty array to store the formatted data
        $oldData = [];
        
        // Fetch each row from the result set
        while ($row = $stmt->fetch()) {
            $standort_id = $row['standort_id'];
            $bike_id = $row['bike_id'];
            
            // Check if the standort_id already exists in the array, if not, initialize it
            if (!isset($oldData[$standort_id])) {
                $oldData[$standort_id] = [];
            }
            
            // Add the bike_id to the array for the corresponding standort_id
            $oldData[$standort_id][] = $bike_id;
        }
        
        return (object)$oldData; // Convert the array to an object before returning
    } catch (Exception $e) {
        // Handle any exceptions that occur during database access
        echo "Fehler: " . $e->getMessage();
        return null; // Return null to indicate failure
    }
}



function getNewData(){

try {

    // Daten von der Publibike-API abrufen
    $url = 'https://api.publibike.ch/v1/public/partner/stations';
    $data = json_decode(file_get_contents($url), true);

    // Liste der Standort-IDs nach Größe sortiert
    $location_ids = [105, 217, 233, 353, 506, 513, 872, 873];

    // Assoziatives Array für Standorte und ihre Fahrzeuge erstellen
    $stations = [];

    // Durch die Standorte iterieren und nach Standort-IDs filtern
    foreach ($data['stations'] as $location) {
        if (in_array($location['id'], $location_ids)) {
            // Fahrzeuge für den aktuellen Standort abrufen
            $vehicle_ids = [];
            foreach ($location['vehicles'] as $vehicle) {
                $vehicle_ids[] = $vehicle['id'];
            }
            $stations[$location['id']] = $vehicle_ids;
        }
    }

    // Objekt aller Stationen mit ihren Fahrzeugen erstellen
    $stationsObject = (object)$stations;

    // Ausgabe des Objekts (optional)
    return $stationsObject;

} catch (Exception $e) {
    echo "Fehler: " . $e->getMessage();
}

}
?>
