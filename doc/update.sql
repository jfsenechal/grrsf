ALTER TABLE `grr_log` CHANGE `AUTOCLOSE` `AUTOCLOSE` BOOLEAN NOT NULL DEFAULT FALSE;
ALTER TABLE ``grr_setting`` DROP PRIMARY KEY;
ALTER TABLE `grr_setting` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE grr_j_user_room CHANGE login login VARCHAR(20) NOT NULL, CHANGE id_room id_room INT NOT NULL;
ALTER TABLE grr_entry CHANGE start_time start_time INT NOT NULL, CHANGE end_time end_time INT NOT NULL, CHANGE entry_type entry_type INT NOT NULL, CHANGE repeat_id repeat_id INT NOT NULL, CHANGE create_by create_by VARCHAR(100) NOT NULL, CHANGE beneficiaire_ext beneficiaire_ext VARCHAR(200) NOT NULL, CHANGE beneficiaire beneficiaire VARCHAR(100) NOT NULL, CHANGE name name VARCHAR(80) NOT NULL, CHANGE type type CHAR(2) DEFAULT NULL, CHANGE option_reservation option_reservation INT NOT NULL, CHANGE moderate moderate TINYINT(1) DEFAULT NULL, CHANGE jours jours TINYINT(1) NOT NULL;
ALTER TABLE grr_setting CHANGE NAME NAME VARCHAR(32) NOT NULL;
CREATE UNIQUE INDEX UNIQ_B09F0A2D68B693B2 ON grr_setting (NAME);
ALTER TABLE grr_j_useradmin_area CHANGE login login VARCHAR(20) NOT NULL, CHANGE id_area id_area INT NOT NULL;
ALTER TABLE grr_log DROP PRIMARY KEY;
ALTER TABLE grr_log CHANGE LOGIN LOGIN VARCHAR(20) NOT NULL, CHANGE START START DATETIME NOT NULL, CHANGE SESSION_ID SESSION_ID VARCHAR(64) NOT NULL, CHANGE REMOTE_ADDR REMOTE_ADDR VARCHAR(16) NOT NULL, CHANGE USER_AGENT USER_AGENT VARCHAR(255) DEFAULT NULL, CHANGE REFERER REFERER VARCHAR(255) DEFAULT NULL, CHANGE AUTOCLOSE AUTOCLOSE TINYINT(1) NOT NULL, CHANGE END END DATETIME NOT NULL;
ALTER TABLE grr_log ADD PRIMARY KEY (START, SESSION_ID);
ALTER TABLE grr_repeat CHANGE start_time start_time INT NOT NULL, CHANGE end_time end_time INT NOT NULL, CHANGE rep_type rep_type INT NOT NULL, CHANGE end_date end_date INT NOT NULL, CHANGE rep_opt rep_opt VARCHAR(32) NOT NULL, CHANGE create_by create_by VARCHAR(100) NOT NULL, CHANGE beneficiaire_ext beneficiaire_ext VARCHAR(200) NOT NULL, CHANGE beneficiaire beneficiaire VARCHAR(100) NOT NULL, CHANGE name name VARCHAR(80) NOT NULL, CHANGE type type CHAR(2) DEFAULT NULL, CHANGE rep_num_weeks rep_num_weeks TINYINT(1) DEFAULT NULL, CHANGE jours jours TINYINT(1) NOT NULL;
ALTER TABLE grr_j_useradmin_site CHANGE login login VARCHAR(40) NOT NULL, CHANGE id_site id_site INT NOT NULL;
ALTER TABLE grr_j_user_area CHANGE login login VARCHAR(20) NOT NULL, CHANGE id_area id_area INT NOT NULL;
ALTER TABLE grr_j_mailuser_room CHANGE login login VARCHAR(20) NOT NULL, CHANGE id_room id_room INT NOT NULL;
ALTER TABLE grr_area_periodes CHANGE id_area id_area INT NOT NULL, CHANGE num_periode num_periode SMALLINT NOT NULL, CHANGE nom_periode nom_periode VARCHAR(100) NOT NULL;
ALTER TABLE grr_utilisateurs CHANGE login login VARCHAR(20) NOT NULL, CHANGE nom nom VARCHAR(30) NOT NULL, CHANGE prenom prenom VARCHAR(30) NOT NULL, CHANGE password password VARCHAR(32) NOT NULL, CHANGE email email VARCHAR(100) NOT NULL, CHANGE statut statut VARCHAR(30) NOT NULL, CHANGE etat etat VARCHAR(20) NOT NULL, CHANGE default_site default_site SMALLINT NOT NULL, CHANGE default_area default_area SMALLINT NOT NULL, CHANGE default_room default_room SMALLINT NOT NULL, CHANGE default_style default_style VARCHAR(50) NOT NULL, CHANGE default_list_type default_list_type VARCHAR(50) NOT NULL, CHANGE default_language default_language CHAR(3) NOT NULL;
ALTER TABLE grr_type_area CHANGE type_name type_name VARCHAR(30) NOT NULL, CHANGE order_display order_display SMALLINT NOT NULL, CHANGE couleur couleur SMALLINT NOT NULL, CHANGE type_letter type_letter CHAR(2) NOT NULL;
ALTER TABLE grr_j_type_area ADD id INT AUTO_INCREMENT NOT NULL, CHANGE id_type id_type INT NOT NULL, CHANGE id_area id_area INT NOT NULL, ADD PRIMARY KEY (id);
ALTER TABLE grr_calendrier_jours_cycle ADD id INT AUTO_INCREMENT NOT NULL, CHANGE DAY DAY INT NOT NULL, CHANGE Jours Jours VARCHAR(20) DEFAULT NULL, ADD PRIMARY KEY (id);
ALTER TABLE grr_area CHANGE area_name area_name VARCHAR(30) NOT NULL, CHANGE access access CHAR(1) NOT NULL, CHANGE order_display order_display SMALLINT NOT NULL, CHANGE ip_adr ip_adr VARCHAR(15) NOT NULL, CHANGE morningstarts_area morningstarts_area SMALLINT NOT NULL, CHANGE eveningends_area eveningends_area SMALLINT NOT NULL, CHANGE resolution_area resolution_area INT NOT NULL, CHANGE eveningends_minutes_area eveningends_minutes_area SMALLINT NOT NULL, CHANGE weekstarts_area weekstarts_area SMALLINT NOT NULL, CHANGE twentyfourhour_format_area twentyfourhour_format_area SMALLINT NOT NULL, CHANGE enable_periods enable_periods VARCHAR(1) DEFAULT 'n' NOT NULL, CHANGE duree_par_defaut_reservation_area duree_par_defaut_reservation_area INT NOT NULL;
ALTER TABLE grr_site CHANGE sitename sitename VARCHAR(50) NOT NULL;
ALTER TABLE grr_entry_moderate CHANGE login_moderateur login_moderateur VARCHAR(40) NOT NULL, CHANGE start_time start_time INT NOT NULL, CHANGE end_time end_time INT NOT NULL, CHANGE entry_type entry_type INT NOT NULL, CHANGE repeat_id repeat_id INT NOT NULL, CHANGE create_by create_by VARCHAR(100) NOT NULL, CHANGE beneficiaire_ext beneficiaire_ext VARCHAR(200) NOT NULL, CHANGE beneficiaire beneficiaire VARCHAR(100) NOT NULL, CHANGE name name VARCHAR(80) NOT NULL, CHANGE option_reservation option_reservation INT NOT NULL, CHANGE moderate moderate TINYINT(1) DEFAULT NULL;
ALTER TABLE grr_j_site_area CHANGE id_site id_site INT NOT NULL, CHANGE id_area id_area INT NOT NULL;
ALTER TABLE grr_calendar ADD id INT AUTO_INCREMENT NOT NULL, CHANGE DAY DAY INT NOT NULL, ADD PRIMARY KEY (id);
ALTER TABLE grr_room CHANGE area_id area_id INT NOT NULL, CHANGE room_name room_name VARCHAR(60) NOT NULL, CHANGE description description VARCHAR(60) NOT NULL, CHANGE capacity capacity INT NOT NULL, CHANGE picture_room picture_room VARCHAR(50) NOT NULL, CHANGE delais_min_resa_room delais_min_resa_room SMALLINT NOT NULL, CHANGE dont_allow_modify dont_allow_modify VARCHAR(1) DEFAULT 'n' NOT NULL, CHANGE order_display order_display SMALLINT NOT NULL, CHANGE delais_option_reservation delais_option_reservation SMALLINT NOT NULL, CHANGE type_affichage_reser type_affichage_reser SMALLINT NOT NULL, CHANGE moderate moderate TINYINT(1) DEFAULT NULL, CHANGE qui_peut_reserver_pour qui_peut_reserver_pour VARCHAR(1) DEFAULT '5' NOT NULL, CHANGE who_can_see who_can_see SMALLINT NOT NULL;
ALTER TABLE grr_overload CHANGE fieldname fieldname VARCHAR(25) NOT NULL, CHANGE fieldtype fieldtype VARCHAR(25) NOT NULL;
