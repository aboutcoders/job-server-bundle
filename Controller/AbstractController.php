<?php

namespace Abc\JobServerBundle\Controller;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class AbstractController
{
    protected function createResponse(ResponseInterface $response): Response
    {
        return new Response($response->getBody(), $response->getStatusCode(), $response->getHeaders());
    }
}
