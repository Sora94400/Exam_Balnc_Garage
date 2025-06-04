# Garage Train - Gestion de Véhicules

## Description
Ce projet est une application web de gestion pour le Garage Train. Il permet de gérer les véhicules, les clients et les rendez-vous. La nouvelle fonctionnalité de gestion des véhicules permet d'ajouter, modifier et supprimer des véhicules, ainsi que de les associer à des clients.

## Fonctionnalités
- Gestion des véhicules (CRUD)
  - Ajout de nouveaux véhicules
  - Modification des informations
  - Suppression de véhicules
  - Association avec des clients
- Tableau de bord avec statistiques
- Système d'authentification sécurisé

## Structure de la Base de Données
- Table `vehicules`
  - id (INT, Primary Key)
  - plaque (VARCHAR)
  - marque (VARCHAR)
  - modele (VARCHAR)
  - annee (INT)
  - client_id (INT, Foreign Key)

## Sécurité
- Authentification par token
- Protection contre les injections SQL
- Validation des données
- Sessions sécurisées

## Installation
1. Cloner le repository
2. Importer le fichier `import.sql` dans votre base de données
3. Configurer les accès à la base de données dans `database/db.php`
4. Lancer l'application sur un serveur PHP

## Utilisation
1. Se connecter avec les identifiants administrateur
2. Accéder à la gestion des véhicules via le tableau de bord
3. Utiliser les formulaires pour gérer les véhicules

## Technologies Utilisées
- PHP natif
- MySQL
- Bootstrap pour l'interface
- jQuery pour les interactions

## Auteur
Garage Train