<?php

namespace Abc\JobServerBundle\Controller;

use Abc\Job\Controller\CronJobController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CronJobController extends AbstractController
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
     * @Route("/cronjob", methods="GET")
     *
     * @param Request $request
     * @return Response
     */
    public function list(Request $request)
    {
        return $this->createResponse($this->controller->list($request->getQueryString(), $request->getUri()));
    }

    /**
     * @Route("/cronjob/{id}", methods="GET")
     *
     * @param string $id
     * @param Request $request
     * @return Response
     */
    public function find(string $id, Request $request)
    {
        return $this->createResponse($this->controller->find($id, $request->getUri()));
    }

    /**
     * @Route("/cronjob/{id}/results", methods="GET")
     *
     * @param string $id
     * @param Request $request
     * @return Response
     */
    public function results(string $id, Request $request)
    {
        return $this->createResponse($this->controller->results($id, $request->getUri()));
    }

    /**
     * @Route("/cronjob", methods="POST")
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        return $this->createResponse($this->controller->create($request->getContent(), $request->getUri()));
    }

    /**
     * @Route("/cronjob/{id}", methods="PUT")
     *
     * @param string $id
     * @param Request $request
     * @return Response
     */
    public function update(string $id, Request $request)
    {
        return $this->createResponse($this->controller->update($id, $request->getContent(), $request->getUri()));
    }

    /**
     * @Route("/cronjob/{id}", methods="DELETE")
     *
     * @param string $id
     * @param Request $request
     * @return Response
     */
    public function delete(string $id, Request $request)
    {
        return $this->createResponse($this->controller->delete($id, $request->getUri()));
    }
}
