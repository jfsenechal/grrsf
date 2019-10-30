Feature: Manage entries with periodicity

  Test par jour
  Test par mois, même date
  Test par mois, même date
  Test par semaine
  Test par année

  Scenario: Add entry with periodicity every day with bad end time
    Given I am logged in as an admin
    Given I am on homepage
    When I follow "15"
    When I follow "Ajouter une entrée"
    And I fill in "entry_with_periodicity[name]" with "My reservation repeated"
    And I select "2" from "entry_with_periodicity_startTime_date_day"
    And I select "9" from "entry_with_periodicity_startTime_date_month"
    And I select "2019" from "entry_with_periodicity_startTime_date_year"
    And I fill in "entry_with_periodicity[duration][time]" with "3"
    And I select "Heure(s)" from "entry_with_periodicity_duration_unit"
    #1 => Chaque jour
    And I select "1" from "entry_with_periodicity[periodicity][type]"
    And I fill the periodicity endTime with the date 1/9/2019
    And I press "Sauvegarder"
    #Then print last response
    Then I should see "La date de fin de la périodicité doit être plus grande que la date de fin de la réservation"

  Scenario: Add entry with periodicity every day with bad end time
    Given I am logged in as an admin
    Given I am on homepage
    When I follow "15"
    When I follow "Ajouter une entrée"
    And I fill in "entry_with_periodicity[name]" with "My reservation repeated"
    And I select "2" from "entry_with_periodicity_startTime_date_day"
    And I select "9" from "entry_with_periodicity_startTime_date_month"
    And I select "2019" from "entry_with_periodicity_startTime_date_year"
    And I fill in "entry_with_periodicity[duration][time]" with "3"
    And I select "Jour(s)" from "entry_with_periodicity_duration_unit"
    #1 => Chaque jour
    And I select "1" from "entry_with_periodicity[periodicity][type]"
    And I fill the periodicity endTime with the date 10/9/2019
    And I press "Sauvegarder"
    #Then print last response
    Then I should see "La durée ne peut excéder une journée pour une répétition par jour"

  Scenario: Add entry with periodicity every day
  Date de début : 2 septembre 2019
  Durée: 2.5 heures
  Date de fin de la périodicité: 5 septembre 2019
    Given I am logged in as an admin
    Given I am on homepage
    When I follow "15"
    When I follow "Ajouter une entrée"
    And I fill in "entry_with_periodicity[name]" with "My reservation repeated"
    And I select "2" from "entry_with_periodicity_startTime_date_day"
    And I select "9" from "entry_with_periodicity_startTime_date_month"
    And I select "2019" from "entry_with_periodicity_startTime_date_year"
    And I fill in "entry_with_periodicity[duration][time]" with "3"
    And I select "Heure(s)" from "entry_with_periodicity_duration_unit"
    #1 => Chaque jour
    And I select "1" from "entry_with_periodicity[periodicity][type]"
    And I fill the periodicity endTime with the date 5/9/2019
    And I press "Sauvegarder"
    Then print last response
    Then I should see "My reservation repeated"
    Then I should see "mardi 3 septembre 2019 à 08:00"
    Then I should see "mercredi 4 septembre 2019 à 08:00"
    Then I should see "jeudi 5 septembre 2019 à 08:00"
