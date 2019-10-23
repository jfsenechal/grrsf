Feature: Ajout d'une area
  Pour ajouter un type je dois etre administrateur

  Scenario: Logging in
    Given I am on "/admin/area/"
    Then I should be on "/login"
    And I fill in "username" with "grr@domain.be"
    And I fill in "password" with "homer"
    And I press "S'identifier"
    Then I should be on "/admin/area/"
    #Then print last response
    Then I should see "Les domaines"
    When I follow "Nouveau domaine"
    And I fill in "area[name]" with "Area demo"
    And I press "Sauvegarder"
    Then I should see "Le domaine a bien été ajouté"
