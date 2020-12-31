# Contribuer
## Nommer les commits

Le nommage des commits doit se faire selon ce modèle :
```
<type>(<portée>): <sujet>
[ligne vide]
[<description>]
[ligne vide]
[<footer>]
```

 - **Type**: ce à quoi touche le commit parmi cette liste :
    - **build**: Systèmes de builds (webpack, npm, sass, etc...)
    - **ci**: Intégration continue (Travis, PrettyCI, etc..)
    - **docs**: Documentation (.md)
    - **func**: Fonctionnalité
    - **views**: Vues
    - **db**: Migrations/Seeds (structure/contenu de la base de données)
    - **stylesheet**: Feuilles de style (scss/css)
    - **fix**: Correction de bug
    - **perf**: Amélioration des performances
    - **refactor**: Modification du code qui ne change rien au fonctionnement
    - **style**: Changement du style du code
    - **test**: Tests unitaires (PHPUnit, Jest, etc...)
    - **other**: Tout ce qui ne rentre pas dans les catégories précédentes

**Attention:** Si vos modification se portent sur plusieurs de ces types, merci de faire un commit séparé pour chacun
des type.

 - **Portée**: la partie de l'application qui est affectée (cette information est optionnelle)
 - **Sujet**: description claire de _tous_ les changements:
    - En utilisant l'impératif présent (_"modifie", et non pas "modification"_),
    - En français ou en anglais,
    - Pas de majuscule au début,
    - Pas de "." à la fin de la fin.
 - **Description**: Détaille les motivations derrière le changement. Les règles sont les mêmes que pour la partie Sujet.
 - **Footer**: Détaille les changements importants (Breaking Changes) et référence les issues/Pr fermées par le commit,
 voir [fermer des issues avec un commit](https://help.github.com/en/github/managing-your-work-on-github/closing-issues-using-keywords).
