<?php

namespace Abc\JobServerBundle\Controller;

use \Abc\Job\Controller\CronJobController as Controller;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CronJobController extends AbstractController
{
    /**
     * @var Controller
     */
    private $cronJobController;

    public function __construct(Controller $cronJobController)
    {
        $this->cronJobController = $cronJobController;
    }

    /**
     * @Route("/cronjob", methods="GET")
     *
     * @param Request $request
     * @return Response
     */
    public function list(Request $request)
    {
        return $this->createResponse($this->cronJobController->list($request->getQueryString(), $request->getUri()));
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
        return $this->createResponse($this->cronJobController->find($id, $request->getUri()));
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
        return $this->createResponse($this->cronJobController->results($id, $request->getUri()));
    }

    /**
     * @Route("/cronjob", methods="POST")
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        return $this->createResponse($this->cronJobController->create($request->getContent(), $request->getUri()));
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
        return $this->createResponse($this->cronJobController->update($id, $request->getContent(), $request->getUri()));
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
        return $this->createResponse($this->cronJobController->delete($id, $request->getUri()));
    }

    private function createResponse(ResponseInterface $response): Response
    {
        return new Response($response->getBody(), $response->getStatusCode(), $response->getHeaders());
    }
}
