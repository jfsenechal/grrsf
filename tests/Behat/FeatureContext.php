<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 21/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Behat;

use Behat\MinkExtension\Context\RawMinkContext;
use Carbon\Carbon;

class FeatureContext extends RawMinkContext
{
    /**
     * @beforeScenario
     */
    public function loadFixtures()
    {

    }


    /**
     * @Given I am logged in as an admin
     */
    public function iAmLoggedInAsAnAdmin()
    {
        $this->visitPath('/login');
        //var_dump($this->getSession()->getPage()->getContent());
        $this->fillField('username', 'grr@domain.be');
        $this->fillField('password', 'homer');
        $this->pressButton('S\'identifier');
    }

    /**
     * @Given I am logged in as user :username
     */
    public function iAmLoggedInAsUser(string $username)
    {
        $this->visitPath('/login');
        $this->fillField('username', $username);
        $this->fillField('password', 'homer');
        $this->pressButton('S\'identifier');
    }

    /**
     * Clicks link semaine
     * Example: When I follow this week
     * @Then /^I follow this week$/
     *
     */
    public function clickLinkWeek()
    {
        $link = "s".Carbon::today()->week;
        $link = $this->fixStepArgument($link);
        $this->getSession()->getPage()->clickLink($link);
    }

    /**
     * Clicks link day
     * Example: When I follow this day
     * @Then /^I follow this day$/
     *
     */
    public function clickLinkDay()
    {
        $link = Carbon::today()->day;
        $link = $this->fixStepArgument($link);
        $this->getSession()->getPage()->clickLink($link);
    }

    private function fillField(string $field, string $value)
    {
        $this->getSession()->getPage()->fillField($field, $value);
    }

    private function pressButton($button)
    {
        $button = $this->fixStepArgument($button);
        $this->getSession()->getPage()->pressButton($button);
    }

    protected function fixStepArgument($argument)
    {
        return str_replace('\\"', '"', $argument);
    }
}