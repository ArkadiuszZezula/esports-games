<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MatchDate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Matchdate controller.
 *
 * @Route("matchdate")
 */
class MatchDateController extends Controller
{
    /**
     * Lists all matchDate entities.
     *
     * @Route("/", name="matchdate_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $matchDates = $em->getRepository('AppBundle:MatchDate')->findAll();

        return $this->render('matchdate/index.html.twig', array(
            'matchDates' => $matchDates,
        ));
    }

    /**
     * Creates a new matchDate entity.
     *
     * @Route("/new", name="matchdate_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $matchDate = new Matchdate();
        $form = $this->createForm('AppBundle\Form\MatchDateType', $matchDate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($matchDate);
            $em->flush();

            return $this->redirectToRoute('matchdate_show', array('id' => $matchDate->getId()));
        }

        return $this->render('matchdate/new.html.twig', array(
            'matchDate' => $matchDate,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a matchDate entity.
     *
     * @Route("/{id}", name="matchdate_show")
     * @Method("GET")
     */
    public function showAction(MatchDate $matchDate)
    {
        $deleteForm = $this->createDeleteForm($matchDate);

        return $this->render('matchdate/show.html.twig', array(
            'matchDate' => $matchDate,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing matchDate entity.
     *
     * @Route("/{id}/edit", name="matchdate_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MatchDate $matchDate)
    {
        $deleteForm = $this->createDeleteForm($matchDate);
        $editForm = $this->createForm('AppBundle\Form\MatchDateType', $matchDate);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('matchdate_edit', array('id' => $matchDate->getId()));
        }

        return $this->render('matchdate/edit.html.twig', array(
            'matchDate' => $matchDate,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a matchDate entity.
     *
     * @Route("/{id}", name="matchdate_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MatchDate $matchDate)
    {
        $form = $this->createDeleteForm($matchDate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($matchDate);
            $em->flush();
        }

        return $this->redirectToRoute('matchdate_index');
    }

    /**
     * Creates a form to delete a matchDate entity.
     *
     * @param MatchDate $matchDate The matchDate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MatchDate $matchDate)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('matchdate_delete', array('id' => $matchDate->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
