<?php

namespace App\Command;

use App\Entity\Area;
use App\Factory\AreaFactory;
use App\Factory\RoomFactory;
use App\Factory\TypeEntryFactory;
use App\Factory\UserFactory;
use App\Repository\AreaRepository;
use App\Repository\EntryTypeRepository;
use App\Repository\RoomRepository;
use App\Repository\Security\UserRepository;
use App\Security\SecurityRole;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InitDataCommand extends Command
{
    protected static $defaultName = 'grr:init-data';
    /**
     * @var EntryTypeRepository
     */
    private $entryTypeRepository;
    /**
     * @var TypeEntryFactory
     */
    private $typeEntryFactory;
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var AreaFactory
     */
    private $areaFactory;
    /**
     * @var RoomFactory
     */
    private $roomFactory;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(
        string $name = null,
        EntryTypeRepository $entryTypeRepository,
        TypeEntryFactory $typeEntryFactory,
        AreaRepository $areaRepository,
        AreaFactory $areaFactory,
        RoomFactory $roomFactory,
        RoomRepository $roomRepository,
        UserRepository $userRepository,
        UserFactory $userFactory,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        parent::__construct($name);
        $this->entryTypeRepository = $entryTypeRepository;
        $this->typeEntryFactory = $typeEntryFactory;
        $this->areaRepository = $areaRepository;
        $this->areaFactory = $areaFactory;
        $this->roomFactory = $roomFactory;
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Initialize les données dans la base de données lors de l\'installation');;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $this->loadType();
        $this->loadArea();
        $this->loadUser();

        $io->success('Les données ont bien été initialisées.');
    }

    public function loadType()
    {
        $types = [
            'A' => 'Cours',
            'B' => 'Reunion',
            'C' => 'Location',
            'D' => 'Bureau',
            'E' => 'Mise a disposition',
            'F' => 'Non disponible',
        ];

        foreach ($types as $index => $nom) {
            if ($this->entryTypeRepository->findOneBy(['name' => $nom])) {
                continue;
            }
            $type = $this->typeEntryFactory->createNew();
            $type->setLetter($index);
            $type->setName($nom);
            $this->entryTypeRepository->persist($type);

        }
        $this->entryTypeRepository->flush();
    }

    public function loadArea()
    {
        $esquareName = 'Esquare';
        $esquare = $this->areaRepository->findOneBy(['name' => $esquareName]);
        if (!$esquare) {
            $esquare = $this->areaFactory->createNew();
            $esquare->setName($esquareName);
            $this->areaRepository->persist($esquare);
        }

        $hdvName = 'Hdv';
        $hdv = $this->areaRepository->findOneBy(['name' => $hdvName]);
        if (!$hdv) {
            $hdv = $this->areaFactory->createNew();
            $hdv->setName($hdvName);
            $this->areaRepository->persist($hdv);
        }

        $salles = [
            'Box',
            'Créative',
            'Meeting Room',
            'Relax Room',
            'Digital Room',
        ];

        $this->loadRooms($esquare, $salles);

        $salles = [
            'Salle Conseil',
            'Salle Collège',
            'Salle cafétaria',
        ];

        $this->loadRooms($hdv, $salles);

        $this->areaRepository->flush();
        $this->roomRepository->flush();
    }

    public function loadRooms(Area $area, array $salles)
    {
        foreach ($salles as $salle) {
            if ($this->roomRepository->findOneBy(['name' => $salle])) {
                continue;
            }
            $room = $this->roomFactory->createNew($area);
            $room->setName($salle);
            $this->roomRepository->persist($room);
        }
    }

    public function loadUser()
    {
        $email = 'jf@marche.be';
        $password = 'homer';

        if ($this->userRepository->findOneBy(['email' => $email])) {
            return;
        }

        $roleGrrAdministrator = SecurityRole::getRoleGrrAdministrator();

        $user = $this->userFactory->createNew();
        $user->setName('Administrator');
        $user->setFirstName('Grr');
        $user->setEmail($email);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));
        $user->addRole($roleGrrAdministrator);

        $this->userRepository->insert($user);
    }
}
