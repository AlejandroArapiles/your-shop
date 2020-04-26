<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sesion
 *
 * @ORM\Table(name="Sesion", uniqueConstraints={@ORM\UniqueConstraint(name="sesion_usuario_UNIQUE", columns={"Sesion", "idUsuario_FK"})}, indexes={@ORM\Index(name="fk_Sesion_Usuario", columns={"idUsuario_FK"})})
 * @ORM\Entity(repositoryClass="App\Repository\SesionRepository")
 */
class Sesion
{
    /**
     * @var int
     *
     * @ORM\Column(name="idSesion", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"public"})
     */
    private $idsesion;

    /**
     * @var string
     *
     * @ORM\Column(name="Sesion", type="string", length=60, nullable=false)
     * @Groups({"public"})
     */
    private $sesion;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUsuario_FK", referencedColumnName="idUsuario")
     * })
     * @Groups({"public"})
     */
    private $idusuarioFk;

    /**
     * @var string
     *
     * @Assert\DateTime
     * @ORM\Column(name="lastLogin", type="datetime", nullable=false)
     * @Groups({"public"})
     */
    private $lastLogin;

    public function getIdsesion(): ?int
    {
        return $this->idsesion;
    }

    public function getSesion(): ?string
    {
        return $this->sesion;
    }

    public function setSesion(string $sesion): self
    {
        $this->sesion = $sesion;

        return $this;
    }

    public function getIdusuarioFk(): ?Usuario
    {
        return $this->idusuarioFk;
    }

    public function setIdusuarioFk(?Usuario $idusuarioFk): self
    {
        $this->idusuarioFk = $idusuarioFk;

        return $this;
    }

    public function getLastlogin(): ?\DateTime
    {
       return $this->lastLogin;
    }

    public function setLastlogin(\DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

}
