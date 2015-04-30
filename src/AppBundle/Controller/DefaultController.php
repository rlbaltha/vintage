<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/home", name="homepage")
     */
    public function homeAction()
    {
        return $this->render('@App/Home/home.html.twig');
    }

    /**
     * @Route("/suggest", name="suggestpage")
     */
    public function suggestAction()
    {
        return $this->render('@App/Home/suggest.html.twig');
    }
}
