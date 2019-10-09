<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class DemoContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    /** @var Response|null */
    private $response;
    /**
     * @var GuardAuthenticatorHandler
     */
    private $guardAuthenticatorHandler;

    public function __construct(
        KernelInterface $kernel,
        GuardAuthenticatorHandler $guardAuthenticatorHandler
    ) {
        $this->kernel = $kernel;
        $this->guardAuthenticatorHandler = $guardAuthenticatorHandler;
    }

    /**
     * @When a demo scenario sends a request to :path
     */
    public function aDemoScenarioSendsARequestTo(string $path): void
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived(): void
    {
        if (null === $this->response) {
            throw new \RuntimeException('No response received');
        }
    }

    /**
     * @Given i am login
     */
    public function imLogin()
    {
    }

    /**
     * @Then I should see :arg1
     */
    public function iShouldSee($arg1)
    {
        if (null === $this->response) {
            throw new \RuntimeException('No response received');
        }

        if (true !== $this->response->isRedirection()) {
            throw new \RuntimeException('Response is not redirect');
        }
    }

    /**
     * @When /^i am login with user "([^"]*)" and password "([^"]*)"$/
     */
    public function iAmLoginWithUserAndPassword($arg1, $arg2)
    {
        if (null === $arg1) {
            throw new \RuntimeException('No user received');
        }
    }
}
