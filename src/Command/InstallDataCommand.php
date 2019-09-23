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
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InstallDataCommand extends Command
{
    protected static $defaultName = 'grr:install-data';
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
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SymfonyStyle
     */
    private $io;

    public function __construct(
        string $name = null,
        EntityManagerInterface $entityManager,
        EntryTypeRepository $entryTypeRepository,
        RoomRepository $roomRepository,
        UserRepository $userRepository,
        TypeEntryFactory $typeEntryFactory,
        AreaRepository $areaRepository,
        AreaFactory $areaFactory,
        RoomFactory $roomFactory,
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
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Initialize les données dans la base de données lors de l\'installation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $questionPurge = new ConfirmationQuestion("Voulez vous vider la base de données ? [y,N] \n", false);
        $purge = $helper->ask($input, $output, $questionPurge);

        if ($purge) {
            $purger = new ORMPurger($this->entityManager);
            $purger->purge();

            $this->io->success('La base de données a bien été vidée.');
        }

        $this->loadType();
        $this->loadArea();
        $this->loadUser();

        $this->io->success('Les données ont bien été initialisées.');
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

        $colors = ["#FFCCFF", "#99CCCC", "#FF9999", "#FFFF99", "#C0E0FF", "#FFCC99", "#FF6666", "#66FFFF", "#DDFFDD"];

        foreach ($types as $index => $nom) {
            if ($this->entryTypeRepository->findOneBy(['name' => $nom])) {
                continue;
            }
            $type = $this->typeEntryFactory->createNew();
            $type->setLetter($index);
            $type->setName($nom);
            $type->setColor($colors[random_int(0, count($colors)-1)]);
            $this->entityManager->persist($type);
        }
        $this->entityManager->flush();
    }

    public function loadArea()
    {
        $esquareName = 'Esquare';
        $esquare = $this->areaRepository->findOneBy(['name' => $esquareName]);
        if (!$esquare) {
            $esquare = $this->areaFactory->createNew();
            $esquare->setName($esquareName);
            $this->entityManager->persist($esquare);
        }

        $hdvName = 'Hdv';
        $hdv = $this->areaRepository->findOneBy(['name' => $hdvName]);
        if (!$hdv) {
            $hdv = $this->areaFactory->createNew();
            $hdv->setName($hdvName);
            $this->entityManager->persist($hdv);
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
            'Salle du Conseil',
            'Salle du Collège',
            'Salle cafétaria',
        ];

        $this->loadRooms($hdv, $salles);

        $this->entityManager->flush();

        return null;
    }

    public function loadRooms(Area $area, array $salles)
    {
        foreach ($salles as $salle) {
            if ($this->roomRepository->findOneBy(['name' => $salle])) {
                continue;
            }
            $room = $this->roomFactory->createNew($area);
            $room->setName($salle);
            $this->entityManager->persist($room);
        }
    }

    public function loadUser()
    {
        $email = 'grr@domain.be';
        $password = random_int(100000, 999999);

        if ($this->userRepository->findOneBy(['email' => $email])) {
            return;
        }

        $password = 'homer';//todo remove
        $roleGrrAdministrator = SecurityRole::ROLE_GRR_ADMINISTRATOR;

        $user = $this->userFactory->createNew();
        $user->setName('Administrator');
        $user->setFirstName('Grr');
        $user->setEmail($email);
        $user->setUsername($email);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));
        $user->addRole($roleGrrAdministrator);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success("L'utilisateur $email avec le mot de passe $password a bien été créé");
    }
}
