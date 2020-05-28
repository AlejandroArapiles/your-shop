<?php

namespace App\Controller\Client;

use App\Entity\Sesion;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController {

    /** @var SessionInterface $sessionManager */
    private $sessionManager;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $sessionManager)
    {
        $this->sessionManager = $sessionManager;
        $this->entityManager = $entityManager;
    }

    public function cerrarSesion($idSesion)
    {
        // $sesion = $this->getDoctrine()->getRepository(Sesion::class)->findOneBySesion$this->sessionManager->get('user')['sesion']);
        // if (isset($sesion)) {
        //     $this->entityManager->remove($sesion);
        //     $this->entityManager->flush();
            //$this->sessionManager->invalidate();
        //     return new JsonResponse(['result' => 'ok']);
        // }
        return $this->render('sesion/modificar.html.twig', [
            //'tienda' => $tienda
        ]);
    }

    public function iniciarSesion(Request $request)
    {
        $notification = [];
        if ($request->getMethod() == 'POST' && !$this->sessionManager->isStarted()) {
            $usuario = $request->request->get('usuario');
            $password = $request->request->get('password');
            $user = $this->getDoctrine()->getRepository(Usuario::class)->comprobarUsuario($usuario, $password);
            if (isset($user)) {
                $sesion = new Sesion();
                $sesion->setIdusuarioFk($user);
                $sesion->setSesion(md5(time()));
                $sesion->setLastLogin(new \DateTime());
                $this->entityManager->persist($sesion);
                $this->entityManager->flush();
                
                //$session = new Session();
               // $session->start();

                $this->sessionManager->set('user', [
                    "idUsuario" => $user->getIdusuario(),
                    "rol" => $user->getRol(),
                    "nombre" => $user->getNombreusuario(),
                    "sesion" => $sesion->getSesion(),
                    "idTienda" => $user->getIdTiendaFk()->getIdtienda()
                ]);
                return $this->redirectToRoute('clientListProductos', ['idTienda' => $user->getIdTiendaFk()->getIdtienda()]);
            } else {
                $notification = ["type"=> "danger", "message" => "El usuario y/o contraseña no coinciden."];
            }
        }
        
        //$sesion = $this->getDoctrine()->getRepository(Sesion::class)->findByIdsesion($idTienda);
        return $this->render('login.html.twig', ['notification' => $notification]);
    }

    public function registrarTiendaUsuario(Request $request){

    }

    
}