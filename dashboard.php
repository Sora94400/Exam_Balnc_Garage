<?php
session_start();

require_once('database/db.php');
$conn = connectDB();

$result = $conn->query("SELECT COUNT(*) AS total_clients FROM clients");
$row = $result->fetch_assoc();
$totalClients = $row['total_clients'];

$result = $conn->query("SELECT COUNT(*) AS total_vehicules FROM vehicules");
$row = $result->fetch_assoc();
$totalVehicules = $row['total_vehicules'];

$result = $conn->query("SELECT COUNT(*) AS total_rendezvous FROM rendezvous");
$row = $result->fetch_assoc();
$totalRendezvous = $row['total_rendezvous'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Garage Train</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Tableau de Bord Garage Train</h1>
        
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2>Clients</h2>
                        <p>Total Clients: <?= $totalClients ?></p>
                        <a href="clients.php" class="btn btn-primary">Gérer les clients</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2>Véhicules</h2>
                        <p>Total Véhicules: <?= $totalVehicules ?></p>
                        <a href="vehicules.php" class="btn btn-primary">Gérer les véhicules</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2>Rendez-vous</h2>
                        <p>Total Rendez-vous: <?= $totalRendezvous ?></p>
                        <a href="rendezvous.php" class="btn btn-primary">Gérer les rendez-vous</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
