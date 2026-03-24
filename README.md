# ANALIM — Documentation

Cette documentation décrit le mini-projet "Analim" : structure, routes, controllers, repositories, vues, et points d'attention (sécurité, amélioration).

## Aperçu
C'est une application PHP simple (sans framework) qui gère des congressistes et leurs factures. Le rendu HTML est produit via des vues classiques, et la génération de PDF utilise Dompdf.

Technos principales
- PHP (>= 7.4 / 8.x recommandé)
- PDO (MySQL)
- dompdf/dompdf (via Composer)

## Arborescence principale
- `index.php` : front controller (router minimal avec mode DEBUG et vérification PDO)
- `config/database.php` : configuration de la connexion PDO
- `controllers/` : contrôleurs (AuthController, AccountController, AdminController, FactureController, BaseController)
- `repository/` : couche d'accès aux données (SQL en minuscules pour compatibilité Linux)
- `classe/` : classes métier (Congressiste, Facture, Hotel, OrganismePayeur, ...)
- `views/` : vues PHP pour rendre les pages et le PDF
- `app/css/` : styles

## Déploiement Linux / Proxmox (Important)
Pour assurer le fonctionnement sur des serveurs Linux (casse sensible) :
1. **Noms de fichiers** : Utilisez uniquement des minuscules pour les fichiers de config (`database.php`) et respectez strictement la casse pour les classes (`Facture.php`).
2. **Tables MySQL** : Les requêtes SQL utilisent désormais des noms de tables en **minuscules** (ex: `SELECT * FROM congressiste`) pour éviter les erreurs "Table doesn't exist" sur Linux.
3. **Mode Debug** : `index.php` contient des `ini_set('display_errors', 1)` pour faciliter le diagnostic sur serveur distant.

## Routes / points d'entrée
Le front controller lit `$_GET['c']` (controller) et `$_GET['a']` (action). Exemples utiles :

- `index.php?c=auth&a=login` — formulaire de connexion
- `index.php?c=auth&a=register` — inscription
- `index.php?c=account&a=monespace` — page personnel (Mon espace)
- `index.php?c=admin` — tableau d'administration (agents/admins)
- `index.php?c=facture&a=pdf&id=456` — génère la facture PDF #456

## Authentification et sessions
- L'authentification utilise `password_verify()`.
- L'utilisateur avec l'ID **25** est considéré comme Agent/Admin (`$_SESSION['is_agent'] == true`).

## Dépendances
- **Dompdf** : Nécessite `composer install` pour générer le dossier `vendor/`.


---

