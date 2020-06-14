<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Clase que muestra los errores 
 */
class ErrorController extends AbstractController {

    /**
     * Muestra al usuario la excepción y el mensaje del error
     *
     * @param Exception $exception
     * @return void
     */
    public function showError($exception) {
        return new JsonResponse(['error' => $exception->getMessage()]);
    }
}