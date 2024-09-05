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
