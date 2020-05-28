<?php

namespace App\Controller\Client;

use App\Entity\Tienda;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TiendaController extends AbstractController {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    

    public function modificarTienda(Request $request, $idTienda)
    {
        //TODO comprobar idTienda de session es la misma que la del $idTienda
        $tienda = $this->getDoctrine()->getRepository(Tienda::class)->findOneByIdtienda($idTienda);
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