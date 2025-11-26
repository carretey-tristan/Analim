# Analim

Projet de gestion d'un congrès (API + site web) — application PHP légère.

## Description

Ce dépôt contient deux parties principales : une API et une interface site web pour gérer les congressistes, les sessions, les factures, hôtels et organismes payeurs. Le projet s'appuie sur PHP classique (structure organisée par contrôleurs, repository et vues) et utilise la librairie `dompdf` pour la génération de PDFs (factures).

## Structure du projet

- `API_Congres/` : point d'entrée API (REST-like) et logique back-end pour exposer des endpoints. Fichier principal : `API_Congres/index.php`.
- `Site_Congres/` : interface front-end (pages publiques et formulaires). Fichier principal : `Site_Congres/index.php`.
- `classe/` : classes principales du domaine (ex : `Activite.php`, `Congressiste.php`, `Facture.php`, `Hotel.php`, `OrganismePayeur.php`, `Session.php`).
- `config/` : configuration, notamment `config/database.php` pour la connexion à la base de données.
- `controllers/` : contrôleurs côté serveur (ex : `AuthController.php`, `FactureController.php`, `AdminController.php`, etc.).
- `repository/` : classes d'accès aux données (pattern repository) pour chaque entité.
- `views/` : templates/partials et vues HTML (inclut la vue `facture/pdf.php` utilisée pour la génération PDF).
- `vendor/` : dépendances gérées par Composer (incluant `dompdf` pour la génération de PDF).

## Prérequis

- PHP 7.4+ (ou version compatible installée via WAMP). Tester avec `php -v`.
- Composer (pour installer les dépendances). Télécharger depuis https://getcomposer.org/.
- Serveur web local (WAMP, XAMPP, ou PHP built-in server pour tests).
- Une base de données MySQL/MariaDB et les informations de connexion à renseigner dans `config/database.php`.

## Installation

1. Placer le dossier dans le répertoire web (ex : `c:\wamp64\www\congres\Analim`).
2. Ouvrir une console depuis le dossier `Analim` et exécuter :

	`composer install`

	Cela installera les dépendances présentes dans `vendor/` si elles ne sont pas déjà installées.
3. Configurer la base de données : ouvrir `config/database.php` et modifier les paramètres (`host`, `dbname`, `user`, `password`) selon votre environnement.
4. Créer la base de données et les tables nécessaires. (Le dépôt ne contient pas toujours un dump SQL ; si vous en avez un, importez-le via phpMyAdmin ou `mysql`.)

## Configuration

- `config/database.php` : mettez vos identifiants DB et testez la connexion.
- `API_Congres/index.php` et `Site_Congres/index.php` : points d'entrée pour l'API et le site. Vérifiez les chemins et virtual hosts selon votre configuration WAMP.

## Usage

- Accéder au site : `http://localhost/congres/Analim/Site_Congres/` (ou selon votre configuration Apache / virtual host).
- Accéder à l'API : `http://localhost/congres/Analim/API_Congres/` (les routes sont gérées dans ce dossier via `index.php` et les contrôleurs).

## Génération de PDF (Factures)

La génération de factures en PDF utilise `dompdf`. La vue `views/facture/pdf.php` contient le template HTML qui sera converti en PDF par la librairie. Exemples d'utilisation : voir `controllers/FactureController.php` et la repository correspondante pour la logique d'assemblage des données et rendu.

## Fichiers et composants importants

- `classe/` : modèles métier.
- `controllers/` : logique applicative (authentification, administration, facturation, etc.).
- `repository/` : accès aux données (CRUD pour les entités).
- `views/partials/header.php` et `views/partials/footer.php` : layout commun.
- `views/facture/pdf.php` : template PDF.
- `vendor/dompdf/` : librairie de conversion HTML -> PDF.

## Bonnes pratiques et notes

- Sauvegardez vos identifiants DB en dehors du contrôle de version si possible (utiliser des variables d'environnement ou un fichier de configuration local ignoré par git).
- Si vous ajoutez des dépendances, lancez `composer install` ou `composer update` selon le cas.
- Tester les pages et l'API via navigateur ou outils type Postman.

## Développement et contribution

- Pour contribuer, forkez le dépôt, faites une branche par fonctionnalité, et créez une Pull Request quand prêt.
- Documentez les changements importants et mettez à jour ce `README.md` si de nouveaux composants ou instructions d'installation sont ajoutés.

## Prochaines étapes suggérées

- Ajouter un dump SQL d'installation (`database/schema.sql`) si nécessaire.
- Ajouter des scripts d'autotests ou validateurs PHP (ex : PHPStan, PHPUnit) si vous souhaitez assurer la qualité du code.

## Contact

Pour questions ou aide, documentez un issue dans le dépôt ou contactez l'auteur du projet.

---
Fait pour faciliter l'installation et la compréhension rapide du projet Analim.
projet congrés
