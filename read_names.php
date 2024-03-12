<?php

$conn = new mysqli('localhost:3306', 'root', '', 'redis_test');

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Read names from a file
$names = file("names.txt", FILE_IGNORE_NEW_LINES);

// Generate and execute SQL INSERT statements
foreach ($names as $name) {
  $name_parts = explode(" ", $name);
  $first_name = $name_parts[0];
  $last_name = $name_parts[1];
  $last_update = date("Y-m-d H:i:s");

  $sql = "INSERT INTO actor (first_name, last_name, last_update) VALUES ('$first_name', '$last_name', '$last_update')";
  if ($conn->query($sql) !== TRUE) {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

echo "Data inserted successfully";

// Close connection
$conn->close();
