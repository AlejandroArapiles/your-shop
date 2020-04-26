<?php

namespace App\Controller;

use App\Entity\Tienda;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TiendaController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * un usuario (administrador) previamente logado podrá modificar datos de su tienda.
     * @return void
     */
    public function modificarTienda(Request $request, $idTienda) {
        $tienda = $this->getDoctrine()->getRepository(Tienda::class)->findOneByIdtienda($idTienda);
        if (isset($tienda)) {
            $datos = json_decode($request->getContent());
            $tienda->setNombretienda($datos->nombre);
            $tienda->setCif($datos->cif);
            $tienda->setCorreocontacto($datos->correocontacto);

            $this->entityManager->persist($tienda);
            $this->entityManager->flush();
            
            return new JsonResponse(['result' => 'ok']);
        }
        
        return new JsonResponse(['result' => 'No existe']);
    }

}
