<?php
namespace App\Controller;
use Library\MD\Controller\ActionControllerInterface,
	Library\MD\Controller\Request\RequestInterface,
    Library\MD\Controller\Response\ResponseInterface;

class ErrorController implements ActionControllerInterface
{
    public function execute(RequestInterface $request, ResponseInterface $response) {
        echo "Error controller called!";
    }
}