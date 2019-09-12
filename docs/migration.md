## Migration

### Procédure

Les changements de la base de données sont trop important que pour migrer
celle ci directement.

Donc la migration consistera a synchroniser vers la nouvelle installation de Grr

La migration va se dérouler en plusieurs étapes :

### 1. Installer le nouveau Grr

Il est nécessaire d'avoir un nouveau Grr installé et fonctionel

Consulter la [page installation](installation.md)

#### 2. Accès aux données de l'ancien Grr

Les données seront accessible via un flux en json.

Pour ce faire télécharger l'archive suivante et décompressée à  la racine de votre ancien Grr

Vous aurez un dossier migration contenant les flux.  
Ces données sont protégées.

### 3. Lancer la commande de migration

Placez vous à la racine de votre nouvelle installation  
Et exécutez la commande suivante avec les paramètres

    bin/console grr:migration 'https://anciengrr.domain.be' admin

Indiquez l'adresse de votre ancien grr et le login d'un compte administrateur **local**  
Le mot de passe de celui-ci vous sera demandé.

Vous pouvez également indiquer le mot de passe en dernier paramètre de la ligne de commande

Suivez la progression de la synchronisation et les éventuels messages d'erreur.
