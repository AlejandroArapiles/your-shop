<?php

namespace App\Controller\Api;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Controller\AuthAbstractController;

class UsuarioController extends AuthAbstractController
{

    public function registrarUsuarioTienda(Request $request) {

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
        return new JsonResponse(['data' => $usuarios]);
    }

    public function insertarUsuario(Request $request)
    {
        $usuario = new Usuario();
        $datos = json_decode($request->getContent());
        $usuario->setNombreUsuario($datos->nombre);
        $usuario->setRol($datos->rol);
        $usuario->setPassword(md5($datos->password));
        $usuario->setIdtiendaFk($this->entityManager->getReference('App\Entity\Tienda', $datos->tienda));
        
        $this->entityManager->persist($usuario);
        $this->entityManager->flush();
        
        $usuario = $this->serializer->normalize($usuario, null, ["groups" => "public"]);
        return new JsonResponse(['data' => $usuario]);
    }

    public function eliminarUsuario(int $idUsuario)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        if (isset($usuario)) {
            $this->entityManager->remove($usuario);
            $this->entityManager->flush();
            
            return new JsonResponse(['result' => 'ok']);
        }
        
        return new JsonResponse(['result' => 'No existe']);
    }

    public function modificarUsuario(Request $request, int $idUsuario)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        if (isset($usuario)) {
            $datos = json_decode($request->getContent());
            $usuario->setNombreusuario($datos->nombreusuario);
            $usuario->setPassword(md5($datos->password));
            $usuario->setRol($datos->rol);
            
            $this->entityManager->persist($usuario);
            $this->entityManager->flush();
            
            return new JsonResponse(['result' => 'ok']);
        }
        
        return new JsonResponse(['result' => 'No existe']);
    }

    public function modificarPerfil(Request $request, int $idUsuario)
    {
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        if (isset($usuario)) {
            $datos = json_decode($request->getContent());
            $usuario->setPassword(md5($datos->password));
        
            $this->entityManager->persist($usuario);
            $this->entityManager->flush();
            
            return new JsonResponse(['result' => 'ok']);
        }
        
        return new JsonResponse(['result' => 'No existe']);
    }

    public function verPerfil(Request $request)
    {
        $idUsuario = $request->query->get("idusuario");
        if (isset($idUsuario) && !empty($idUsuario)) {
            $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneByIdusuario($idUsuario);
        }
        $usuario = $this->serializer->normalize($usuario, null, ["groups" => "public"]);
        return new JsonResponse(['data' => $usuario]);
    }
}
