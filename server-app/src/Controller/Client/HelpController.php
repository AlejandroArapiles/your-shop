<?php

namespace App\Controller\Client;

use App\Entity\Tienda;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\AuthAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * CLIENT
 * Clase que muestra la ayuda en la aplicación cliente
 */
class HelpController extends AuthAbstractController {

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
     * Carga el código html que contiene la ayuda
     *
     * @return void
     */
    public function mostrarAyuda(){
        return $this->render('help/ayuda.html.twig');
    }    
}