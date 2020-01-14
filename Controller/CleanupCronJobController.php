<?php

namespace Abc\JobServerBundle\Controller;

use Abc\Job\Controller\CleanupCronJobController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CleanupCronJobController extends AbstractController
{
    /**
     * @var CleanupCronJobController
     */
    private $controller;

    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @Route("/cronjob", methods="DELETE")
     *
     * @param Request $request
     * @return Response
     */
    public function list(Request $request)
    {
        return $this->createResponse($this->controller->execute($request->getUri()));
    }
}
