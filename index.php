<?php

require './vendor/autoload.php';

$redis = new Predis\Client();

$cachedEntry = $redis->get('actor');

$t0 = 0;
$t1 = 0;

if ($cachedEntry) {
  // display the result from the cache
  echo "<h3>From Redis Cache</h3>";
  $t0 = microtime((true)) * 1000;
  echo $cachedEntry;
  $t1 = microtime((true)) * 1000;
  echo '<h4>Time taken: ' . round($t1 - $t0, 4) . '</h4>';
  exit();
} else {
  // connect to the database, display data and cache it in Redis
  $t0 = microtime((true)) * 1000;
  $conn = new mysqli('localhost:3306', 'root', '', 'redis_test');

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT first_name, last_name FROM actor";
  $result = $conn->query($sql);
  echo "<h3>From Database</h3>";

  $temp = '';
  $num = 0;
  while ($row = $result->fetch_assoc()) {
    $num += 1;
    echo $num . '. ';
    echo $row['first_name'] . ' ';
    echo $row['last_name'] . '<br>';
    $temp .= $num . '. ' . $row['first_name'] . '   ' . $row['last_name'] . '<br>';
  }

  $t1 = microtime((true)) * 1000;
  echo '<h4>Time taken: ' . round($t1 - $t0, 4) . '</h4>';

  $redis->set('actor', $temp);

  $redis->expire('actor', 20);

  $conn->close();
  exit();
}
