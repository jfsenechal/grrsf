Reverse engine

grr_log
A faire avant mode console: 

`ALTER TABLE `grr_log` CHANGE `AUTOCLOSE` `AUTOCLOSE` BOOLEAN NOT NULL DEFAULT FALSE;`


`ALTER TABLE ``grr_setting`` DROP PRIMARY KEY;`
`ALTER TABLE `grr_setting` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);`


ALTER TABLE `grr_log` CHANGE `START` `START` DATETIME NOT NULL, CHANGE `END` `END` DATETIME NOT NULL;


grr_calendar
ALTER TABLE `grr_calendar` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);

grr_calendrier_jours_cycle
ALTER TABLE `grr_calendrier_jours_cycle` ADD `id` INT NOT NULL AUTO_INCREMENT AFTER `Jours`, ADD PRIMARY KEY (`id`);

grr_j_type_area
ALTER TABLE `grr_j_type_area` ADD `id` INT NOT NULL AUTO_INCREMENT AFTER `id_area`, ADD PRIMARY KEY (`id`);

Après 
grr_setting name en tant que key 
caractères vont pas dans path
j'ai ajouté un champ id