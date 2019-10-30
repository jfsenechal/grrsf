# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Using SymfonyExtension

  @javascript
  Scenario: Checking menu select
    Then I am on the homepage
    Then I should be on "/"
    Then print current URL
    Then print last response
    And I select "Hdv" from "area_menu_select_area"
