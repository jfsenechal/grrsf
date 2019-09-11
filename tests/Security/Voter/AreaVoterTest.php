<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 6/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Security\Voter;

use App\Entity\Security\User;
use App\Entity\Security\UserAuthorization;
use App\Security\SecurityHelper;
use App\Security\Voter\AreaVoter;
use App\Tests\Repository\BaseRepository;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AreaVoterTest extends BaseRepository
{
    /**
     * @dataProvider provideCases
     */
    public function testVote(string $attribute, string $areaName, int $result, ?string $email = null)
    {
        $this->loadFixtures();

        $area = $this->getArea($areaName);
        $user = null;
        if ($email) {
            $user = $this->getUser($email);
        }

        $token = $this->initToken($user);
        $voter = $this->initVoter();

        $this->assertSame($result, $voter->vote($token, $area, [$attribute]));
    }

    public function provideCases()
    {
        yield 'anonymous cannot edit' => [
            AreaVoter::EDIT,
            'Esquare',
            Voter::ACCESS_DENIED,
            null,
        ];

        yield 'administrator' => [
            AreaVoter::EDIT,
            'Esquare',
            Voter::ACCESS_GRANTED,
            'bob@domain.be',
        ];

        yield 'not admin' => [
            AreaVoter::EDIT,
            'Esquare',
            Voter::ACCESS_DENIED,
            'alice@domain.be',
        ];
    }

    protected function initSecurityHelper()
    {
        return new SecurityHelper($this->entityManager->getRepository(UserAuthorization::class));
    }

    private function initVoter()
    {
        $mock = $this->createMock(AccessDecisionManager::class);

        /*   $accessDecisionManager
               ->expects($this->once())
               ->method('decide')
               ->with($this->equalTo($token), $this->equalTo(['foo' => 'bar']), $this->equalTo($request))
               ->willReturn(false);
   */

        return new AreaVoter($mock, $this->initSecurityHelper());
    }

    private function initToken(?User $user)
    {
        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')->getMock(
        );

        $token
            ->expects($this->any())
            ->method('isAuthenticated')
            ->willReturn(true);

        $token = new AnonymousToken('secret', 'anonymous');
        if ($user) {
            $token = new UsernamePasswordToken(
                $user, 'homer', 'app_user_provider'
            );
        }

        return $token;
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