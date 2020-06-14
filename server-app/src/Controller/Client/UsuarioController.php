<?php

namespace App\Controller\Client;

use App\Entity\Tienda;
use App\Entity\Usuario;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\AuthAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * CLIENT
 * Clase que interactúa con la tabla Usuario en la base de datos
 */
class UsuarioController extends AuthAbstractController {

     /** @var SessionInterface $sessionManager */
     protected $sessionManager;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer,
    AuthService $authService, SessionInterface $sessionManager) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Lista los usuarios de la tienda a la que pertenece el usuario. (solo admin)
     *
     * @param integer $idTienda Id de la tienda que lista los usuarios
     * @return void
     */
    public function listarUsuarios($idTienda)
    {
        if (!$this->authService->validateUserLogged()) {
            return $this->redirectToRoute('clientLogin');
        }
        if ($this->sessionManager->get('user')['idTienda'] != $idTienda) {
            throw new AccessDeniedHttpException("Esta tienda no pertenece a este usuario");
        }
        $usuarios = $this->getDoctrine()->getRepository(Usuario::class)->findByIdtiendaFk($idTienda);
        return $this->render('usuario/listado.html.twig', [
            'usuarios' => $usuarios
        ]);
    }

    /**
     * Crea un usuario de la tienda a la que pertenece el usuario. (solo admin)
     *
     * @param Request $request
     * @return void
     */
    public function crearUsuario(Request $request){
        if (!$this->authService->validateUserLogged()) {
            return $this->redirectToRoute('clientLogin');
        }
        if ($request->getMethod() == 'POST') {
            $usuario = new Usuario();
            $usuario->setNombreusuario($request->request->get('nombre'));
            $usuario->setPassword(md5($request->request->get('password')));
            $usuario->setRol($request->request->get('rol'));
            $usuario->setIdtiendaFk($this->entityManager->getReference('App\Entity\Tienda', $this->sessionManager->get('user')['idTienda']));

            $this->entityManager->persist($usuario);
            $this->entityManager->flush();

            return $this->redirectToRoute('clientListUsuarios', ['idTienda' => $usuario->getIdTiendaFk()->getIdtienda()]);
        } else {
            return $this->render('usuario/view.html.twig');
        }
    }

    /**
     * Lista la información de un usuario recibiendo su id por parámetro. (solo admin)
     *
     * @param Request $request
     * @param integer $idUsuario Id del usuario a listar información
     * @return void
     */
    public function verUsuario(Request $request, $idUsuario)
    {
        if (!$this->authService->validateUserLogged()) {
            return $this->redirectToRoute('clientLogin');
        }
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        if (!isset($usuario) || $this->sessionManager->get('user')['idTienda'] != $usuario->getIdTiendaFk()->getIdTienda()) {
            throw new AccessDeniedHttpException("Este usuario no pertenece a tu tienda");
        }

         if ($request->getMethod() == 'POST') {
            $usuario->setNombreusuario($request->request->get('nombre'));
            $usuario->setRol($request->request->get('rol'));
            if (!empty($request->request->get('password'))) {
                $usuario->setPassword($request->request->get('password'));
            }

            $this->entityManager->persist($usuario);
            $this->entityManager->flush();
            return $this->redirectToRoute('clientListUsuarios', ['idTienda' => $usuario->getIdTiendaFk()->getIdtienda()]);
        }
        return $this->render('usuario/view.html.twig', [
            'usuario' => $usuario
        ]);
    }

    /**
     * Modifica la contraseña del usuario logado recibiendo su id por parámetro
     *
     * @param Request $request
     * @param integer $idUsuario Id del usuario a modificar la contraseña
     * @return void
     */
    public function modificarPerfil(Request $request, $idUsuario)
    {
        if (!$this->authService->validateUserLogged()) {
            return $this->redirectToRoute('clientLogin');
        }
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        if (!isset($usuario) || ($this->sessionManager->get('user')['idTienda'] != $usuario->getIdTiendaFk()->getIdTienda())) {
            throw new AccessDeniedHttpException("Este usuario no pertenece a tu tienda");
        }       
        $data = [
            'usuario' => $usuario
        ];
        if ($request->getMethod() == 'POST') {
            if (!empty($request->request->get('password'))) {
                if ($request->request->get('password') == $request->request->get('checkPassword')) {
                    $usuario->setPassword(md5($request->request->get('password')));
                    $this->entityManager->persist($usuario);
                    $this->entityManager->flush();
                } else {
                    $data['notification'] = ["type" => "danger", "message" => "Las contraseñas no coinciden."];
                }
            }
        }

        return $this->render('usuario/perfil.html.twig', $data);
    }

    /**
     * Registra una nueva tienda añadiendo a esta un usuario admin
     *
     * @param Request $request
     * @return void
     */
    public function registrarUsuarioTienda(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $tienda = new Tienda();
            $tienda->setNombretienda($request->request->get('nTienda'));
            $tienda->setCif($request->request->get('cif'));
            $tienda->setCorreoContacto($request->request->get('correo'));
            $this->entityManager->persist($tienda);

            $usuario = new Usuario();
            $usuario->setNombreusuario($request->request->get('nUsuario'));
            $usuario->setPassword(md5($request->request->get('password')));
            $usuario->setRol('admin');
            $usuario->setIdtiendaFk($tienda);

            $this->entityManager->persist($usuario);
            $this->entityManager->flush();
            
            $this->authService->login($usuario);
            return $this->redirectToRoute('clientListProductos', ['idTienda' => $tienda->getIdtienda()]);
        }

        return $this->render('usuario/registro.html.twig');

    }

   
}