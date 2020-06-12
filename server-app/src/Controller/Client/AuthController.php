<?php

namespace App\Controller\Client;

use App\Entity\Sesion;
use App\Entity\Usuario;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\AuthAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthController extends AuthAbstractController {

    /** @var SessionInterface $sessionManager */
    private $sessionManager;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer,
    AuthService $authService, SessionInterface $sessionManager) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
    }


    public function cerrarSesion()
    {
        $sesion = $this->getDoctrine()->getRepository(Sesion::class)->findOneBySesion($this->sessionManager->get('user')['sesion']);
        if (isset($sesion)) {
            $this->entityManager->remove($sesion);
            $this->entityManager->flush();
            $this->sessionManager->invalidate();
        }
        
        return $this->redirectToRoute('clientLogin');
    }

    public function iniciarSesion(Request $request)
    {
        if ($request->getMethod() == 'GET' && $this->authService->validateUserLogged()) {
            return $this->redirectToRoute("clientListProductos", ['idTienda' => $this->sessionManager->get("user")['idTienda']]);
        }

        $notification = [];
        if ($request->getMethod() == 'POST' && !$this->sessionManager->isStarted()) {
            $usuario = $request->request->get('usuario');
            $password = $request->request->get('password');
            $user = $this->getDoctrine()->getRepository(Usuario::class)->comprobarUsuario($usuario, $password);
            if (isset($user)) {
                $this->authService->login($user);
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