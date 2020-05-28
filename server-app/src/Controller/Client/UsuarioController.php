<?php

namespace App\Controller\Client;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsuarioController extends AbstractController {

    /** @var EntityManagerInterface $entityManager */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
    $this->entityManager = $entityManager;
    }
    
    public function listarUsuarios($idTienda)
    {
        //TODO comprobar idTienda  de session es la misma que la del $idTienda
        $usuarios = $this->getDoctrine()->getRepository(Usuario::class)->findByIdtiendaFk($idTienda);
        return $this->render('usuario/listado.html.twig', [
            'usuarios' => $usuarios
        ]);
    }

    public function crearUsuario(Request $request){
        if ($request->getMethod() == 'POST') {
            $usuario = new Usuario();
            $usuario->setNombreusuario($request->request->get('nombre'));
            $usuario->setPassword(md5($request->request->get('password')));
            $usuario->setRol($request->request->get('rol'));
            $usuario->setIdtiendaFk($this->entityManager->getReference('App\Entity\Tienda', 1));

            $this->entityManager->persist($usuario);
            $this->entityManager->flush();

            return $this->redirectToRoute('clientViewUsuario', ['idUsuario' => $usuario->getIdusuario()]);
        } else {
            return $this->render('usuario/view.html.twig');
        }
    }


    public function verUsuario($idUsuario)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        //TODO comprobar idTienda de session es la misma que la del usuario
        return $this->render('usuario/view.html.twig', [
            'usuario' => $usuario
        ]);
    }


    public function modificarPerfil(Request $request, $idUsuario)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        //TODO comprobar idTienda  de session es la misma que la del usuario
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

   
}