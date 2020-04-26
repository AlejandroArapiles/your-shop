<?php

namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsuarioController extends AbstractController
{
     /** @var EntityManagerInterface $entityManager */
     private $entityManager;

     /** @var SerializerInterface $serializer */
     private $serializer;

     public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    public function listUsuarios(int $idUsuario) :JsonResponse
    {
        $usuarios = $this->getDoctrine()->getRepository(Usuario::class)->findBy(["idtiendaFk" => $idTienda]);
        $usuarios = $this->serializer->normalize($usuarios, null, ["groups" => "public"]);
        return new JsonResponse(['data' => $usuarios]);
    }

    public function insertUsuario(Request $request)
    {
        $usuario = new Usuario();
        $datos = json_decode($request->getContent());
        $usuario->setNombreUsuario($datos->nombre);
        $usuario->setRol($datos->rol);
        $usuario->setPassword(md5($datos->password));
        $usuario->setIdtiendaFk($this->entityManager->getReference('App\Entity\Tienda', $datos->tienda));
        
        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        return new Response('Saved new usuario with nombre '.$usuario->getNombreUsuario());
    }
}
