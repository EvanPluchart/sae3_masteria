<h1 style='color:red'>Projet : site web pour le master IA de l'URCA</h1>

## Membres du projet :

- XXXX Gaétan
- PLUCHART Evan, `pluc0003`
- XXXX Alban
- XXXX Lucie

____

## Introduction du projet

___

> L'université de Reims Champagne-Ardenne a besoin d'un site pour leur master informatique parcours IA. Nous sommes ici chargés de la création de ce site. Ce projet est composé en 3 étapes :
>  - La réalisation du [cahier des charges](https://docs.google.com/document/d/1SNjHnTAs2dZm_Wvjl6REXTSZmRf3SR8BpaIwg26iOUk/edit?usp=sharing "Lien vers le drive du cahier des charges")
>  - La réalisation du [rapport d'analyse](https://docs.google.com/document/d/1bvXWJZBKu2fY5icIPTMaX61Fq6h1UfLQC4F78m-e2SE/edit?usp=sharing "Lien vers le drive du rapport d'analyse") à l'aide de la méthode SCRUM
>  - La partie développement, permettant de mettre en place les diagrammes créés auparavant.
___

## Outils utilisés

____

- <abbr title="Hypertext Preprocessor">**PHP**</abbr>
  - Le projet sera fait sur [**Symfony**](https://symfony.com/download)
  - [**Composer**](https://getcomposer.org/) sera utilisé pour la gestion des bibliothèques
  - [**Codeception**](https://codeception.com/) pour les tests unitaires
  - [**PHP-CS-FIXER**](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer#user-content-editor-integration) servira à vérifier le respect des normes de PHP
- <abbr title="Hyper Text Markup Language">**HTML**</abbr>
- <abbr title="Cascading Style Sheets">**CSS**</abbr>
  - [**Bootstrap**](https://getbootstrap.com/)
- <abbr title="Syntactically Awesome Style Sheet">**SCSS**</abbr>
- **Javascript**
___
## Installation / Configuration
___

### Installation Symfony

Il faut d'abord installer l'executable "symfony" avec la commande :

```
wget https://get.symfony.com/cli/installer -O - | bash
```

Il faut ensuite ajouter symfony au PATH ainsi que vérifier le bon fonctionnement de symfony avec

```
symfony self:version
```

Ensuite, il faut contrôler la compatibilité du système avec la commande

```
symfony check:requirements  --verbose
```

Enfin, on peut créer notre projet symphony

```
symfony --version 5.4 --webapp new symfony-contacts
```

Pour finir, on lance le serveur Web local avec la commande

```
symfony serve
```

### Installation par `Composer`

Lancer `composer install` pour installer [PHP Coding Standards Fixer](https://cs.symfony.com/) et le configurer dans PhpStorm (le fichier `.php-cs-fixer.php` contient les règles personnalisées basées sur la recommandation [PSR-12](https://www.php-fig.org/psr/psr-12/))

### Configurer PhpStorm

Configurer l'intégration de PHP Coding Standards Fixer dans PhpStorm en fixant le jeu de règles sur `Custom` et en désignant `.php-cs-fixer.php` comme fichier de configuration de règles de codage. 

### Base de données

Copier le fichier `.env` en `.env.local` et remplacez la ligne MyPDO avec cette ligne : `DATABASE_URL="mysql://[phpmyadmin_name]:[mdp]@mysql:3306/[nom_bdd_dans_phpMyAdmin]?serverVersion=mariadb-10.2.25"` pour ajuster la configuration du serveur de base de données.

### Scripts

    start: Démarre le serveur symfony sans restriction de durée.,
    test:cs: Démarre un test cs-fixer permettant de montrer les erreurs de syntaxe dans le projet.,
    fix:cs: Fixe les problèmes de syntaxes du projet.,
    test:codecept: Regénère une base de donnée de test en y insérant des données puis execute les tests de codeception.,
    test: Démarre les tests de codeception et de composer à la suite.,
    db: Génère une base de donnée de test.

### Serveur Web local


Lancez le serveur Web local avec cette commande :

```bash
composer start
```

Naviguez alors à partir de cette adresse : <http://localhost:8000/>

### Style de codage

Le code suit la recommandation [PSR-12](https://www.php-fig.org/psr/psr-12/) :
- il peut être reformaté automatiquement avec `composer fix:cs`
### Traduction

Pour ajouter une traduction (en anglais):
 - ajouter les balises twig `{% trans %}...{% endtrans %}` 
sur du texte uniquement (pas de balise html) dans les vues à traduire

 - lancez la commande 
```bash
composer translate:en
``` 
   pour mettre à jour le fichier de traduction 
   - enfin accédez au fichier de traduction "translation/messages+intl-icu.en.xlf"
et modifier le contenu des balises `<target>` avec la traduction du contenu des balises
`<source>`
