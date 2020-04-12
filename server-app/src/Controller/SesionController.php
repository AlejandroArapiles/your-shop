<?php

namespace App\Controller;

use App\Entity\Sesion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SesionController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function insertSesion(EntityManagerInterface $entityManager)
    {
        //TODO coger de json
        $usuarioId = 3;
        $sesion = new Sesion();
        $sesion->setSesion(md5(time()));
        $sesion->setIdusuarioFk($entityManager->getReference('App\Entity\Usuario', $usuarioId));

        $entityManager->persist($sesion);
        $entityManager->flush();

        return new Response('Saved new session with md5 '.$sesion->getSesion());
    }
}
