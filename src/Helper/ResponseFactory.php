<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    private bool $success;
    private ?int $page;
    private ?int $itemsPerPage;
    private string|array|object|null $content;
    private int $statusCode;

    /**
     * @param bool $success
     * @param array|object|string|null $content
     * @param int $statusCode
     * @param int|null $page
     * @param int|null $itemsPerPage
     */
    public function __construct(bool $success,
                                array|object|null|string $content,
                                int $statusCode = Response::HTTP_OK,
                                int $page = null,
                                int $itemsPerPage = null)
    {
        $this->success = $success;
        $this->page = $page;
        $this->itemsPerPage = $itemsPerPage;
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    public function getResponse(): JsonResponse
    {
        $response  =[
            'success' => $this->success,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'content' => $this->content
        ];
        if (!$this->page) {
            unset($response['page']);
            unset($response['itemsPerPage']);
        }

        return new JsonResponse($response, $this->statusCode);
    }
}