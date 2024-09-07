# ToDoList : OpenClassrooms Project n° 8

## Introduction

Ce projet vise à améliorer une application de ToDoList existante en y ajoutant les fonctionnalités suivantes :

- Associer une tâche à la personne connectée ou le cas échéant à une personne "Anonyme" ;
- Un utilisateur doit pouvoir modifier une tâche sans modifier son auteur ;
- Un utilisateur doit pouvoir supprimer une tâche lui étant associée ;
- Lors de la création ou modification d'un utilisateur on doit pouvoir choisir un rôle 'Admin' ou 'User' ;
- Seuls les administrateurs peuvent accéder au tableau de gestion des utilisateurs ;
- Seuls les administrateurs peuvent supprimer une tâche crée par une personne "Anonyme" ;

## Installation

Pour installer le projet sur votre ordinateur, vous devez ouvrir votre invite de commande dans le dossier souhaité puis entrer cette commande :

```bash
git clone https://github.com/lisavincent31/ToDoList.git
cd ToDoList
composer install
```

Vous avez ensuite accès au dossier dans votre éditeur de code.

### Configuration

Ouvrez le fichier *.env* à la racine du dossier pour configurer votre base de données.
Vous pouvez ensuite créer votre base de données et charger les fixtures en tapant dans votre terminal les commandes suivantes : 

```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```
### Comptes pour le jeu de données

Voici les adresses e-mail et mot de passes pour les utilisateurs créés dans le jeu de données :

- Administrateur : 
    - email => "admin@example.com" 
    - mot de passe => "s3cr3t/<:Adm1n!" ;
- Utilisateur : 
    - email => "user@example.com"
    - mot de passe => "s3cr3t/<:us3r!" ;

### Lancer le projet

Pour lancer le projet, il vous suffit de rentrer la commande : 

```bash
symfony serve:start
```

Puis ouvrez votre navigateur à l'adresse suivante : [127.0.0.1](http://127.0.0.1:8000/)

## Tests

### Couverture de code

Vous trouverez le fichier pour accéder à votre couverture de code dans le chemin relatif suivant : 

**/public/test-coverage/index.html**

Attention, veillez à bien rentrer l'URL complète du chemin d'accès de votre dossier.

Exemple : **C:/Windows/projects/ToDoList/public/test-coverage/index.html**

Vous arriverez directement sur le rapport de couverture du code. Comme vous pouvez le constater, cette couverture est à hauteur de 85%.

## Améliorations à apporter

Voici les axes d'améliorations à apporter rapidement à l'application pour une meilleure expériences utilisateur : 

- une interface plus dynamique et mieux structurée ;
- la création d'un menu serait plus simple que des boutons sur la page ;
- l'ajout de la date de création et de modification sur les tâches ;
- la création d'un tableau de bord utilisateur et administrateur ;
- la possibilité de créer des groupes ou de rendre les tâches privées et publics ;
- ne pas laisser la possibilité à tous les utilisateurs de choisir leurs rôles ;

## Contribution

Vous trouverez à la racine du dossier les fichiers de contributions (français et anglais).

Ces fichiers se nomment : **contribution_FR.mk** et **contribution_EN.mk**

# English version

## Introduction

The aim of this project is to improve an existing ToDoList application by adding the following functionalities :

- Associate a task with the logged-in person or, if applicable, with an ‘Anonymous’ person;
- A user must be able to modify a task without modifying its author;
- A user must be able to delete a task associated with him/her;
- When creating or modifying a user, it must be possible to choose an ‘Admin’ or ‘User’ role;
- Only administrators can access the user management table;
- Only administrators can delete a task created by an ‘Anonymous’ person;

## Installation

To install the project on your computer, you need to open your command prompt in the desired folder and enter this command :

```bash
git clone https://github.com/lisavincent31/ToDoList.git
cd ToDoList
composer install
```

You can then access the folder in your code editor.

### Configuration

Open the *.env* file at the root of the folder to configure your database. You can then create your database and load the fixtures by typing the following commands in your terminal :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```

### Launching the project

To start the project, simply enter the command : 

```bash
symfony serve:start
```

Then open your browser at the following address : [127.0.0.1](http://127.0.0.1:8000/)

## Tests

### Code coverage

You will find the file to access your code coverage in the following relative path : 

**/public/test-coverage/index.html**

Make sure you enter the full URL of the path to your file.

Example : **C:/Windows/projects/ToDoList/public/test-coverage/index.html**

This will take you directly to the code coverage report. As you can see, the coverage is 85%.

## Improvements to be made

Improvements to be made
Here are the areas where we need to make rapid improvements to the application to enhance the user experience:

- a more dynamic and better-structured interface;
- the creation of a menu would be simpler than buttons on the page;
- adding the creation and modification dates to tasks;
- the creation of a user and administrator dashboard;
- the possibility of creating groups or making tasks private or public;
- not allowing all users to choose their roles;

## Contribution

At the root of the folder you will find the contribution files (French and English).

These files are called : **contribution_FR.mk** and **contribution_EN.mk**