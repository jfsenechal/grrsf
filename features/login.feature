Feature: Authentication
  In order to gain access to the site management area
  As an admin
  I need to be able to login and logout

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

  Scenario: Bad login
    When I set basic authentication with "something" and "wrong"
    And I go to "/browser/auth.php"
    Then the response status code should be 401
    And I should see "NONE SHALL PASS"
