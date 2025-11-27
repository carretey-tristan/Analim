# Analim

Projet de gestion d'un congrès (API + site web) — application PHP légère.

## Vue d'ensemble

Ce dépôt contient deux parties principales :
- une API (`API_Congres/`) pour exposer des endpoints REST-like ;
- une interface web (`Site_Congres/`) pour la gestion et la consultation (authentification, liste des conférences, inscriptions, etc.).

Le projet utilise une architecture PHP classique (contrôleurs, repositories, vues) et inclut la bibliothèque `dompdf` pour la génération de PDF (ex. factures).

## Prérequis

- PHP 7.4+ (ou version compatible) installé via WAMP, XAMPP ou équivalent.
- Composer (https://getcomposer.org/) pour gérer les dépendances.
- MySQL / MariaDB pour la base de données.
- Un serveur web local configuré (Apache dans WAMP/XAMPP) ou utilisation du serveur PHP intégré pour des tests.

## Installation rapide

1. Copier le dossier `Analim` dans votre répertoire web (ex : `c:\wamp64\www\congres\Analim`).
2. Depuis le dossier `Analim`, installer les dépendances :

```powershell
composer install
```

3. Configurer la base de données :
   - Ouvrir `config/database.php` et renseigner `host`, `dbname`, `user`, `password`.
4. Créer la base de données et les tables nécessaires : importer votre dump SQL via phpMyAdmin ou `mysql` si vous en disposez.

## Structure du projet

- `API_Congres/` : API (point d'entrée `index.php`), classes métier et contrôleurs REST.
- `Site_Congres/` : application front (point d'entrée `index.php`, contrôleurs côté client, vues et assets).
- `classe/` : entités métier (ex. `conference.php`, `intervenant.php`, `seminaire.php`).
- `config/` : fichiers de configuration (notamment `database.php`).
- `repository/` : classes d'accès aux données (pattern repository).
- `vendor/` : dépendances Composer (ex. `dompdf`).
- `Site_Congres/views/` : vues et templates pour l'interface utilisateur.

> Remarque : il existe un `README.md` spécifique dans `Site_Congres/` qui décrit le front-end plus en détail.

## Points d'attention

- `config/database.php` : configurez correctement vos identifiants DB avant d'exécuter l'application.
- `vendor/` : si ce dossier n'est pas présent, lancer `composer install`.
- Génération PDF : la logique se trouve dans les contrôleurs/ vues utilisant `dompdf` (voir `controllers/FactureController.php` si présent).

## Lancement et accès

- Application (site) : `http://localhost/congres/Analim/Site_Congres/` (ou selon votre configuration de virtual host).
- API : `http://localhost/congres/Analim/API_Congres/`.

Pour tests rapides depuis la ligne de commande (PHP built-in server) :

```powershell
cd c:\wamp64\www\congres\Analim\Site_Congres
php -S localhost:8000
# puis ouvrir http://localhost:8000
```

## Bonnes pratiques

- Ne commitez pas vos identifiants de base de données : utilisez un fichier local ignoré ou des variables d'environnement.
- Vérifiez les permissions d'écriture si l'application doit gérer des uploads (photos, PDF générés).
- Documentez et versionnez les dumps SQL d'installation si vous partagez le projet.

## Développement et contribution

- Forkez le dépôt, créez une branche par fonctionnalité et ouvrez une Pull Request.
- Ajoutez des tests unitaires / d'intégration (PHPUnit) et un linter (PHPStan) pour améliorer la qualité du code.

## Prochaines améliorations suggérées

- Ajouter un dump SQL `database/schema.sql` et un script `setup` pour automatiser l'installation.
- Ajouter un guide de configuration pour les environnements (dev / prod).

## Contact

Pour toute question, ouvrez un issue dans le dépôt ou contactez l'auteur.

---
README mis à jour : correction et clarifications sur l'installation et la structure.
