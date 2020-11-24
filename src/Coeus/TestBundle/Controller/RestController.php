<?php


namespace App\Coeus\TestBundle\Controller;


use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\BrowserKit\Request;

class RestController extends AbstractFOSRestController
{
    /**
     * Here goes our route
     * @Get("/get/coeus")
     */
    public function restGetAction(Request $request)
    {
        // Do something with your Request object
        $data = array(
            "name" => "coeus",
            "extra" => "Is awesome!"
        );
        $view = $this->view($data, 200);
        return $this->handleView($view);
    }
}