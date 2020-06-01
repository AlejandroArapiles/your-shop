<?php

namespace App\Service;

use App\Entity\Sesion;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthService {

    private $entityManager;
    private $sessionManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $sessionManager) 
    {
        $this->entityManager = $entityManager;
        $this->sessionManager = $sessionManager;
    }


    public function validateMd5($md5) :boolean
    {

    }

    public function login($user)
    {
        $sesion = new Sesion();
        $sesion->setIdusuarioFk($user);
        $sesion->setSesion(md5(time()));
        $sesion->setLastLogin(new \DateTime());
        $this->entityManager->persist($sesion);
        $this->entityManager->flush();
        $this->sessionManager->set('user', [
            "idUsuario" => $user->getIdusuario(),
            "rol" => $user->getRol(),
            "nombre" => $user->getNombreusuario(),
            "sesion" => $sesion->getSesion(),
            "idTienda" => $user->getIdTiendaFk()->getIdtienda()
        ]);
    }

    public function validateMd5ByIdTienda($md5, $idTienda) :?Usuario
    {
        return $this->entityManager->getRepository(Usuario::class)->findOneByMd5AndIdTienda($md5, $idTienda);
    }

    public function validateUserLogged()
    {
        return $this->sessionManager->has('user') ? true: false;
    }


}