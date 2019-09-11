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

use App\Entity\Security\User;
use App\Entity\Security\UserAuthorization;
use App\Security\SecurityHelper;
use App\Security\SecurityRole;
use App\Tests\Repository\BaseRepository;

class SecurityHelperTest extends BaseRepository
{
    /**
     * @dataProvider provideAdministrator
     */
    public function testIsAdministrator(
        string $email,
        bool $access1,
        bool $access2,
        bool $access3,
        bool $access4
    ) {
        $this->loadFixtures();

        $area = $this->getArea('Esquare');
        $securityHelper = $this->initSecurityHelper();
        $user = $this->getUser($email);

        self::assertSame($access1, $securityHelper->isAreaAdministrator($user, $area));

        $room = $this->getRoom('Box');
        self::assertSame($access2, $securityHelper->isRoomAdministrator($user, $room));

        $room = $this->getRoom('Salle cafétaria');
        self::assertSame($access3, $securityHelper->isRoomAdministrator($user, $room));

        $area = $this->getArea('Hdv');
        self::assertSame($access4, $securityHelper->isAreaAdministrator($user, $area));
    }

    public function provideAdministrator()
    {
        yield 'administrator' => [
            'bob@domain.be',
            true,
            true,
            false,
            false,
        ];

        yield 'not admin' => [
            'alice@domain.be',
            false,
            false,
            false,
            false,
        ];

        yield 'admin area of Hdv' => [
            'joseph@domain.be',
            false,
            false,
            true,
            true,
        ];

        yield 'not admin area of Hdv' => [
            'kevin@domain.be',
            false,
            false,
            false,
            false,
        ];

        yield 'admin cafet' => [
            'fred@domain.be',
            false,
            false,
            true,
            false,
        ];

        yield 'not admin' => [
            'raoul@domain.be',
            false,
            true,
            false,
            false,
        ];

        yield 'box ' => [
            'charle@domain.be',
            false,
            false,
            false,
            false,
        ];
    }

    /**
     * @dataProvider provideManager
     */
    public function testIsManager(string $email, bool $access1, bool $access2, bool $access3, bool $access4)
    {
        $this->loadFixtures();

        $securityHelper = $this->initSecurityHelper();
        $user = $this->getUser($email);

        $area = $this->getArea('Esquare');
        self::assertSame($access1, $securityHelper->isAreaManager($user, $area));

        $room = $this->getRoom('Box');
        self::assertSame($access2, $securityHelper->isRoomManager($user, $room));

        $room = $this->getRoom('Salle cafétaria');
        self::assertSame($access3, $securityHelper->isRoomManager($user, $room));

        $area = $this->getArea('Hdv');
        self::assertSame($access4, $securityHelper->isAreaManager($user, $area));
    }

    public function provideManager()
    {
        yield 'administrator' => [
            'bob@domain.be',
            true,
            true,
            false,
            false,
        ];

        yield 'not admin' => [
            'alice@domain.be',
            true,
            true,
            false,
            false,
        ];

        yield 'admin area of Hdv' => [
            'joseph@domain.be',
            false,
            false,
            true,
            true,
        ];

        yield 'not admin area of Hdv' => [
            'kevin@domain.be',
            false,
            false,
            false,
            true,
        ];

        yield 'admin cafet' => [
            'fred@domain.be',
            false,
            false,
            true,
            false,
        ];

        yield 'not admin' => [
            'raoul@domain.be',
            false,
            true,
            false,
            false,
        ];

        yield 'box ' => [
            'charle@domain.be',
            false,
            true,
            false,
            false,
        ];
    }

    /**
     * @dataProvider provideAddEntry
     */
    public function testAddEntry(string $email, bool $access1, bool $access2)
    {
        $this->loadFixtures();

        $securityHelper = $this->initSecurityHelper();
        $user = $this->getUser($email);

        $room = $this->getRoom('Box');
        self::assertSame($access1, $securityHelper->canAddEntry($room, $user));

        $room = $this->getRoom('Salle cafétaria');
        self::assertSame($access2, $securityHelper->canAddEntry($room, $user));
    }

    public function provideAddEntry()
    {
        yield 'administrator' => [
            'bob@domain.be',
            true,
            false,
        ];

        yield 'not admin' => [
            'alice@domain.be',
            true,
            false,
        ];

        yield 'admin area of Hdv' => [
            'joseph@domain.be',
            false,
            true,
        ];

        yield 'not admin area of Hdv' => [
            'kevin@domain.be',
            false,
            true,
        ];

        yield 'admin cafet' => [
            'fred@domain.be',
            false,
            true,
        ];

        yield 'box admin' => [
            'raoul@domain.be',
            true,
            false,
        ];

        yield 'box ' => [
            'charle@domain.be',
            true,
            false,
        ];
    }

    /**
     * @dataProvider provideGrrAdministrator
     */
    public function testIsGrrAdministrator(User $user, bool $administrator)
    {
        $this->loadFixtures();
        $securityHelper = $this->initSecurityHelper();

        self::assertSame($administrator, $securityHelper->isGrrAdministrator($user));
    }

    public function provideGrrAdministrator()
    {
        $user = new User();
        $user->addRole(SecurityRole::ROLE_GRR_ADMINISTRATOR);

        yield 'administrator' => [
            $user,
            true,
        ];

        $user = new User();
        $user->setRoles(SecurityRole::getRoles());
        $user->removeRole(SecurityRole::ROLE_GRR_ADMINISTRATOR);

        yield 'not administrator' => [
            $user,
            false,
        ];

        yield 'not administrator' => [
            new User(),
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
                $this->pathFixtures.'authorization_administrator.yaml',
            ];

        $this->loader->load($files);
    }

}