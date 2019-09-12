## Installation

### Prérequis

**Php**
  7.2 ou plus

**Base de données**
- Maria Db 
- Mysql
- PostrgreSQL
- sqlite 

### Téléchargement

    git clone https://github.com/jfsenechal/grrsf.git

ou télécharez et dézippé 

https://github.com/jfsenechal/grrsf/archive/master.zip

### Installation des dépendances

A la racine de votre dossier : 

    composer install
    
[Vous n'avez pas composer sur votre serveur ?](https://getcomposer.org/download/) 

### Configuration de la base de données

Copié coller le fichier .env

    cp .en .env.local  
    
 Et adapté le paramètre suivant votre serveur 
 
    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

### Initialisation des données par défaut

Exécuter la commande 

    php bin/console grr:install-data 

Cela va des domaines, ressources, types d'entrées et un compte administrateur avec le mot de passe "homer"

### Lancer Grr

Lancé le serveur web intégré

    php bin/console server:start

Il vous reste a ouvrir l'url http://127.0.0.1:8000

**Note, pour une version en production, finalisez la configuration de votre serveur web** 

https://symfony.com/doc/current/setup/web_server_configuration.html