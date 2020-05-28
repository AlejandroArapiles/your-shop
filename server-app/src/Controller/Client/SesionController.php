<?php

namespace App\Controller\Client;

use App\Entity\Sesion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SesionController extends AbstractController {


    public function cerrarSesion($idSesion)
    {
        //$sesion = $this->getDoctrine()->getRepository(Sesion::class)->findByIdsesion($idTienda);
        return $this->render('sesion/modificar.html.twig', [
            //'tienda' => $tienda
        ]);
    }

    
}