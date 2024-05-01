<?php
// Beispiele für verschiedene Variablentypen in PHP (Integer, Float, String, Boolean, Array, Array mit Strings, Array mit gemischten Werten, assoziatives Array)
$integer = 42;
$float = 3.14;
$string = "Hallo Welt!";
$boolean = true;
$array = array(1, 2, 3, 4, 5);
$arrayStrings = array("Apfel", "Birne", "Banane");
$arrayMixed = array(1, "Apfel", 3.14, true);
$associativeArray = array("Vorname" => "Alina", "Nachname" => "Weisser", "Grösse" => 1.64);

// Ausgabe der Variablen
echo "Integer: " . $integer . "<br>";
echo "Float: " . $float . "<br>";
echo "String: " . $string . "<br>";
echo "Boolean: " . $boolean . "<br>";
echo "Array: ";
print_r($array);
echo "<br>";
echo "Array mit Strings: ";
print_r($arrayStrings);
echo "<br>";
echo "Array mit gemischten Werten: ";
print_r($arrayMixed);
echo "<br>";
echo "Assoziatives Array: ";
print_r($associativeArray);

// Ausgabe einzelner Werte aus dem assoziativen Array
echo "<br>";
echo "Vorname: " . $associativeArray["Vorname"] . "<br>";
echo "Nachname: " . $associativeArray["Nachname"] . "<br>";

// Bedingungen
if ($integer > 40) {
  echo "Die Zahl ist grösser als 40.<br>";
} else {
  echo "Die Zahl ist kleiner oder gleich 40.<br>";
}

// Schleifen
echo "Zahlen von 1 bis 10:<br>";
for ($i = 1; $i <= 10; $i++) {
  echo $i . "<br>";
}

// Array mit Schleifen ausgeben
echo "Array mit Schleife ausgeben:<br>";
for ($i = 0; $i < count($arrayStrings); $i++) {
  echo $arrayStrings[$i] . "<br>";
}

// Assoziatives Array mit Schleifen ausgeben
echo "Assoziatives Array mit Schleife ausgeben:<br>";
foreach ($associativeArray as $key => $value) {
  echo $key . ": " . $value . "<br>";
}

// Funktionen
function addieren($a, $b)
{
  return $a + $b;
}

echo "Ergebnis der Funktion addieren: " . addieren(3, 5) . "<br>";

?>
<!-- erstelle eine einfache HTML-Struktur und gebe alle oben erzeugten Werte in der Struktur aus -->