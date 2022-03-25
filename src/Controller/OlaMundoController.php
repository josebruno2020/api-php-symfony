<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OlaMundoController
{

    /**
     * @Route ("/ola")
     */
    public function olaMundo(Request $request): Response
    {
        return new JsonResponse(["mensagem" => "ola mundo", "query" => $request->get('query')]);
    }
}