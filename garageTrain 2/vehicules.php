<?php
session_start();
require_once('database/db.php');
require_once('security/connexion.php');

if(!isTokenValid($_SESSION['token'])){
    header("Location: /index.php");
    exit();
}

$conn = connectDB();
$message = '';
$error = '';

// Suppression d'un véhicule
if(isset($_POST['delete']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM vehicules WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) {
        $message = "Véhicule supprimé avec succès";
    } else {
        $error = "Erreur lors de la suppression";
    }
}

// Ajout ou modification d'un véhicule
if(isset($_POST['submit'])) {
    $plaque = $_POST['plaque'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $annee = $_POST['annee'];
    $client_id = !empty($_POST['client_id']) ? $_POST['client_id'] : null;

    if(isset($_POST['id'])) {
        // Modification
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE vehicules SET plaque = ?, marque = ?, modele = ?, annee = ?, client_id = ? WHERE id = ?");
        $stmt->bind_param("sssiii", $plaque, $marque, $modele, $annee, $client_id, $id);
        if($stmt->execute()) {
            $message = "Véhicule modifié avec succès";
        } else {
            $error = "Erreur lors de la modification";
        }
    } else {
        // Ajout
        $stmt = $conn->prepare("INSERT INTO vehicules (plaque, marque, modele, annee, client_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $plaque, $marque, $modele, $annee, $client_id);
        if($stmt->execute()) {
            $message = "Véhicule ajouté avec succès";
        } else {
            $error = "Erreur lors de l'ajout";
        }
    }
}

// Récupération des clients pour le select
$clients = $conn->query("SELECT id, nom FROM clients ORDER BY nom");

// Récupération des véhicules
$vehicules = $conn->query("SELECT v.*, c.nom as client_nom FROM vehicules v LEFT JOIN clients c ON v.client_id = c.id ORDER BY v.marque, v.modele");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Véhicules - Garage Train</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Gestion des Véhicules</h1>
        
        <?php if($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Formulaire d'ajout/modification -->
        <form method="POST" class="mb-4">
            <input type="hidden" name="id" id="vehicule_id">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Plaque</label>
                        <input type="text" name="plaque" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Marque</label>
                        <input type="text" name="marque" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Modèle</label>
                        <input type="text" name="modele" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Année</label>
                        <input type="number" name="annee" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Client</label>
                        <select name="client_id" class="form-control">
                            <option value="">Aucun</option>
                            <?php while($client = $clients->fetch_assoc()): ?>
                                <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['nom']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary mt-3">Enregistrer</button>
        </form>

        <!-- Liste des véhicules -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Plaque</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Année</th>
                    <th>Client</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($vehicule = $vehicules->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($vehicule['plaque']) ?></td>
                        <td><?= htmlspecialchars($vehicule['marque']) ?></td>
                        <td><?= htmlspecialchars($vehicule['modele']) ?></td>
                        <td><?= htmlspecialchars($vehicule['annee']) ?></td>
                        <td><?= htmlspecialchars($vehicule['client_nom'] ?? 'Aucun') ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" 
                                    data-id="<?= $vehicule['id'] ?>"
                                    data-plaque="<?= htmlspecialchars($vehicule['plaque']) ?>"
                                    data-marque="<?= htmlspecialchars($vehicule['marque']) ?>"
                                    data-modele="<?= htmlspecialchars($vehicule['modele']) ?>"
                                    data-annee="<?= htmlspecialchars($vehicule['annee']) ?>"
                                    data-client="<?= htmlspecialchars($vehicule['client_id'] ?? '') ?>">
                                Modifier
                            </button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?');">
                                <input type="hidden" name="id" value="<?= $vehicule['id'] ?>">
                                <button type="submit" name="delete" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Gestion de la modification
        $('.edit-btn').click(function() {
            $('#vehicule_id').val($(this).data('id'));
            $('input[name="plaque"]').val($(this).data('plaque'));
            $('input[name="marque"]').val($(this).data('marque'));
            $('input[name="modele"]').val($(this).data('modele'));
            $('input[name="annee"]').val($(this).data('annee'));
            $('select[name="client_id"]').val($(this).data('client'));
        });
    </script>
</body>
</html>