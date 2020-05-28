<?php

namespace App\Controller;

use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


abstract class AuthAbstractController extends AbstractController
{
     /** @var EntityManagerInterface $entityManager */
     protected $entityManager;

     /** @var SerializerInterface $serializer */
     protected $serializer;

     /** @var AuthService $authService*/
     private $authService;

     public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, AuthService $authService) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->authService = $authService;
    }

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
