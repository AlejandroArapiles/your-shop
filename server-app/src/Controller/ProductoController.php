<?php

namespace App\Controller;

use App\Entity\Tienda;
use App\Entity\Producto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductoController extends AbstractController
{
    public function insertProducto(EntityManagerInterface $entityManager) 
    {
        $producto = new Producto();
        $producto->setNombreproducto("prueba2");
        $producto->setCantidad("2");
        $producto->setPrecio("1");
        $producto->setActivo("1");
        $producto->setIdtiendaFk($entityManager->getReference('App\Entity\Tienda', 1));

        $entityManager->persist($producto);
        $entityManager->flush();

        return new Response('Saved new product with id '.$producto->getIdproducto());

    }
}
