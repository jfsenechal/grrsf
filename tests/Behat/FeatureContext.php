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
use Rector\Doctrine\Tests\Rector\MethodCall\ChangeGetUuidMethodCallToGetIdRector\Source\Car;

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
     * @Given I fill the periodicity endTime with the date :day/:month/:year
     */
    public function iFillEndTimePeridocity(int $day, int $month, int $year)
    {
        $this->fillField('entry_with_periodicity[periodicity][endTime][day]', $day);
        $this->fillField('entry_with_periodicity[periodicity][endTime][month]', $month);
        $this->fillField('entry_with_periodicity[periodicity][endTime][year]', $year);
    }

    /**
     * @Given /^I fill the periodicity endTime with this month and day (\d+) and year (\d+)$/
     */
    public function iFillEndTimePeridocityThisMonth(int $day, int $year)
    {
        $today = Carbon::today();

        $this->fillField('entry_with_periodicity[periodicity][endTime][day]', $day);
        $this->fillField('entry_with_periodicity[periodicity][endTime][month]', $today->month);
        $this->fillField('entry_with_periodicity[periodicity][endTime][year]', $year);
    }

    /**
     * @Given I fill the periodicity endTime with later date
     */
    public function iFillEndTimePeridocityLater()
    {
        $today = Carbon::today();
        $today->addDays(3);

        $this->fillField('entry_with_periodicity[periodicity][endTime][day]', $today->day);
        $this->fillField('entry_with_periodicity[periodicity][endTime][month]', $today->month);
        $this->fillField('entry_with_periodicity[periodicity][endTime][year]', $today->year);
    }

    /**
     * @Given I fill the entry startTime with the date :day/:month/:year
     */
    public function iFillDateBeginEntry(int $day, int $month, int $year)
    {
        $this->fillField('entry_with_periodicity_startTime_date_day', $day);
        $this->fillField('entry_with_periodicity_startTime_date_month', $month);
        $this->fillField('entry_with_periodicity_startTime_date_year', $year);
    }

    /**
     * @Given I fill the entry startTime with today :hour::minute
     */
    public function iFillDateBeginEntryWithToday(int $hour, int $minute)
    {
        $today = Carbon::today();
        $this->fillField('entry_with_periodicity_startTime_date_day', $today->day);
        $this->fillField('entry_with_periodicity_startTime_date_month', $today->month);
        $this->fillField('entry_with_periodicity_startTime_date_year', $today->year);
        $this->fillField('entry_with_periodicity_startTime_time_hour', $hour);
        $this->fillField('entry_with_periodicity_startTime_time_minute', $minute);
    }

    /**
     * @Given /^I fill the entry startTime with this month and day (\d+) and year (\d+) at time (\d+):(\d+)$/
     *
     */
    public function iFillDateBeginEntryWithThisMonth(int $day, int $year, int $hour, int $minute)
    {
        $today = Carbon::today();
        $this->fillField('entry_with_periodicity_startTime_date_day', $day);
        $this->fillField('entry_with_periodicity_startTime_date_month', $today->month);
        $this->fillField('entry_with_periodicity_startTime_date_year', $year);
        $this->fillField('entry_with_periodicity_startTime_time_hour', $hour);
        $this->fillField('entry_with_periodicity_startTime_time_minute', $minute);
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

    /**
     * @Then /^I should see "([^"]*)" exactly "([^"]*)" times$/
     * @throws \Exception
     */
    public function iShouldSeeTextSoManyTimes($sText, $iExpected)
    {
        $sContent = $this->getSession()->getPage()->getText();
        $iFound = substr_count($sContent, $sText);
        if ($iExpected != $iFound) {
            throw new \Exception('Found '.$iFound.' occurences of "'.$sText.'" when expecting '.$iExpected);
        }
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