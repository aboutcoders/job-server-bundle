<?php

namespace Abc\JobServerBundle\Controller;

use Abc\Job\Controller\JobController as Controller;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    /**
     * @var Controller
     */
    private $controller;

    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @Route("/job", methods="GET")
     *
     * @param Request $request
     * @return Response
     */
    public function list(Request $request)
    {
        $request = new \GuzzleHttp\Psr7\Request($request->getMethod(), $request->getUri(), $request->headers->all(), $request->getContent(), $request->getProtocolVersion());

        return $this->createResponse($this->controller->list($request->getQueryString(), $request->getUri()));
    }

    /**
     * @Route("/job", methods="POST")
     *
     * @param Request $request
     * @return Response
     */
    public function process(Request $request)
    {
        return $this->createResponse($this->controller->process($request->getContent(), $request->getUri()));
    }

    /**
     * @Route("/job/{id}", methods="GET")
     *
     * @param string $id
     * @return Response
     */
    public function result(string $id, Request $request): Response
    {
        return $this->createResponse($this->controller->result($id, $request->getUri()));
    }

    /**
     * @Route("/job/{id}/restart", methods="PUT")
     *
     * @param string $id
     * @param Request $request
     * @return Response
     */
    public function restart(string $id, Request $request): Response
    {
        return $this->createResponse($this->controller->restart($id, $request->getUri()));
    }

    /**
     * @Route("/job/{id}/cancel", methods="PUT")
     *
     * @param string $id
     * @param Request $request
     * @return Response
     */
    public function cancel(string $id, Request $request): Response
    {
        return $this->createResponse($this->controller->cancel($id, $request->getUri()));
    }

    /**
     * @Route("/job/{id}", methods="DELETE")
     *
     * @param string $id
     * @param Request $request
     * @return Response
     */
    public function delete(string $id, Request $request): Response
    {
        return $this->createResponse($this->controller->delete($id, $request->getUri()));
    }
}
