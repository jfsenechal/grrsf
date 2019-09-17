## Tests

Il sont dans le répertoire [tests](/tests)  
Les fixtures sont dans le répertoire [Fixtures](/src/Fixtures)

Ils peuvent être lancé avec la commande 

    vendor/bin/simple-phpunit tests

#####!! Attention à ne pas exécuter sur une installation en production sous peine d'effacer toute la base de donnéées !!

Sous symfony on peut paramètrer la base de données sous diffent environnement (dev, test, prod) :  
https://symfony.com/doc/current/configuration.html#managing-multiple-env-files

J'ai aussi créé un compte chez Travis https://travis-ci.org/

Ca permet de tester l'application avec différentes verstions de php  
et différent serveur sql

[Configuration Travais](/.travis.yml)



