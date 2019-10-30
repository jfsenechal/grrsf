Feature: Edit entries with periodicity

  Background:
    Given I am logged in as an admin
    Given I am on homepage
    When I follow "15"
    When I follow "Ajouter une entrée"
    And I fill in "entry_with_periodicity[name]" with "Tous les jours pendant 3 jours"
    And I fill the entry startTime with today 10:00
    And I fill in "entry_with_periodicity[duration][time]" with "3"
    And I select "Heure(s)" from "entry_with_periodicity_duration_unit"
    #1 => Chaque jour
    And I select "1" from "entry_with_periodicity[periodicity][type]"
    And I select "Relax Room" from "entry_with_periodicity_room"
    And I fill the periodicity endTime with later date
    And I press "Sauvegarder"
    Then I should see "Tous les jours pendant 3 jours"

  Scenario: Edit one entry
    Given I am on the homepage
    Then I follow "Tous les jours pendant 3 jours"
    Then I follow "Modifier la réservation"
    And I fill in "entry[name]" with "Tous les jours pendant 4 jours"
    And I press "Sauvegarder"
    Then I should see "Tous les jours pendant 4 jours"
    Given I am on homepage
    Then I should see "Tous les jours pendant 3 jours"
    Then I should see "Tous les jours pendant 4 jours"

  Scenario: Edit periodicity remove periodicity
    Given I am on the homepage
    Then I follow "Tous les jours pendant 3 jours"
    Then I follow "Modifier la périodicité"
    And I select "0" from "entry_with_periodicity[periodicity][type]"
    And I press "Sauvegarder"
    Then should not see "Périodicité"
    Given I am on homepage
    Then I should see "Tous les jours pendant 3 jours" exactly "1" times

  Scenario: Change end date periodicity
    Given I am on the homepage
    Then I follow "Tous les jours pendant 3 jours"
    Then I follow "Modifier la périodicité"
    And I fill in "entry_with_periodicity[periodicity][endTime][day]" with "8"
    And I press "Sauvegarder"
    Then I should see "Tous les jours pendant 4 jours"
    Given I am on homepage
    Then I should see "Tous les jours pendant 3 jours"
    Then I should see "Tous les jours pendant 4 jours"
