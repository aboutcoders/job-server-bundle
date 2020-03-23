<?php

namespace Abc\JobServerBundle\Controller;

use Abc\Job\Controller\BrokerController as DecoratedController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrokerController extends AbstractController
{
    /**
     * @var DecoratedController
     */
    private $controller;

    public function __construct(DecoratedController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @Route("/broker/{name}/setup", methods="POST")
     *
     * @param string $name
     * @param Request $request
     * @return Response
     */
    public function setup(string $name, Request $request)
    {
        return $this->createResponse($this->controller->setup($name, $request->getUri()));
    }
}
