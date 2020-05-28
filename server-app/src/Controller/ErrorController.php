<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ErrorController extends AbstractController {

    public function showError($exception) {
        return new JsonResponse(['error' => $exception->getMessage()]);
    }
}