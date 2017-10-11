<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\FileUploader;


/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{
    const AVATAR_DIR = "uploads/avatars/";

    /**
     * Lists all user entities.
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{slug}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{slug}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user, FileUploader $fileUploader)
    {
        $usr = $this->get('security.token_storage')->getToken()->getUser();;
        if (!($this->isGranted("ROLE_USER") && $usr->getId() == $user->getId() || $this->isGranted("ROLE_ADMIN"))) {
            throw $this->createAccessDeniedException();
        }


        $oldAvatar = $user->getAvatar();
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $file = $user->getAvatar();
            if (isset($file) && $file != "") {

                $fileName = $fileUploader->upload($file);

                $user->setAvatar(self::AVATAR_DIR . $fileName);
                $fileUploader->removeOldFile($oldAvatar);
            } else {
                $user->setAvatar($oldAvatar);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', array('slug' => $user->getSlug()));
        }


        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{slug}", name="user_delete")
     * @Method("DELETE")
     */
    public
    function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private
    function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('slug' => $user->getSlug())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
