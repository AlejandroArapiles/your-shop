<?php

namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsuarioController extends AbstractController
{
    public function insertUsuario(EntityManagerInterface $entityManager)
    {
        $usuario = new Usuario();
        $usuario->setNombreUsuario("usuario1");
        $usuario->setRol("Administrador");
        $usuario->setPassword(md5("contraseña"));
        $usuario->setIdtiendaFk($entityManager->getReference('App\Entity\Tienda', 1));
        
        $entityManager->persist($usuario);
        $entityManager->flush();

        return new Response('Saved new usuario with nombre '.$usuario->getNombreUsuario());
    }
}
