<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 6/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Security;


use App\Entity\Area;
use App\Entity\Security\User;
use App\Security\Voter\AreaVoter;
use App\Tests\Repository\BaseRepository;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AreaVoterTest extends BaseRepository
{

    /**
     * dataProvider provideCases
     * @param string $attribute
     * @param Area $project
     * @param User|null $user
     * @param int $expectedVote
     */
    public function testVote()
    {
        $this->loadFixtures();

        $area = $this->getArea('Esquare');
        $user = null;
        $attribute = AreaVoter::EDIT;

        $voter = new AreaVoter();
        $token = new AnonymousToken('secret', 'anonymous');
        if ($user) {
            $token = new UsernamePasswordToken(
                $user, 'credentials', 'memory'
            );
        }

        $this->assertSame(
            Voter::ACCESS_DENIED,
            $voter->vote($token, $area, [$attribute])
        );
    }

    public function getAreaAdministrator() {

    }

    public function provideCases()
    {
        yield 'anonymous cannot edit' => [
            'edit',
            $this->getArea('Esquare'),
            null,
            Voter::ACCESS_DENIED,
        ];

        yield 'non-owner cannot edit' => [
            'edit',
            $this->getArea('Esquare'),
            $this->getUser('bob@domaine.be'),
            Voter::ACCESS_DENIED,
        ];

        yield 'owner can edit' => [
            'edit',
            $this->getArea('Hdb'),
            $this->getUser('alice@domaine.be'),
            Voter::ACCESS_GRANTED,
        ];
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'user.yaml',
                $this->pathFixtures.'authorization.yaml',
            ];

        $this->loader->load($files);
    }

}