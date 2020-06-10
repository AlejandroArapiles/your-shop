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

class UsuarioController extends AuthAbstractController
{

    public function registrarUsuarioTienda(Request $request) {
        if ($request->getMethod() == 'POST') {
            $datos = json_decode($request->getContent());
            $tienda = new Tienda();
            $tienda->setNombretienda($datos->idtiendaFk->nombre);
            $tienda->setCif($datos->idtiendaFk->cif);
            $tienda->setCorreoContacto($datos->idtiendaFk->correocontacto);
            $this->entityManager->persist($tienda);

            $usuario = new Usuario();
            $usuario->setNombreusuario($datos->nombreUsuario);
            $usuario->setPassword(md5($datos->password));
            $usuario->setRol('admin');
            $usuario->setIdtiendaFk($tienda);

            $this->entityManager->persist($usuario);
            $this->entityManager->flush();
        
            $usuario = $this->serializer->normalize($usuario, null, ["groups" => "public"]);
            return new JsonResponse($usuario);
        }

    }

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

    public function eliminarUsuario(int $idUsuario)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        if (isset($usuario)) {
            $this->entityManager->remove($usuario);
            $this->entityManager->flush();
            
            return new JsonResponse(null, Response::HTTP_OK);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

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

    public function modificarPerfil(Request $request, int $idUsuario)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
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

    public function verUsuario($idUsuario)
    {
        if (isset($idUsuario) && !empty($idUsuario)) {
            $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        }
        $usuario = $this->serializer->normalize($usuario, null, ["groups" => "public"]);
        return new JsonResponse($usuario);
    }

    public function verPerfil(Request $request)
    {
        //TODO coger idUsuario desde el token
        $idUsuario = $request->query->get("idusuario");
        if (isset($idUsuario) && !empty($idUsuario)) {
            $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        }
        $usuario = $this->serializer->normalize($usuario, null, ["groups" => "public"]);
        return new JsonResponse($usuario);
    }
}
