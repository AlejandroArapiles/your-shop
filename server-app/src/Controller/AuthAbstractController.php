<?php

namespace App\Controller;

use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Clase que contiene validaciones a la hora de realizar operaciones
 */
abstract class AuthAbstractController extends AbstractController
{
     /** @var EntityManagerInterface $entityManager */
     protected $entityManager;

     /** @var SerializerInterface $serializer */
     protected $serializer;

     /** @var AuthService $authService*/
     protected $authService;

     public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, AuthService $authService) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->authService = $authService;
    }

    /**
     * Comprueba que el usuario tiene sesión iniciada y que pertenece a la tienda en la cual quiere realizar operaciones
     *
     * @param Request $request
     * @param integer $idTienda
     * @return void
     */
    public function validarMd5(Request $request, $idTienda) {
        $md5 = $request->query->get('token');
        if (isset($md5)) {
            $user = $this->authService->validateMd5ByIdTienda($md5, $idTienda);
            if (isset($user)) {
                return $user;
            }
        } else {
            throw new AccessDeniedHttpException('No existe el parámetro token');
        }
        throw new AccessDeniedHttpException('No tienes permiso');
    }
}
