<?php

namespace Hexmedia\AsseticBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HexmediaAsseticBundle:Default:index.html.twig', array('name' => $name));
    }
}
