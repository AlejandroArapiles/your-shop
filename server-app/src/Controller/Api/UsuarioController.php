<?php

namespace App\Controller\Api;

use App\Entity\Tienda;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\AuthAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * API
 * Clase que interactúa con la tabla Usuario en la base de datos
 */
class UsuarioController extends AuthAbstractController
{

    /**
     * Registra una nueva tienda y asigna un usuario administrador a esta.
     *
     * @param Request $request
     * @return void
     */
    public function registrarUsuarioTienda(Request $request) {
        if ($request->getMethod() == 'POST') {
            $datos = json_decode($request->getContent());
            $tienda = new Tienda();
            $tienda->setNombretienda($datos->idtiendaFk->nombretienda);
            $tienda->setCif($datos->idtiendaFk->cif);
            $tienda->setCorreoContacto($datos->idtiendaFk->correocontacto);
            $this->entityManager->persist($tienda);

            $usuario = new Usuario();
            $usuario->setNombreusuario($datos->nombreusuario);
            $usuario->setPassword(md5($datos->password));
            $usuario->setRol('admin');
            $usuario->setIdtiendaFk($tienda);

            $this->entityManager->persist($usuario);
            $this->entityManager->flush();
        
            $usuario = $this->serializer->normalize($usuario, null, ["groups" => "public"]);
            return new JsonResponse($usuario);
        }

    }

    /**
     * Lista todos los usuarios de la tienda la cual el usuario está logado. (solo admin)
     *
     * @param Request $request 
     * @param integer $idTienda Id de la tienda que lista los usuarios
     * @return JsonResponse
     */
    public function listarUsuario(Request $request, int $idTienda) :JsonResponse
    {
        $this->validarMd5($request, $idTienda);
        $nombre = $request->query->get("nombre");
        if (isset($nombre) && !empty($nombre)) {
            $usuarios = $this->getDoctrine()->getRepository(Usuario::class)->findAllByIdTiendaAndNombreUsuario($idTienda, $nombre);
        } else {
            $usuarios = $this->getDoctrine()->getRepository(Usuario::class)->findBy(["idtiendaFk" => $idTienda]);
        }
        $usuarios = $this->serializer->normalize($usuarios, null, ["groups" => "public"]);
        return new JsonResponse($usuarios);
    }
    /**
     * Inserta un usuario en la tienda la cual el usuario está logado. (solo admin)
     * 
     * @param Request $request
     * @return void
     */
    public function insertarUsuario(Request $request)
    {
        $datos = json_decode($request->getContent());
        $idTienda = $datos->idtiendaFk->idtienda;
        $this->validarMd5($request, $idTienda);
        $usuario = new Usuario();
        $usuario->setNombreUsuario($datos->nombreusuario);
        $usuario->setRol($datos->rol);
        $usuario->setPassword(md5($datos->password));
        $usuario->setIdtiendaFk($this->entityManager->getReference('App\Entity\Tienda', $idTienda));
        
        $this->entityManager->persist($usuario);
        $this->entityManager->flush();
        
        $usuario = $this->serializer->normalize($usuario, null, ["groups" => "public"]);
        return new JsonResponse($usuario);
    }

    /**
     * Elimina un usuario en la tienda la cual el usuario está logado. (solo admin)
     *
     * @param integer $idUsuario Id de usuario a eliminar
     * @return void
     */
    public function eliminarUsuario(int $idUsuario)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        $this->validarMd5($request, $usuario->getIdtiendaFk()->getIdtienda());
        if (isset($usuario)) {
            $this->entityManager->remove($usuario);
            $this->entityManager->flush();
            
            return new JsonResponse(null, Response::HTTP_OK);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Modifica un usuario en la tienda la cual el usuario está logado. (solo admin)
     *
     * @param Request $request
     * @param integer $idUsuario Id de usuario a modificar
     * @return void
     */
    public function modificarUsuario(Request $request, int $idUsuario)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        
        if (isset($usuario)) {
            $datos = json_decode($request->getContent());
            $usuario->setNombreusuario($datos->nombreusuario);
            if (!empty($datos->password)) {
                $usuario->setPassword(md5($datos->password));
            }
            $usuario->setRol($datos->rol);
            
            $this->entityManager->persist($usuario);
            $this->entityManager->flush();
            
            $usuario = $this->serializer->normalize($usuario, null, ["groups" => "public"]);

            return new JsonResponse($usuario, Response::HTTP_OK);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Modifica la contraseña del usuario logado si coinciden las dos introducidas
     *
     * @param Request $request
     * @param integer $idUsuario Id de usuario a modificar
     * @return void
     */
    public function modificarPerfil(Request $request, int $idUsuario)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        $this->validarMd5($request, $usuario->getIdtiendaFk()->getIdtienda());
        if (isset($usuario)) {
            $datos = json_decode($request->getContent());
            if (!empty($datos->password)) {
                $usuario->setPassword(md5($datos->password));
            }
        
            $this->entityManager->persist($usuario);
            $this->entityManager->flush();
            
            return new JsonResponse($usuario, Response::HTTP_OK);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Lista la información de un usuario recibiendo su id por parámetro (solo admin)
     *
     * @param integer $idUsuario Id del usuario a listar la información
     * @return void
     */
    public function verUsuario(int $idUsuario)
    {
        if (isset($idUsuario) && !empty($idUsuario)) {
            $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        }
        $this->validarMd5($request, $usuario->getIdtiendaFk()->getIdtienda());
        $usuario = $this->serializer->normalize($usuario, null, ["groups" => "public"]);
        return new JsonResponse($usuario);
    }

    /**
     * Muestra la información del usuario logado
     *
     * @param Request $request
     * @return void
     */
    public function verPerfil(Request $request)
    {
        $idUsuario = $request->query->get("idusuario");
        if (isset($idUsuario) && !empty($idUsuario)) {
            $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        }
        $this->validarMd5($request, $usuario->getIdtiendaFk()->getIdtienda());
        $usuario = $this->serializer->normalize($usuario, null, ["groups" => "public"]);
        return new JsonResponse($usuario);
    }

    
}
