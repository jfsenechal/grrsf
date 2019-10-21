Feature: Ajout d'un type d'entrée
  Pour ajouter un type je dois etre administrateur

  Scenario: Logging in
    Given I am on homepage
    When I follow "Administration"
    #Then the response should be received
    #When I follow "Administration"
    Then I should be on "/login"
    And I fill in "username" with "grr@domain.be"
    And I fill in "password" with "homer"
    And I press "S'identifier"
    Then I should be on "/admin/"
    #Then print last response
    Then I should see "Grr admin"
    When I follow "Type de réservations"
    When I follow "Nouveau type"
    And I fill in "type_entry[name]" with "Racc"
    And I fill in "type_entry[letter]" with "R"
    And I press "Sauvegarder"
    Then I should be on "admin/entry/type/"
    Then I should see "Le type de réservation a bien été ajouté"
