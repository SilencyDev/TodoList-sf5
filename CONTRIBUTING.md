# Contribution

Veuillez prendre connaissance de ce document afin de suivre le processus de contribution.

## Issues
[Issues](https://github.com/Silencydev/todolist-sf5/issues) est le canal pour les rapports de bug, les nouvelles fonctionnalités ou pour soumètre une `pull requests`, cependant veuillez a bien respecter les restrictions suivantes :
  * N'utilisez par ce canal pour vos demandes d'aide personnelles (utilisez [Stack Overflow](http://stackoverflow.com/)).
  * Il est interdit d'insulter ou d'offenser d'une quelconque manière en commentaire d'une `issue`. Respectez les opinions des autres, et restez concentré sur la discussion principale.

## Rapport de bug
Un bug est une erreur concrète, causée par le code présent dans ce `repository`.

Guide :
1.  Assurez-vous de ne pas créer un rapport déjà existant via [le système de recherche](https://github.com/Silencydev/todolist-sf5/issues).
2.  Vérifiez si le bug est corrigé, en essayant la dernière version du code sur la branche `master` ou `dev`.
3.  Isolez le problème afin de créer un scénario de test simple et identifiable.

## Nouvelle fonctionnalité
Il est toujours apprécié de proposer de nouvelles fonctionnalités. Cependant, prenez le temps de réfléchir, assurez-vous que cette fonctionnalité correspond bien aux objectifs du projet.

## Test coverage (Optionnel)
Si possible, veuillez inclure les tests de vos fonctionnalités.

Pour effectuer une analyze de la couverture des tests :
```
./vendor/bin/simple-phpunit --coverage-html docs/test-coverage
```

## Pull request
Elles doivent rester dans le cadre du projet et ne doit pas contenir de `commits` non lié au projet. Veuillez demander avant de poster votre `pull request`.

Suivez ce processus afin de proposer une `pull request` qui respecte les bonnes pratiques :

1.  Mettez votre code à la norme PSR-12 automatiquement via `code sniffer` (corrigez les erreurs non résolues par la commande `./vendor/bin/phpcbf`) :
```
./vendor/bin/phpcs
./vendor/bin/phpcbf
```

2.  Testez vos modifications via les tests :
```
./vendor/bin/simple-phpunit
```

3.  [Fork](http://help.github.com/fork-a-repo/) le projet, clonez votre `fork` et configurez les `remotes`:
```
git clone https://github.com/<your-username>/<repo-name>
cd todolist-sf5
git remote add upstream https://github.com/Silencydev/todolist-sf5
```

4.  Récupérez les dernières modifications depuis `upstream`:
```
git checkout master
git pull upstream master

git checkout dev
git pull upstream dev
```

5.  Créez une nouvelle branche qui contiendra votre fonctionnalité, modification ou correction:
* Pour une nouvelle fonctionnalité ou modification:
```
git checkout dev
git checkout -b feature/<feature-name>
```

* Pour une nouvelle correction:
```
git checkout master
git checkout -b hotfix/<feature-name>
```

6.  `Commit` vos changements via la convention de nommage suivante :
```
<type>: <subject> <body>
```

Types:
* **build**: Changements qui ont un effet sur le système (installation de nouvelles dépendances, composer, environnements, ...)
* **ci**: Configuration de l'intégration continue
* **docs**: Modifications de la documentation
* **feat**: Nouvelle fonctionnalité
* **fix**: Correction (`hotfix`)
* **perf**: Modification du code qui optimise les performances
* **refactor**: Toute modification du code dans le cadre d'un refactoring
* **style**: Corrections propres au coding style (PSR-12)
* **test**: Ajout d'un nouveau test ou correction d'un test existant

7.  Push de la branche sur votre `repository` :
```
git push origin <branch-name> 
```

8.  Ouvrez une nouvelle `pull request` avec un titre et une description précises.