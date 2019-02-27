<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrrLog
 *
 * @ORM\Table(name="grr_log")
 * @ORM\Entity
 */
class GrrLog
{
    /**
     * @var string
     *
     * @ORM\Column(name="LOGIN", type="string", length=20, nullable=false)
     */
    private $login = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="START", type="datetime", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $start;

    /**
     * @var string
     *
     * @ORM\Column(name="SESSION_ID", type="string", length=64, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $sessionId = '';

    /**
     * @var string
     *
     * @ORM\Column(name="REMOTE_ADDR", type="string", length=16, nullable=false)
     */
    private $remoteAddr = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="USER_AGENT", type="string", length=255, nullable=true)
     */
    private $userAgent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="REFERER", type="string", length=255, nullable=true)
     */
    private $referer;

    /**
     * @var bool
     *
     * @ORM\Column(name="AUTOCLOSE", type="boolean", nullable=false)
     */
    private $autoclose = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="END", type="datetime", nullable=false)
     */
    private $end;

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function getRemoteAddr(): ?string
    {
        return $this->remoteAddr;
    }

    public function setRemoteAddr(string $remoteAddr): self
    {
        $this->remoteAddr = $remoteAddr;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getReferer(): ?string
    {
        return $this->referer;
    }

    public function setReferer(?string $referer): self
    {
        $this->referer = $referer;

        return $this;
    }

    public function getAutoclose(): ?bool
    {
        return $this->autoclose;
    }

    public function setAutoclose(bool $autoclose): self
    {
        $this->autoclose = $autoclose;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }


}
