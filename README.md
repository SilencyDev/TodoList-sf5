# TodoList-sf5

Base du projet #8 : Améliorer un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

## Installation
1. Cloner ou télécharger le projet :
```
    git clone https://github.com/SilencyDev/TodoList-sf5.git
```
2. Configurer les variables d'environnement dans le fichier 'env.local' pour la production/dev et 'env.test' pour les tests.
3. Télécharger et installer les packages avec composer(https://getcomposer.org/download/) via le terminal :
```
    composer install
```
4. Créer la base de donnée depuis le terminal :
```
    php bin/console doctrine:database:create
```
5. Créer les tables via les migrations :
```
    php bin/console doctrine:migrations:migrate
```
6. (Optionnel) Installer les fixtures pour avoir un jeu de données :
```
    php bin/console doctrine:fixtures:load --env=dev
```
7. (Optionnel) Créer la base de donnée, les migrations et les fixtures de test depuis le terminal :
```
    php bin/console doctrine:database:create --env=test
    php bin/console doctrine:migrations:migrate --env=test
    php bin/console doctrine:fixtures:load --env=test 
```