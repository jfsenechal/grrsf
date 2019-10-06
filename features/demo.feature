# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

#When détermine un événement
#And ajoute une nouvelle action
#Then détermine le résultat attendu

Feature:
    In order to prove that the Behat Symfony extension is correctly installed
    As a user
    I want to have a demo scenario

    Scenario: It receives a response from Symfony's kernel
        When i am login with user "grr@domain.be" and password "homer"
        When a demo scenario sends a request to "/"
        #Then the response should be received
        Then I should see "Welcome to country"
