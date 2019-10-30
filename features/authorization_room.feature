Feature: Manage room authorization
  J'ajoute Bob et Alice en tant que room manager de l'Esquare

  Scenario: New room manager
    Given I am logged in as an admin
    Given I am on "/admin/area/"
    Then I should see "Les domaines"
    Then I follow "Esquare"
    Then I follow "Droits"
    Then I follow "Ajouter"
    When I select "Box" from "authorization_area_rooms"
    And I additionally select "Meeting Room" from "authorization_area_rooms"
    When I select "ADAMS bob" from "authorization_area_users"
    And I additionally select "ADAMS alice" from "authorization_area_users"
    Then I press "Sauvegarder"
    And I should see "Nouvelle autorisation bien ajoutée"
    And I should see "ADAMS alice"
    And I should see "ADAMS bob"
    And I should see "Box"
    And I should see "Meeting Room"
