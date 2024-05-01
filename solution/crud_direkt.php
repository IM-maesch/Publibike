<?php
require_once 'config.php'; // DB-Verbindungsdaten aus externer Datei laden

// 0. Datenbankverbindung mit PDO herstellen

try {

  $pdo = new PDO($dsn, $db_user, $db_pass, $options);
  echo "Connected to the database successfully!";
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
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
    echo "Record inserted successfully!";
  } else {
    echo "Error inserting record.";
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
    echo "Record deleted successfully!";
  } else {
    echo "Error deleting record.";
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
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>PHP mit HTML</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <h1><a href="crud_direkt.php">CRUD - PHP</a></h1>


  <!-- 1. Ausgabe des Arras $users in einer Tabelle -->
  <table>
    <tr>
      <th>ID</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Email</th>
    </tr>
    <?php foreach ($users as $user) : ?>
      <tr>
        <td><?php echo $user['id']; ?></td>
        <td><?php echo $user['firstname']; ?></td>
        <td><?php echo $user['lastname']; ?></td>
        <td><?php echo $user['email']; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <!-- 2. Einfügen eines neuen Datensatzes -->
  <form method="POST" action="">
    <input type="text" name="firstname" placeholder="First Name">
    <input type="text" name="lastname" placeholder="Last Name">
    <input type="email" name="email" placeholder="Email">
    <button type="submit" name="submit">Insert</button>
  </form>

  <?php
  ?>

  <!-- 3. Lesen eines Datensatzes mit id -->
  <form method="POST" action="">
    <input type="text" name="id" placeholder="ID">
    <button type="submit" name="read">Read</button>
  </form>

  <?php

  if ($singleUser) {
    echo "ID: " . $singleUser['id'] . "<br>";
    echo "First Name: " . $singleUser['firstname'] . "<br>";
    echo "Last Name: " . $singleUser['lastname'] . "<br>";
    echo "Email: " . $singleUser['email'] . "<br>";
  }
  ?>

  <!-- 4. Lesen aller Datensätze, die den String $string in firstname, lastname oder email enthalten -->
  <form method="POST" action="">
    <input type="text" name="string" placeholder="Search">
    <button type="submit" name="search">Search</button>
  </form>

  <?php
  if (isset($searchResults)) {
    foreach ($searchResults as $result) {
      echo "ID: " . $result['id'] . "<br>";
      echo "First Name: " . $result['firstname'] . "<br>";
      echo "Last Name: " . $result['lastname'] . "<br>";
      echo "Email: " . $result['email'] . "<br>";
    }
  }
  ?>

  <!-- 5. Delete mit email -->
  <form method="POST" action="">
    <input type="email" name="email" placeholder="Email">
    <button type="submit" name="delete">Delete</button>
  </form>

  <?php

  ?>


</body>

</html>