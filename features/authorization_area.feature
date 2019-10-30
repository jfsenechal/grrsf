Feature: Manage area authorization
  J'ajoute Bob et Alice en tant que Administrateur de l'Esquare

  Scenario: New administrateur area
    Given I am logged in as an admin
    Given I am on "/admin/area/"
    Then I should see "Les domaines"
    Then I follow "Esquare"
    Then I follow "Droits"
    Then I follow "Ajouter"
    When I select "1" from "authorization_area[role]"
    When I select "ADAMS bob" from "authorization_area_users"
    And I additionally select "ADAMS alice" from "authorization_area_users"
    Then I press "Sauvegarder"
    And I should see "Nouvelle autorisation bien ajoutée"
    And I should see "ADAMS alice"
    And I should see "ADAMS bob"

  Scenario: New room manager
    Given I am logged in as an admin
    Given I am on "/admin/area/"
    Then I should see "Les domaines"
    Then I follow "Esquare"
    Then I follow "Droits"
    Then I follow "Ajouter"
    When I select "2" from "authorization_area[role]"
    When I select "ADAMS bob" from "authorization_area_users"
    And I additionally select "ADAMS alice" from "authorization_area_users"
    Then I press "Sauvegarder"
    And I should see "Nouvelle autorisation bien ajoutée"
    And I should see "ADAMS alice"
    And I should see "ADAMS bob"
    #Deja les droits
    When I follow "Ajouter"
    When I select "2" from "authorization_area[role]"
    When I select "ADAMS bob" from "authorization_area_users"
    And I additionally select "ADAMS alice" from "authorization_area_users"
    Then I press "Sauvegarder"
    And I should see "L' autorisation pour Esquare existe déjà pour ADAMS alice"
