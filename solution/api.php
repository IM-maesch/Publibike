<?php
require_once 'config.php'; // DB-Verbindungsdaten aus externer Datei laden

// 0. Datenbankverbindung mit PDO herstellen

try {

  $pdo = new PDO($dsn, $db_user, $db_pass, $options);
  // echo "Connected to the database successfully!";
} catch (PDOException $e) {
  // echo "Connection failed: " . $e->getMessage();
}

// 2. Einfügen eines neuen Datensatzes
if (isset($_POST['submit'])) {
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $email = $_POST['email'];

  $sql = "INSERT INTO User (firstname, lastname, email) VALUES (?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $result = $stmt->execute([$firstname, $lastname, $email]);

  if ($result) {
    // echo "Record inserted successfully!";
  } else {
    // echo "Error inserting record.";
  }
  $json = json_encode($result);
}

// 3. Lesen eines Datensatzes mit id

if (isset($_POST['read'])) {
  $id = $_POST['id'];

  $sql = "SELECT * FROM User WHERE id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $singleUser = $stmt->fetch(PDO::FETCH_ASSOC);
  $json = json_encode($singleUser);
} else {
  $singleUser = false;
  $json = json_encode($singleUser);
}

// 4. Lesen aller Datensätze, die den String $string in firstname, lastname oder email enthalten
if (isset($_POST['search'])) {
  $string = $_POST['string'];

  $sql = "SELECT * FROM User WHERE firstname LIKE ? OR lastname LIKE ? OR email LIKE ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(["%$string%", "%$string%", "%$string%"]);
  $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $json = json_encode($searchResults);
}

// 5. Delete mit email 
if (isset($_POST['delete'])) {
  $email = $_POST['email'];

  $sql = "DELETE FROM User WHERE email = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':email', $email);
  $result = $stmt->execute();

  if ($result) {
    // echo "Record deleted successfully!";
  } else {
    // echo "Error deleting record.";
  }
  $json = json_encode($result);
}


// 1. Abfrage aller Datensätze aus der Tabelle User
$sql = "SELECT * FROM User";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$jsonList = json_encode($users);


if ($json != "false") {
  echo $json;
} else {
  echo $jsonList;
}
