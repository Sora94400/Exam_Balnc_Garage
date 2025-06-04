<?php

function connectDB(){
  $host = '127.0.0.1:80';
  $user = 'root';
  $password = '';
  $database = 'garage';
  $conn = new mysqli($host, $user, $password, $database);

  if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
  }
  else{
    return $conn;
  }
}

