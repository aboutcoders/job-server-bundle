<?php

namespace Abc\JobServerBundle\Controller;

use Abc\Job\HttpRouteServer;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RouteController extends AbstractController
{
    /**
     * @var HttpRouteServer
     */
    private $httpRouteServer;

    public function __construct(HttpRouteServer $httpRouteServer)
    {
        $this->httpRouteServer = $httpRouteServer;
    }

    /**
     * @Route("/route", methods="GET")
     *
     * @param Request $request
     * @return Response
     */
    public function all(Request $request)
    {
        return $this->createResponse($this->httpRouteServer->all($request->getUri()));
    }

    /**
     * @Route("/route", methods="POST")
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        return $this->createResponse($this->httpRouteServer->create($request->getContent(), $request->getUri()));
    }

    private function createResponse(ResponseInterface $response): Response
    {
        return new Response($response->getBody(), $response->getStatusCode(), $response->getHeaders());
    }
}
