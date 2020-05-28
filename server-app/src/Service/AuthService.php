<?php

namespace App\Service;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;

class AuthService {

    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->userRepository = $entityManager->getRepository(Usuario::class);
    }


    public function validateMd5($md5) :boolean
    {

    }


    public function validateMd5ByIdTienda($md5, $idTienda) :?Usuario
    {
        return $this->userRepository->findOneByMd5AndIdTienda($md5, $idTienda);
    }


}