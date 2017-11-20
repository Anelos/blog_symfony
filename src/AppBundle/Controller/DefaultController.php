<?php

namespace AppBundle\Controller;

use AppBundle\Service\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



/**
 * Class DefaultController
 * @package AppBundle\Controller
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/{page}",
     *     defaults={"page": "1"},
     *     requirements={"page": "\d+"},
     *     name="homepage")
     * @Method("GET")
     */
    public function indexAction($page, Pagination $pagination)
    {
        $pagination->setEntityName('AppBundle:Article');
        $pagination->setPage($page);
        $pagination->setCriteria(array("published" => true));

        return $this->render('default/index.html.twig', array(
            'page' => $pagination->getPage(),
            'nbPage'=> $pagination->getNbPage(),
            'articles'=>$pagination->getQueryResult(),
        ));
    }
}
