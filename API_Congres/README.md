# API_Congres — Documentation

Cette documentation décrit le mini-projet "API_Congres" : structure, routes, controllers, repositories, vues, et points d'attention (sécurité, amélioration).

## Aperçu
C'est une application PHP simple (sans framework) qui gère des congressistes et leurs factures. Le rendu HTML est produit via des vues classiques, et la génération de PDF utilise Dompdf.

Technos principales
- PHP (>= 7.4 / 8.x recommandé)
- PDO (MySQL)
- dompdf/dompdf (via Composer)

## Arborescence principale
- `index.php` : front controller (router minimal basé sur `?c=CONTROLLER&a=ACTION`)
- `config/Database.php` : configuration de la connexion PDO
- `controllers/` : contrôleurs (AuthController, AccountController, AdminController, FactureController, BaseController)
- `repository/` : couche d'accès aux données (AuthRepository, CongressisteRepository, FactureRepository, HotelRepository, OrganismePayeurRepository)
- `classe/` : classes métier (Congressiste, Facture, Hotel, OrganismePayeur, ...)
- `views/` : vues PHP pour rendre les pages et le PDF (`views/facture/pdf.php` pour la facture)
- `app/css/` : styles

## Routes / points d'entrée
Le front controller lit `$_GET['c']` (controller) et `$_GET['a']` (action). Exemples utiles :

- `index.php?c=auth&a=login` — formulaire de connexion (POST → même route)
- `index.php?c=auth&a=register` — inscription (POST → même route)
- `index.php?c=account&a=monespace` — page "Mon espace" (accessible si connecté)
- `index.php?c=admin` — tableau d'administration (accessible aux agents/admins)
- `index.php?c=admin&a=create&id=123` — créer une facture pour le congressiste 123 (POST idéalement)
- `index.php?c=facture&a=pdf&id=456` — génère et affiche la facture PDF #456

## Authentification et sessions
- L'authentification est simple : `AuthRepository::findByEmail()` et `password_verify()` sont utilisés.
- Après login, sont stockés en session :
  - `$_SESSION['user_id']` — id du congressiste connecté
  - `$_SESSION['email']` — email
  - `$_SESSION['is_agent']` — bool (vrai si user id == 25 dans l'implémentation actuelle)

Remarque : l'utilisation d'un `is_agent` basé sur l'id est temporaire et fragile. Préférez ajouter un champ `role` dans la table `CONGRESSISTE`.

## Contrôleurs importants
- `AuthController` : login, register, création de session
- `AccountController::monEspace()` : récupère les factures du congressiste connecté
- `AdminController::index()` : affiche la liste des factures (filtrage) et les congressistes sans facture — accès restreint aux agents
- `FactureController::pdf()` : charge la `Facture` via `FactureRepository::findById()` puis inclut `views/facture/pdf.php` pour produire le HTML et utilise Dompdf pour afficher le PDF.
  - Ajout d'une vérification d'autorisation : seul le propriétaire (congressiste) ou un agent peut voir une facture.

## Repositories
- `AuthRepository` : recherche et création d'utilisateurs (congressistes)
- `CongressisteRepository` : recherche par id, liste des congressistes, relation vers `Hotel` et `OrganismePayeur`
- `FactureRepository` : logique métier pour trouver, créer et prévisualiser des factures ; calcule des montants agrégés (hôtel, sessions, activités, acompte)
- `HotelRepository`, `OrganismePayeurRepository` : petites méthodes utilitaires pour récupérer les objets reliés

## Vues
- `views/admin/index.php` — tableau des factures et listing des congressistes sans facture
- `views/account/monespace.php` — page personnelle affichant les factures de l'utilisateur
- `views/facture/pdf.php` — template HTML stylé transformé en PDF par Dompdf

Remarque : certaines vues supportent à la fois des objets et des tableaux (le code vérifie `is_array` et `is_object` lorsque nécessaire).

## Dépendances
- dompdf/dompdf (via Composer)
  - Assurez-vous d'avoir exécuté `composer install` pour installer `vendor/`.


---

