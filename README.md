# TRT-conseil

<h2>Pourquoi ?</h2>

Cette application web est le résultat d'un projet d'évualuation dans le cadre du titre professionel, que j'ambitionne d'obtenir .
TRT Conseil est une agence de recrutement spécialisée dans l’hôtellerie et la restauration. Fondée en
2014, la société s’est agrandie au fil des ans et possède dorénavant plus de 12 centres dispersés aux
quatre coins de la France.
La crise du coronavirus ayant frappée de plein fouet ce secteur, la société souhaite progressivement
mettre en place un outil permettant à un plus grand nombre de recruteurs et de candidats de trouver leur
bonheur.
TRT Conseil désire avoir un produit minimum viable afin de tester si la demande est réellement présente.
L'agence souhaite proposer pour l'instant une simple interface avec une authentification.

<h2>Comment ?</h2>

Le back-end du projet est construit grâce à symphony, tandis que le front a été conçu par React via webpack-encore.

<h2>Deploiement en local:</h2>

<h3>Assurez-vous d'avoir Node.js et Composer installés avant de continuer.</h3>

<h4>1. Installer les dépendances du back-end en tapant la commande :</h4>

`$ composer install`

<h4>2. Installez les dépendances du front-end :</h4>

`$ npm install`

<h4>3. Créez la base de données :</h4>

`$ php bin/console doctrine:database:create`

<h4>4. Effectuez une migration :</h4>

`$ php bin/console doctrine:migrations:migrate`

<h4>5. Lancement du serveur symfony :</h4>

`$ php bin/console server:run` ou `$ symfony server:start` si la commande `symfony`est déjà installée.

 <h4>6. Générer les clés jwt :</h4>

`$ php bin/console lexik:jwt:generate-keypair`

<h4>7. Lancement de la construction du front :</h4>

`$ npm run dev-server`

 <h3>Une ligne de commande a été créée pour configurer un administrateur :</h3>

`$ php bin/console app:create-admin <firstname> <lastname> <email> <password>`

Les consultants seront crées par l'administrateur, un mot de passe sera généré aléatoirement lors de la création et sera modifiable par la suuite.
