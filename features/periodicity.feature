Feature: Manage entries with periodicity

  Test par jour
  Test par mois, même date
  Test par mois, même date
  Test par semaine
  Test par année

  Background:
    Given I am logged in as an admin
    Given I am on homepage
    When I follow "15"
    When I follow "Ajouter une entrée"
    And I fill in "entry_with_periodicity[name]" with "My reservation repeated"

  Scenario: Add entry with periodicity every day with bad end time
    And I fill the entry startTime with the date 2/9/2019
    And I fill in "entry_with_periodicity[duration][time]" with "3"
    And I select "Heure(s)" from "entry_with_periodicity_duration_unit"
    #1 => Chaque jour
    And I select "1" from "entry_with_periodicity[periodicity][type]"
    And I fill the periodicity endTime with the date 1/9/2019
    And I press "Sauvegarder"
    #Then print last response
    Then I should see "La date de fin de la périodicité doit être plus grande que la date de fin de la réservation"

  Scenario: Add entry with periodicity every day with bad end time
    And I fill the entry startTime with the date 2/9/2019
    And I fill in "entry_with_periodicity[duration][time]" with "3"
    And I select "Jour(s)" from "entry_with_periodicity_duration_unit"
    #1 => Chaque jour
    And I select "1" from "entry_with_periodicity[periodicity][type]"
    And I fill the periodicity endTime with the date 10/9/2019
    And I press "Sauvegarder"
    #Then print last response
    Then I should see "La durée ne peut excéder une journée pour une répétition par jour"

  Scenario: Add entry with periodicity every day
    And I fill the entry startTime with the date 2/9/2019
    And I fill in "entry_with_periodicity[duration][time]" with "3"
    And I select "Heure(s)" from "entry_with_periodicity_duration_unit"
    #1 => Chaque jour
    And I select "1" from "entry_with_periodicity[periodicity][type]"
    And I fill the periodicity endTime with the date 5/9/2019
    And I press "Sauvegarder"
    Then I should see "My reservation repeated"
    Then I should see "mardi 3 septembre 2019 à 08:00"
    Then I should see "mercredi 4 septembre 2019 à 08:00"
    Then I should see "jeudi 5 septembre 2019 à 08:00"

  Scenario: Add entry with periodicity every month with end time too short
    And I fill the entry startTime with the date 2/9/2019
    And I fill in "entry_with_periodicity[duration][time]" with "3"
    And I select "Heure(s)" from "entry_with_periodicity_duration_unit"
    #1 => Chaque mois meme jour
    And I select "3" from "entry_with_periodicity[periodicity][type]"
    And I fill the periodicity endTime with the date 5/9/2019
    And I press "Sauvegarder"
    Then I should see "La date de fin de la périodicité doit au moins dépasser d'un mois par rapport à la de de fin de la réservation"

  Scenario: Add entry with periodicity every month with same day
    And I fill the entry startTime with the date 2/9/2019
    And I fill in "entry_with_periodicity[duration][time]" with "3"
    And I select "Heure(s)" from "entry_with_periodicity_duration_unit"
    #1 => Chaque mois meme jour
    And I select "3" from "entry_with_periodicity[periodicity][type]"
    And I fill the periodicity endTime with the date 5/12/2019
    And I press "Sauvegarder"
    Then I should see "lundi 2 septembre 2019"
    Then I should see "mercredi 2 octobre 2019"
    Then I should see "samedi 2 novembre 2019"
    Then I should see "lundi 2 décembre 2019"

  Scenario: Add entry with periodicity every month with week day
    And I fill the entry startTime with the date 2/9/2019
    And I fill in "entry_with_periodicity[duration][time]" with "3"
    And I select "Heure(s)" from "entry_with_periodicity_duration_unit"
    #1 => Chaque mois meme semaine
    And I select "5" from "entry_with_periodicity[periodicity][type]"
    And I fill the periodicity endTime with the date 5/12/2019
    And I press "Sauvegarder"
    Then I should see "lundi 2 septembre 2019"
    Then I should see "lundi 7 octobre 2019"
    Then I should see "lundi 4 novembre 2019"
    Then I should see "lundi 2 décembre 2019"
