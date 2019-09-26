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
use App\Entity\Security\Authorization;
use App\Security\SecurityHelper;
use App\Security\Voter\EntryVoter;
use App\Tests\BaseTesting;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EntryVoterTest extends BaseTesting
{
    /**
     * @dataProvider provideCases
     */
    public function testVote(string $attribute, array $datas)
    {
        $this->loadFixtures();
        $voter = $this->initVoter();

        foreach ($datas as $data) {
            $entryName = $data[0];
            $result = $data[1];
            $email = $data[2];

            $area = $this->getEntry($entryName);
            $user = null;
            if ($email) {
                $user = $this->getUser($email);
            }

            $token = $this->initToken($user);
            $this->assertSame($result, $voter->vote($token, $area, [$attribute]));
        }
    }

    public function provideCases()
    {
        yield [
            EntryVoter::INDEX,
            [
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    null,
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'bob@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'alice@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'raoul@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'fred@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'grr@domain.be',
                ],
            ],
        ];

        yield [
            EntryVoter::EDIT,
            [
                [
                    'Réunion cst',
                    Voter::ACCESS_DENIED,
                    null,
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'bob@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'alice@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'raoul@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_DENIED,
                    'fred@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'grr@domain.be',
                ],
            ],
        ];

        yield [
            EntryVoter::DELETE,
            [
                [
                    'Réunion cst',
                    Voter::ACCESS_DENIED,
                    null,
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'bob@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'alice@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'raoul@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_DENIED,
                    'fred@domain.be',
                ],
                [
                    'Réunion cst',
                    Voter::ACCESS_GRANTED,
                    'grr@domain.be',
                ],
            ],
        ];
    }

    protected function initSecurityHelper()
    {
        return new SecurityHelper($this->entityManager->getRepository(Authorization::class));
    }

    private function initVoter()
    {
        $mock = $this->createMock(AccessDecisionManager::class);

        return new EntryVoter($mock, $this->initSecurityHelper());
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
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'entry.yaml',
            ];

        $this->loader->load($files);
    }


}