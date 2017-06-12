<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Matches;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Match controller.
 *
 * @Route("matches")
 */
class MatchesController extends Controller
{
    /**
     * Lists all match entities.
     *
     * @Route("/", name="matches_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $matches = $em->getRepository('AppBundle:Matches')->findAll();

        return $this->render('matches/index.html.twig', array(
            'matches' => $matches,
        ));
    }

    /**
     * Creates a new match entity.
     *
     * @Route("/new", name="matches_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $match = new Match();
        $form = $this->createForm('AppBundle\Form\MatchesType', $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($match);
            $em->flush();

            return $this->redirectToRoute('matches_show', array('id' => $match->getId()));
        }

        return $this->render('matches/new.html.twig', array(
            'match' => $match,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a match entity.
     *
     * @Route("/{id}", name="matches_show")
     * @Method("GET")
     */
    public function showAction(Matches $match)
    {
        $deleteForm = $this->createDeleteForm($match);

        return $this->render('matches/show.html.twig', array(
            'match' => $match,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing match entity.
     *
     * @Route("/{id}/edit", name="matches_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Matches $match)
    {
        $deleteForm = $this->createDeleteForm($match);
        $editForm = $this->createForm('AppBundle\Form\MatchesType', $match);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('matches_edit', array('id' => $match->getId()));
        }

        return $this->render('matches/edit.html.twig', array(
            'match' => $match,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a match entity.
     *
     * @Route("/{id}", name="matches_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Matches $match)
    {
        $form = $this->createDeleteForm($match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($match);
            $em->flush();
        }

        return $this->redirectToRoute('matches_index');
    }

    /**
     * Creates a form to delete a match entity.
     *
     * @param Matches $match The match entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Matches $match)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('matches_delete', array('id' => $match->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
