<?php

class PdoGrr
{
    /**
     * @var \PDO
     */
    private $dbh;
    /**
     * @var string
     */
    private $prefix;

    public function __construct($dsn, $username, $password, $prefix)
    {
        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        );

        $this->dbh = new \PDO($dsn, $username, $password, $options);
        $this->prefix = $prefix.'_';
    }

    /**
     * @return array
     */
    public function getEntries()
    {
        $sql = 'SELECT * FROM `'.$this->prefix.'entry`';

        return $this->execute($sql);
    }


    /**
     * @return array
     */
    public function getRepeat()
    {
        $sql = 'SELECT * FROM `'.$this->prefix.'repeat`';

        return $this->execute($sql);
    }

    /**
     * @return array
     */
    public function getAreas()
    {
        $sql = 'SELECT * FROM `'.$this->prefix.'area`';

        return $this->execute($sql);
    }

    /**
     * @return array
     */
    public function getRooms()
    {
        $sql = 'SELECT * FROM `'.$this->prefix.'room`';

        return $this->execute($sql);
    }

    /**
     * @return array
     */
    public function getAdminsArea()
    {
        $sql = 'SELECT * FROM `'.$this->prefix.'j_useradmin_area`';

        return $this->execute($sql);
    }


    /**
     * @return array
     */
    public function getUserRoom()
    {
        $sql = 'SELECT * FROM `'.$this->prefix.'j_user_room`';

        return $this->execute($sql);
    }


    /**
     * @return array
     */
    public function getSettings()
    {
        $sql = 'SELECT * FROM `'.$this->prefix.'setting`';

        return $this->execute($sql);
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        $sql = 'SELECT * FROM `'.$this->prefix.'utilisateurs`';

        return $this->execute($sql);
    }

    /**
     * @return array
     */
    public function getTypeArea()
    {
        $sql = 'SELECT * FROM `'.$this->prefix.'type_area`';

        return $this->execute($sql);
    }

    private function execute($sql)
    {
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute();

            return $sth->fetchAll();
        } catch (\PDOException $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

}