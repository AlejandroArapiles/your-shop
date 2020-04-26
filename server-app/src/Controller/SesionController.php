<?php

namespace App\Controller;

use App\Entity\Sesion;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SesionController extends AbstractController
{

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var SerializerInterface $serializer */
    private $serializer;


    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * inicia sesión de un usuario introduciendo el usuario y password.
     * @return void
     */
    public function iniciarSesion(Request $request) {
        $datos = json_decode($request->getContent());
        $usuario = $datos->usuario;
        $password = $datos->password;
        $user = $this->getDoctrine()->getRepository(Usuario::class)->comprobarUsuario($usuario, $password);
        if (isset($user)) {
            $session = new Sesion();
            $session->setIdusuarioFk($user);
            $session->setSesion(md5(time()));
            $session->setLastLogin(new \DateTime());
            $this->entityManager->persist($session);
            $this->entityManager->flush();
            $session = $this->serializer->normalize($session, null, ["groups" => "public"]);

            return new JsonResponse(["data" => $session]);
        }
    }

    /**
     * cierra la sesión del usuario logado actualizando el lastLogin de este.
     * @return void
     */

    public function cerrarSesion(Request $request) {
        $datos = json_decode($request->getContent());
        $md5 = $datos->sesion;
        $sesion = $this->getDoctrine()->getRepository(Sesion::class)->findOneBySesion($md5);
        if (isset($sesion)) {
            $this->entityManager->remove($sesion);
            $this->entityManager->flush();
            
            return new JsonResponse(['result' => 'ok']);
        }
        
        return new JsonResponse(['result' => 'No existe']);

    }

    public function insertSesion(Request $request)
    {
        $sesion = new Sesion();
        $datos = json_decode($request->getContent());
        $sesion->setSesion(md5($datos->sesion));
        $sesion->setLastLogin(new \DateTime("now"));
        $sesion->setIdusuarioFk($entityManager->getReference('App\Entity\Usuario', $datos->usuario));

        $entityManager->persist($sesion);
        $entityManager->flush();

        return new Response('Saved new session with md5 '.$sesion->getSesion());
    }
}
