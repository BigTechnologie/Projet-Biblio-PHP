# Projet:

1. Création de la page d'accueil :
    La page d'accueil inclura un header et un footer.
    Le header contiendra une barre de navigation permettant d'accéder aux différentes pages.

2. Accès à la page d'administration :
    La page d'administration permet d'ajouter, de gérer et de modifier des produits (livres).

Conditions d'accès :

Utilisateur connecté : L'accès à la page(administration) est possible uniquement si l'utilisateur est authentifié.
Utilisateur non connecté :
Redirection automatique vers une page de connexion.
Sur cette page, l'utilisateur peut se connecter en saisissant un identifiant et un mot de passe existant dans la base de données.
Une fois authentifié, l'utilisateur est redirigé vers la page "Ajouter un produit".

Depuis cette page, l'utilisateur peut effectuer les actions suivantes :
Créer un livre.
Supprimer un livre.
Modifier les informations d'un livre.
Consulter ou récupérer des données existantes depuis la base de données.

3. Gestion SQL :
Mise en place d’un système pour :
Créer automatiquement la base de données si elle n’existe pas.
Créer également les tables nécessaires, notamment les tables livre et utilisateur, si elles n’existent pas.

4. Astuce technique :
Lorsqu'un utilisateur tente d'accéder à une page spécifique sans être connecté :
Enregistrer dans une variable de session l’adresse de la page initialement demandée.
Après connexion, vérifier dans $_SESSION si une redirection vers la page d’origine est nécessaire.  