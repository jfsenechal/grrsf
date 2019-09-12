## Les autorisations

### Administrator

**Un administreur de domaine (Area) peux:** 
- Modifier/supprimer le domaine
- Ajouter/modifier/supprimer des ressources
- Gérer toutes les entrées des différentes ressources sans restriction

**Un administrateur de ressource (Room) peux:**
- Modifier/supprimer la ressource
- Gérer toutes les entrées de la ressource sans restriction

### Manager

**Un manager de domaine (Area) peux:** 
- Gérer toutes les entrées des différentes ressources sans restriction

**Un manager de ressource (Room) peux:**
- Gérer toutes les entrées de la ressource sans restriction

### Examples

Area1
  Room1

| User      | Area   | Room    |   is_area_administrator     | is_resource_administrator   |
| ----------|--------|---------|: -------------------------: | --------------------------: |
| Bob       |  Area1 |         |      true                   | false                       |
| Alice     |  Area1 |         |      false                  | true                        |
| Joseph    |  Area1 |         |      false                  | false                       |
| Fred      |        |  Room 1 |      false                  | true                        |
| Kevin     |        |  Room 1 |      false                  | false                       |


- Bob est administrateur de Area1
- Alice est manager de Area1
- Joseph peux encoder des entrées dans toutes les ressources de Area1 sans restrictions
- Fred est administrateur de Room1
- Kevin est manager de Room1

### Par héritage des droits

Bob est manager de Area1 et administrator et manager de Room1
Alice est manager de Room1
