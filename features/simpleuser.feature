Feature: Account
  Je me connecte avec bob
  Je modifie mon profil en changeant mon nom en "Batman"
  Je seléctionne Esquare comme Area par defaut
  Je change le mot de passe en "marge"
  Je test le nouveau mot de passe


  Background:
    Given I am logged in as user "bob@domain.be"
    Given I am on homepage
    When I follow "15"
    When I follow "Ajouter une entrée"
    And I fill in "entry_with_periodicity[name]" with "My reservation"
    And I fill the entry startTime with the date 5/10/2019
    And I fill in "entry_with_periodicity[duration][time]" with "3"
    And I select "Heure(s)" from "entry_with_periodicity_duration_unit"

  Scenario: Add entry as bob
    And I select "Relax Room" from "entry_with_periodicity_room"
    And I press "Sauvegarder"
    Then I should see "My reservation"
    Then I should see "La réservation a bien été ajoutée"

    #todo not finish
  Scenario: Add entry as brenda
    Given I am logged in as user "brenda@domain.be"
    And should not see an "Relax Room" element
#    And I select "Hdv" from "entry_with_periodicity_area"
#    And I select "Salle Conseil" from "entry_with_periodicity_room"
#    And I press "Sauvegarder"
#    And print last response
#    Then I should see "My reservation"
#    Then I should see "La réservation a bien été ajoutée"

  Scenario: Test link delete and edit
    Given I am logged in as user "brenda@domain.be"
    Given I am on the page show entry "Réunion conseillers"
    Then I should see "Modifier"
    Then I should see "Supprimer"
