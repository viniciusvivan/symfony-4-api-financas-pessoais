<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait ResponseGenerator
 * @package App\Helper
 */
trait ResponseGeneratorTrait
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $itensByPage;

    /**
     * @var string
     */
    private $content;

    /**
     * @var bool
     */
    private $success;

    /**
     * @param bool $success
     * @param string $content
     * @param int $status
     * @param int|null $page
     * @param int|null $itensByPage
     * @return JsonResponse
     */
    public function generateResponse(
        bool $success,
        $content,
        int $status = JsonResponse::HTTP_OK,
        int $page = null,
        int $itensByPage = null
    ) {
        $this->status = $status;
        $this->content = $content;
        $this->page = $page;
        $this->itensByPage = $itensByPage;
        $this->success = $success;

        return $this->formatResponse();
    }

    /**
     * @return JsonResponse
     */
    public function formatResponse(): JsonResponse
    {
        $response = [
          'success' => $this->success,
          'page' => $this->page,
          'itens' => $this->itensByPage,
          'content' => $this->content
        ];

        if (is_null($this->page)) {
            unset($response['page']);
            unset($response['itens']);
        }

        return new JsonResponse($response, $this->status);
    }

    /**
     * @param $content
     * @return JsonResponse
     */
    public function successResponse($content)
    {
        return $this->generateResponse(
            true,
            $content,
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @param $content
     * @return JsonResponse
     */
    public function failResponse($content)
    {
        return $this->generateResponse(
            false,
            $content,
            JsonResponse::HTTP_NOT_FOUND
        );
    }

    /**
     * @param int $status
     * @return JsonResponse
     */
    public function emptyResponse(int $status)
    {
        return new JsonResponse('', $status);
    }
}
