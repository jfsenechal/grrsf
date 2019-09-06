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


use App\Entity\Security\UserAuthorization;
use App\Security\SecurityHelper;
use App\Tests\Repository\BaseRepository;

class SecurityHelperTest extends BaseRepository
{
    /**
     * @dataProvider provideAdministrator
     */
    public function testIsAreaAdministrator(string $email, bool $access)
    {
        $this->loadFixtures();

        $area = $this->getArea('Esquare');
        $securityHelper = $this->initSecurityHelper();
        $user = $this->getUser($email);

        self::assertSame($access, $securityHelper->isAreaAdministrator($user, $area));
    }

    public function provideAdministrator()
    {
        yield 'administrator' => [
            'bob@domain.be',
            true,
        ];

        yield 'not admin' => [
            'alice@domain.be',
            false,
        ];

        yield 'not admin' => [
            'fred@domain.be',
            false,
        ];
    }

    /**
     * @dataProvider provideRoomAdministrator
     */
    public function testIsRoomAdministrator(string $email, bool $access)
    {
        $this->loadFixtures();

        $area = $this->getArea('Esquare');
        $securityHelper = $this->initSecurityHelper();
        $user = $this->getUser($email);

        self::assertSame($access, $securityHelper->isRoomAdministrator($user, $area));
    }

    public function provideRoomAdministrator()
    {
        yield 'administrator' => [
            'bob@domain.be',
            true,
        ];

        yield 'not admin' => [
            'alice@domain.be',
            false,
        ];

        yield 'not admin' => [
            'fred@domain.be',
            false,
        ];
    }

    protected function initSecurityHelper()
    {
        return new SecurityHelper($this->entityManager->getRepository(UserAuthorization::class));
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