<?php

namespace App\Service;

use App\Entity\Sesion;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Clase que realiza las validaciones para realizar operaciones en las tablas
 */
class AuthService {

    private $entityManager;
    private $sessionManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $sessionManager) 
    {
        $this->entityManager = $entityManager;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Realiza el login de un usuario
     *
     * @param Usuario $user
     * @return void
     */
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
            "lastlogin" => $sesion->getLastlogin(),
            "idTienda" => $user->getIdTiendaFk()->getIdtienda()
        ]);
    }

    /**
     * Comprueba si el usuario pertenece a la tienda mediante su md5
     *
     * @param String $md5
     * @param integer $idTienda
     * @return Usuario|null
     */
    public function validateMd5ByIdTienda($md5, $idTienda) :?Usuario
    {
        return $this->entityManager->getRepository(Usuario::class)->findOneByMd5AndIdTienda($md5, $idTienda);
    }

    /**
     * Comprueba si un usuario existe mediante un md5
     *
     * @param String $md5
     * @param int $idUsuario
     * @return Usuario|null
     */
    public function validateMd5ByIdUsuario($md5, $idUsuario) :?Usuario
    {
        return $this->entityManager->getRepository(Usuario::class)->findOneByMd5AndIdTienda($md5, $idTienda);
    }

    /**
     * Comprueba si un usuario tiene sesión iniciada
     *
     * @return void
     */
    public function validateUserLogged()
    {
        return $this->sessionManager->has('user') ? true: false;
    }


}