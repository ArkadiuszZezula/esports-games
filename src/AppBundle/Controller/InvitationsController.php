<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invitations;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Invitation controller.
 *
 * @Route("invitations")
 */
class InvitationsController extends Controller
{
    /**
     * Lists all invitation entities.
     *
     * @Route("/", name="invitations_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $invitations = $em->getRepository('AppBundle:Invitations')->findAll();

        return $this->render('invitations/index.html.twig', array(
            'invitations' => $invitations,
        ));
    }

    /**
     * Creates a new invitation entity.
     *
     * @Route("/new", name="invitations_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $invitation = new Invitation();
        $form = $this->createForm('AppBundle\Form\InvitationsType', $invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($invitation);
            $em->flush();

            return $this->redirectToRoute('invitations_show', array('id' => $invitation->getId()));
        }

        return $this->render('invitations/new.html.twig', array(
            'invitation' => $invitation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a invitation entity.
     *
     * @Route("/{id}", name="invitations_show")
     * @Method("GET")
     */
    public function showAction(Invitations $invitation)
    {
        $deleteForm = $this->createDeleteForm($invitation);

        return $this->render('invitations/show.html.twig', array(
            'invitation' => $invitation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing invitation entity.
     *
     * @Route("/{id}/edit", name="invitations_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Invitations $invitation)
    {
        $deleteForm = $this->createDeleteForm($invitation);
        $editForm = $this->createForm('AppBundle\Form\InvitationsType', $invitation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('invitations_edit', array('id' => $invitation->getId()));
        }

        return $this->render('invitations/edit.html.twig', array(
            'invitation' => $invitation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a invitation entity.
     *
     * @Route("/{id}", name="invitations_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Invitations $invitation)
    {
        $form = $this->createDeleteForm($invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($invitation);
            $em->flush();
        }

        return $this->redirectToRoute('invitations_index');
    }

    /**
     * Creates a form to delete a invitation entity.
     *
     * @param Invitations $invitation The invitation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Invitations $invitation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('invitations_delete', array('id' => $invitation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
