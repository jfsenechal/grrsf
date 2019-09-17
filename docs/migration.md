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


## Détails du script de migration

**1) On troncate les tables**

On repart d'une db vierge

    $purger = new ORMPurger($this->entityManager);  
    $purger->purge();
    
**2) Enregistrements de la table repeat dans un fichier temporaire**  

Pour des raisons de perfomances  

     $fileHandler = file_get_contents(__DIR__.'/../../var/cache/repeat.json');
     
**3) Importation des Domaines(Area) et ressources(Room)**

Les listes sont enregistrées dans les propriétés de la classe pour réutilisation ultérieur

     $this->areas = ...
     $this->rooms = ...
     
La résolution ancien room et nouvelle est room est sauvegardé

    $this->resolveRooms[$data['id']] = $room;
     
**4) Importation des types d'entrées**

J'enregistre une référence depuis la lettre vers les nouvelles instances

    $this->resolveTypeEntries[$data['type_letter']] = $type;

**5) Importation des utilisateurs**

Pour les utilisateurs locaux, pour le moment le mp est le même

**6) Importation des Users Area**

**7) Importation des Users Room**

**8) Importation des entries**

Je les regroupe par repeat_Id 

    $entries = $this->migrationUtil->groupByRepeat($entries);
       
 [Voir le nouveau schéma de la db](schemadb.md)
 
 **9) Création des répétitions**
 
 Je génère les dates de répétitions suivant les données dans la table périodicity
 
 