<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Service\LikeManager;
use AppBundle\Service\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\TagConverter;

/**
 * Article controller.
 *
 * @Route("article")
 */
class ArticleController extends Controller
{
    /**
     * Lists all article entities.
     *
     * @Route("/{page}",
     *     requirements={"page": "\d+"},
     *     name="article_index")
     * @Method("GET")
     */
    public function indexAction($page = 1, Pagination $pagination)
    {
        $pagination->setEntityName('AppBundle:Article');
        $pagination->setPage($page);

        return $this->render('article/index.html.twig', array(
            'page' => $pagination->getPage(),
            'nbPage'=> $pagination->getNbPage(),
            'articles'=>$pagination->getQueryResult(),
        ));
    }

    /**
     * Creates a new article entity.
     *
     * @Route("/new", name="article_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $usr = $this->get('security.token_storage')->getToken()->getUser();
        $article = new Article();
        $form = $this->createForm('AppBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setAuthor($usr);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('article_show', array('slug' => $article->getSlug()));
        }

        return $this->render('article/new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a article entity.
     *
     * @Route("/{slug}", name="article_show")
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);

        return $this->render('article/show.html.twig', array(
            'article' => $article,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/{slug}/edit", name="article_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Article $article, TagConverter $converter)
    {
        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('AppBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $article = $converter->tagFindOrCreate($article);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_edit', array('slug' => $article->getSlug()));
        }

        return $this->render('article/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a article entity.
     *
     * @Route("/{slug}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Article $article)
    {
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('article_index');
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Article $article The article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('slug' => $article->getSlug())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/{id}/like", name="article_like")
     * @Method({"GET", "POST"})
     */
    public function likeAction(Article $article, LikeManager $likeManager)
    {
        if ($this->isGranted('ROLE_USER')) {
            $usr = $this->get('security.token_storage')->getToken()->getUser();
            $article = $likeManager->manageLike($article, $usr);
        }

        return $this->redirectToRoute('article_show', array('slug' => $article->getSlug()));

    }


}
