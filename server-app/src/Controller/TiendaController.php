<?php

namespace App\Controller;

use App\Entity\Tienda;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TiendaController extends AbstractController
{

    
    public function insertTienda(EntityManagerInterface $entityManager) 
    {
        $tienda = new Tienda();
        $tienda->setNombreTienda("Amazon");
        $tienda->setCif("2abcdefgh");
        $tienda->setCorreoContacto("amazon@outlook.com");
        
        $entityManager->persist($tienda);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with name '.$tienda->getNombreTienda());
    }

}
