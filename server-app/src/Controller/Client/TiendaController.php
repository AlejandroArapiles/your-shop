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

class TiendaController extends AuthAbstractController {

    /** @var SessionInterface $sessionManager */
    protected $sessionManager;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer,
    AuthService $authService, SessionInterface $sessionManager) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
    }

    

    public function modificarTienda(Request $request, $idTienda)
    {
        if (!$this->authService->validateUserLogged()) {
            return $this->redirectToRoute('clientLogin');
        }
        $tienda = $this->getDoctrine()->getRepository(Tienda::class)->findOneByIdtienda($idTienda);
        if (!isset($tienda) || $this->sessionManager->get('user')['idTienda'] != $idTienda) {
            throw new AccessDeniedHttpException("Esta tienda no pertenece a este usuario");
        }
        if ($request->getMethod() == 'POST') {
            $tienda->setNombretienda($request->request->get('nombre'));
            $tienda->setCif($request->request->get('cif'));
            $tienda->setCorreocontacto($request->request->get('correocontacto'));

            $this->entityManager->persist($tienda);
            $this->entityManager->flush();
            
        }
        return $this->render('tienda/modificar.html.twig', [
            'tienda' => $tienda
        ]);
    }

    
}