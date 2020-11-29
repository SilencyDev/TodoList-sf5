# TodoList-sf5

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

## Installation

1.  Clonez ou téléchargez le projet :
```sh
git clone https://github.com/SilencyDev/TodoList-sf5.git
```

2.  Configurez les variables d'environnement dans le fichier 'env.local' pour la production/dev et 'env.test' pour les tests.
3.  Téléchargez et installez les packages avec [Composer](https://getcomposer.org/download/) via le terminal :
```sh
composer install
```

4.  Créez la base de donnée depuis le terminal :
```sh
php bin/console doctrine:database:create
```

5.  Créez les tables via les migrations :
```sh
php bin/console doctrine:migrations:migrate
```

6.  (Optionnel) Installez les fixtures pour avoir un jeu de données :
```sh
php bin/console doctrine:fixtures:load --env=dev
```

7.  (Optionnel) Créez la base de donnée, les migrations et les fixtures de test depuis le terminal :
```sh
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --env=test
php bin/console doctrine:fixtures:load --env=test 
```