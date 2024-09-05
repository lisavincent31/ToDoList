# Contribution au développement de l'application (FR)

Chers développeurs, ce document a pour but de vous présenter les étapes clés pour contribuer à l'amélioration et au développement de l'application ToDo&Co.

## 1. Création d'issues

Avant de débuter le développement de nouvelles fonctionnalités, je vous suggère fortement de créer une nouvelle issue sur GitHub. 

Le nom de l'issue devra être numéroté et porter le nom de la branche que vous allez créer. 

Exemple : **8. nom_de_la_branche**

Chaque issue devra comprendre la liste des tâches à effectuer pour atteindre votre objectif. Les tâches devront être claires et rapides à réaliser. Je vous conseille d'effectuer un commit après avoir effectuer une tâche.

Exemple : **Création d'un nouveau controller**, **Création d'une fonctionnalité**

## 2. Nouvelle branche

Vous ne devez jamais développer directement sur la branche principale **master**. Il est recommandé de créer une nouvelle branche en suivant les instructions ci-dessous : 

1. Vérifiez que vous êtes sur la branche la plus avancée du projet ou sur **master** :

```bash
git branch
git checkout master
```

2. Créez votre nouvelle branche en suivant la nomenclature suivante : **type_de_modification/objectif_nom_date**

Exemple : Si vous devez créer un tableau de bord utilisateur
**feature/user_dashboard_VINCENT_092024**

Voici les différents types possibles :
- tests : implémentation de tests
- feature : implémentation de nouvelles fonctionnalités
- bugfix : correctifs de bug sur l'application
- doc : modification du readme, ajout de documentation

```bash
git checkout -b nom_de_la_branche
```

## 3. Sauvegarde des modification

Tous les jours, vous devez sauvegarder votre travail sur GitHub en effectuant un commit.
Chaque commit doit apporter des modifications uniques et logiques. Le message de votre commit devra décrire vos modifications de façon claire.

Pour cela, vous devez taper les commandes suivantes dans votre terminal :

```bash
git status
git add nom_des_fichiers
git commit -m "modifications apportées"
git push -u origin nom_de_votre_branche
```

Tous les matins, il est recommandé de récupérer toutes les sauvegardes effectuées sur les différentes branches. Il suffit de taper la commande :

```bash
git pull
```

## 4. Pull Request

A la fin de vos modifications, vous devez effectuer un pull request de votre branche vers la branche **master**. La description de votre pull request devra être précise sur les modifications apportées, les tests à effectuer. N'hésitez pas à rappeler également le nom de l'issue liée à vos modifications.

Au moins un autre développeur devra obligatoirement vérifier votre pull request avant la fusion des branches. 

Assurez vous que tous les tests passent avant la fusion des deux branches.