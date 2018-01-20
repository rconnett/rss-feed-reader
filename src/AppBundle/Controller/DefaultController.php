<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RSSFeed;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Rssfeed controller.
 */
class DefaultController extends Controller
{
    /**
     * We're only interested in the RSS feeds, so go to that controller index
     *
     * @Route("/", name="default_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('rssfeed_index');
    }

}
