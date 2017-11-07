<?php

namespace AppBundle\Controller;

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
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $nbPage = $em->getRepository('AppBundle:Article')->getNumberOfPage();
        if ($page > $nbPage && $nbPage > 0) {
            $page = $nbPage;
        } elseif ($page < 1) {
            $page = 1;
        }

        $articles = $em->getRepository('AppBundle:Article')->getPublishedArticles($page);

        return $this->render('default/index.html.twig', array(
            'articles' => $articles,
            'page' => $page,
            'nbPage' => $nbPage,
        ));
    }
}
