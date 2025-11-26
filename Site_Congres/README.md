# AP3.1 – Application Séminaire (consommation d’une API REST)

## Structure du dossier

```
SITE_SEMINAIRE_API_ETUDIANT/
│   index.php
│   README.md
└───app/
    │   ApiClient.php
    └───controllers/
    │       ConferenceController.php
    │       AuthController.php
    └───views/
      │   layout.php
      ├───conference/
      │       list.php
      ├───auth/
      │       login.php
      │       register.php
      ├───home/
      │       index.php
      └───inscription/
            list.php
images/                (optionnel, pour illustrations)
css/                   (optionnel, pour vos feuilles de style)
```

# AP3.1 – Application Séminaire (consommation d’une API REST)

## Objectif général
Développer une application web PHP (architecture MVC) qui consomme une API REST pour gérer les inscriptions à un séminaire.

---

## Étape 1 : Comprendre l’architecture du projet

### Objectif
Découvrir l’architecture MVC proposée et le rôle de chaque dossier/fichier.

### Consignes
1. Ouvrez le dossier du projet et repérez les dossiers suivants :
   - `app/controllers/` : Contiendra les contrôleurs (logique métier, gestion des requêtes)
   - `app/views/` : Contiendra les vues (affichage HTML)
   - `app/ApiClient.php` : Servira à communiquer avec l’API REST
   - `index.php` : Point d’entrée de l’application

2. Lisez le contenu de chaque fichier fourni. Repérez les parties à compléter (indiquées par `TODO`).

---

## Étape 2 : Compléter le client API

### Objectif
Écrire les méthodes de base pour communiquer avec l’API REST.

### Consignes
1. Dans `app/ApiClient.php`, complétez les méthodes `request`, `login`, `getConferences`, `inscrire`, `getInscriptions` selon les commentaires et indices.
2. Utilisez cURL pour envoyer les requêtes à l’API.
3. Si besoin, testez chaque méthode avec des var_dump pour vérifier les retours.

---

## Étape 3 : Afficher la liste des conférences

### Objectif
Afficher dynamiquement la liste des conférences dans la vue.

### Consignes
1. Dans `ConferenceController.php`, complétez la méthode `list` pour :
   - Instancier `ApiClient`
   - Récupérer la liste des séminaires (pour le filtre)
   - Récupérer la liste des conférences (filtrées ou non)
   - Inclure la vue `conference/list.php` en passant les variables nécessaires
2. Dans la vue, bouclez sur les conférences et affichez les informations principales.

---

## Étape 4 : Authentification (connexion)

### Objectif
Permettre à un utilisateur de se connecter via l’API.

### Consignes
1. Dans `AuthController.php`, complétez les méthodes `login` et `register`.
2. Dans la vue `auth/login.php`, créez le formulaire de connexion.
3. Gérez la session PHP pour mémoriser l’utilisateur connecté.
4. Affichez un message d’erreur en cas d’échec.

---

## Étape 5 : Inscription à une conférence

### Objectif
Permettre à un utilisateur connecté de s’inscrire à une conférence via l’API.

### Consignes
1. Dans la vue des conférences, ajoutez un bouton "S’inscrire" pour chaque conférence.
2. Dans `ConferenceController.php`, traitez l’inscription via la méthode `inscrire` du client API.
3. Affichez un message de confirmation ou d’erreur.

---

## Étape 6 : Affichage des inscriptions de l’utilisateur

### Objectif
Afficher la liste des conférences auxquelles l’utilisateur est inscrit.

### Consignes
1. Ajoutez une méthode dans `ApiClient.php` pour récupérer les inscriptions de l’utilisateur.
2. Créez un contrôleur et une vue pour afficher ces inscriptions.

---

## Étape 7 : Déconnexion et gestion de session

### Objectif
Permettre à l’utilisateur de se déconnecter proprement.

### Consignes
1. Dans `AuthController.php`, complétez la méthode `logout` pour détruire la session.
2. Ajoutez un lien de déconnexion dans le layout ou le menu.

---

## Conseils pédagogiques

- Utilisez les blocs `TODO` et les commentaires dans chaque fichier pour guider votre travail.
- Testez chaque étape avant de passer à la suivante.
- Utilisez `var_dump()` pour le debug.

---

> N’hésitez pas à demander de l’aide ou des précisions à votre enseignant si besoin !
