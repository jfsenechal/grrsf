<?php

namespace App\Security\Ldap;

use Symfony\Component\Ldap\Exception\LdapException;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class GrrLdap
{
    /**
     * @var Ldap
     */
    private $ldap;
    /**
     * @var string
     */
    private $dn;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;

    public function __construct(string $host, $dn, string $user, string $password)
    {
        $this->ldap = Ldap::create(
            'ext_ldap',
            [
                'host' => $host,
                'encryption' => 'ssl',
            ]
        );

        $this->user = $user;
        $this->password = $password;

        $this->dn = $dn;
    }

    /**
     * @param $uid
     *
     * @return mixed|null
     */
    public function getEntry($uid)
    {
        $this->ldap->bind($this->user, $this->password);
        $filter = "(&(|(sAMAccountName=*$uid*))(objectClass=person))";
        $query = $this->ldap->query($this->dn, $filter, ['maxItems' => 1]);
        $results = $query->execute();

        if ($results->count() > 0) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param $user
     * @param $password
     *
     * @throws LdapException
     */
    public function bind($user, $password): void
    {
        try {
            $this->ldap->bind($user, $password);
        } catch (\Exception $exception) {
            throw new BadCredentialsException($exception->getMessage());
        }
    }

    public function getEntryManager(): \Symfony\Component\Ldap\Adapter\EntryManagerInterface
    {
        return $this->ldap->getEntryManager();
    }
}
