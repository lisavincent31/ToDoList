# Documentation sur l'authentification d'un utilisateur

## 1. Entité User

L'entité **User** a été créée grâce à la commande suivante : 

```bash
php bin/console make:user
```

Cela crée automatiquement une entité User avec les propriétés suivantes :
- email
- password
- roles

Pour rajouter une propriété à l'entité, il faut taper les commandes suivantes :

```bash
php bin/console make:entity User
```

Puis, suivez les instructions en répondant aux questions :
- nom de la colonne à rajouter
- type
- etc...

## 2. Authentification de l'utilisateur

Les fichiers d'authentification ont été créé grâce au security-bundle de symfony par la commande :

```bash
php bin/console make:auth
```
### 2.1 : Le formulaire pour l'authentification

Pour modifier les champs du formulaire, il vous faut rajouter les champs dans le fichier **App\Form\UserType.php**. Attention à ne pas oublier le type du champs pour plus de sécurité.

### 2.2 : Le template

Pour modifier la mise en forme du formulaire d'authentification, vous pouvez modifier le fichier **templates\security\login.html.twig**.

### 2.3 : Le controller

Les fonctions de *login()* et *logout()* se trouvent dans le fichier **App\Controller\SecurityController.php**. C'est ici que vous définissez le template pour la fonction d'authentification.

### 2.4 : Sécurité

Pour gérer l'accès aux pages, il faut vous rendre sur le fichier **config\packages\security.yaml**. 
Dans ce fichier, vous trouverez le paramètre *access_control*. Pour le moment, seuls les **administrateurs** ont accès aux routes "^/users$". Toutes les autres routes sont accessibles à tous les utilisateurs.

## 2.5 : Fonction d'authentification

L'authentification de l'utilisateur passe par le fichier : **App\Security\LoginAuthenticator.php**. 

Dans ce fichier, vous pouvez modifier la route où est renvoyé l'utilisateur si l'authentification a réussi dans la fonction *onAuthenticationSuccess()*.

