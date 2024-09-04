# ToDoList : OpenClassrooms Project n° 8

## 1. Introduction

Ce projet vise à améliorer une application de ToDoList existante en y ajoutant les fonctionnalités suivantes :

- Associer une tâche à la personne connectée ou le cas échéant à une personne "Anonyme" ;
- Un utilisateur doit pouvoir modifier une tâche sans modifier son auteur ;
- Un utilisateur doit pouvoir supprimer une tâche lui étant associée ;
- Lors de la création ou modification d'un utilisateur on doit pouvoir choisir un rôle 'Admin' ou 'User' ;
- Seuls les administrateurs peuvent accéder au tableau de gestion des utilisateurs ;
- Seuls les administrateurs peuvent supprimer une tâche crée par une personne "Anonyme" ;

## 2. Installation

Pour installer le projet sur votre ordinateur, vous devez ouvrir votre invite de commande dans le dossier souhaité puis entrer cette commande :

```bash
git clone https://github.com/lisavincent31/ToDoList.git
cd ToDoList
```

Vous avez ensuite accès au dossier dans votre éditeur de code.

### 2.1. Configuration

Ouvrez le fichier *.env* à la racine du dossier pour configurer votre base de données.
Vous pouvez ensuite créer votre base de données et charger les fixtures en tapant dans votre terminal les commandes suivantes : 

```bash
php bin/console doctrine:database:create
php bin/console doctrine:fixtures:load
```

### 2.2 Lancer le projet

Pour lancer le projet, il vous suffit de rentrer la commande : 

```bash
symfony serve:start
```

Puis ouvrez votre navigateur à l'adresse suivante : 127.0.0.1

## 3. Tests