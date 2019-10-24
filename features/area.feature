Feature: Manage area
  J'ajoute une area avec comme nom Area demo
  Je la renomme en Hdv demon
  Je lui attribute les types de réservations : Cours, réunion

  Scenario: New area
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

  Scenario: Edit area
    Given I am on "/admin/area/"
    Then I should be on "/login"
    And I fill in "username" with "grr@domain.be"
    And I fill in "password" with "homer"
    And I press "S'identifier"
    Then I should be on "/admin/area/"
    #Then print last response
    Then I should see "Les domaines"
    When I follow "Area demo"
    When I follow "Modifier"
    And I fill in "area[name]" with "Hdv demo"
    And I press "Sauvegarder"
    Then I should see "Hdv demo"

  Scenario: Attribution de types d'entrée
    Given I am on "/admin/area/"
    Then I should be on "/login"
    And I fill in "username" with "grr@domain.be"
    And I fill in "password" with "homer"
    And I press "S'identifier"
    Then I should be on "/admin/area/"
    #Then print last response
    Then I should see "Les domaines"
    When I follow "Area demo"
    When I follow "Types d'entrée"
    And I ti in "assoc_type_for_area[entryTypes][]" with "Hdv demo"
    And I press "Sauvegarder"
    Then I should see "Hdv demo"