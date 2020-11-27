# Authentification

La sécurité concernant l'authentification est configurée dans le fichier `config/packages/security.yaml`.
Toutes les configurations `yaml` citées ci-dessous possèdent le même parent `security :`.
Voici la [documentation de Symfony](https://symfony.com/doc/5.1/security.html) sur le composant security.

## Prérequis
Creez l'entité User(`src/Entity/User.php`) qui représente l'utilisateur connecté et doit implémenter l'interface `UserInterface`. 

## Encoders
`encoders` détermine l'entité `App\Entity\User` ainsi que la méthode algorithmique souhaitée pour celle-ci.
En mode `auto` symfony choisi l'algorithm qu'il utilisera sur l'entité `App\Entity\User` et enverra la methode choisie à `UserPasswordEncoderInterface` pour le hachage du mot de passe.
```yaml
# config/packages/security.yaml
    encoders:
        App\Entity\User:
            algorithm: auto
```

## Providers
`providers` va nous permettre d'indiquer où se situe notre entité `App\Entity\User`.
`property` désigne la propriété utilisée comme identifiant pour les formulaires d'authentification.
```yaml
# config/packages/security.yaml
    providers:
        users:
            entity:
                class: App\Entity\User
                property: username
```

## Firewalls
`firewalls` va définir comment les utilisateurs vont être authentifiés.
`dev` correspond à l'environnement de développement et utilise `pattern : ^/(_(profiler|wdt)|css|images|js)/` afin de ne pas bloquer les outils de développement comme le profiler.
`main` englobe la totalité de l'application.
`anonymous: true` authorise les demandes non identifiées comme l'accès à la page de d'authentification.
`form_login:` 
    `check_path:` route pour la connexion utilisateur.
    `username_parameter:` propriété utilisée comme identifiant pour les formulaires d'authentification.
`logout:`
    `path` route pour la déconnexion utilisateur.
    `target` route pour la redirection de déconnexion.
```yaml
# config/packages/security.yaml
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            anonymous: true
            form_login:
                check_path: login
                username_parameter: _username
            logout:
                path: /logout
                target: /
```

## Role_Hierarchy
role_hierarchy défini les rôles supplémentaires que peut posséder un rôle.
```yaml
# config/packages/security.yaml
    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
```

## Access_Control
access_control va définir les limitations d'accès à certaines parties du site sous la forme suivante :
`- { path: <route>, roles: <roles-authorisés>}`
```yaml
# config/packages/security.yaml
    access_control:
        - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/users/create, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/users, roles: ROLE_ADMIN }
        - { path: ^/, roles: ROLE_USER }
```

access_control dans les contrôleurs est possible par la fonction `IsGranted` via les annotations.
Exemple :
```php
// src/Controller/AdminController.php
// ...

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
* Require ROLE_ADMIN for *every* controller method in this class.
*
* @IsGranted("ROLE_ADMIN")
*/
class AdminController extends AbstractController
{
    /**
    * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
    */
    public function adminDashboard()
    {
        // ...
    }
}
```
ou encore via les Voters :
```php
// src/Controller/TaskController.php
// ...

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
* Require ROLE_ADMIN for *every* controller method in this class.
*
* @IsGranted("ROLE_ADMIN")
*/
class TaskController extends AbstractController
{
    /**
    * Require the ROLE determined by the custom voter for the DELETE action.
    * See more at https://symfony.com/doc/current/security/voters.html
    */
    public function DeleteThatTaskAction()
    {
        $this->denyAccessUnlessGranted('DELETE', $task);
    }
}
```