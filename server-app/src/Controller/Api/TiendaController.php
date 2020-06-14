<?php

namespace App\Controller\Api;

use App\Entity\Tienda;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * API
 * Clase que interactúa con la tabla Tienda en la base de datos
 */
class TiendaController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * un usuario (solo admin) previamente logado podrá modificar datos de su tienda.
     * @return void
     */
    public function modificarTienda(Request $request, $idTienda) {
        $tienda = $this->getDoctrine()->getRepository(Tienda::class)->findOneByIdtienda($idTienda);
        $this->validarMd5($request, $idTienda);
        if (isset($tienda)) {
            $datos = json_decode($request->getContent());
            $tienda->setNombretienda($datos->nombretienda);
            $tienda->setCif($datos->cif);
            $tienda->setCorreocontacto($datos->correocontacto);

            $this->entityManager->persist($tienda);
            $this->entityManager->flush();
            
            $tienda = $this->serializer->normalize($tienda, null, ["groups" => "public"]);

            return new JsonResponse($tienda);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

}
