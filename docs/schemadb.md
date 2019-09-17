### Voici les principaux changement de la base de données

#### Area

**access** => **is_restricted** est devenu un boolean (0,1)  
**resolution_area** => **time_interval** passé de secondes en minutes  
**display_days** => **days_of_week_to_display** array()
**twentyfourhour_format_area** => **is_24_hour_format** boolean  

#### Room

**Les champs suivants sont devenus des boolean**

* ShowFicRoom
* ShowComment
* AllowActionInPast
* DontAllowModify
* Moderate
* ActiveRessourceEmpruntee

**who_can_see** => 	**rule_to_add**

#### Entry

**startTime** => DateTime   
**endTime** => DateTime  
**timestamp** => **createdAt** => DateTime

#### Tables pour les répétitions

La nouvelle structure est par "Entry" 
* 1 entrée dans la table entry
* 1 entrée dans la table periodicity 
* x entrées dans la table "periodicity_days"  


