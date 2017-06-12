<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Operations;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Operation controller.
 *
 * @Route("operations")
 */
class OperationsController extends Controller
{
    /**
     * Lists all operation entities.
     *
     * @Route("/", name="operations_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $operations = $em->getRepository('AppBundle:Operations')->findAll();

        return $this->render('operations/index.html.twig', array(
            'operations' => $operations,
        ));
    }

    /**
     * Creates a new operation entity.
     *
     * @Route("/new", name="operations_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $operation = new Operation();
        $form = $this->createForm('AppBundle\Form\OperationsType', $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($operation);
            $em->flush();

            return $this->redirectToRoute('operations_show', array('id' => $operation->getId()));
        }

        return $this->render('operations/new.html.twig', array(
            'operation' => $operation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a operation entity.
     *
     * @Route("/{id}", name="operations_show")
     * @Method("GET")
     */
    public function showAction(Operations $operation)
    {
        $deleteForm = $this->createDeleteForm($operation);

        return $this->render('operations/show.html.twig', array(
            'operation' => $operation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing operation entity.
     *
     * @Route("/{id}/edit", name="operations_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Operations $operation)
    {
        $deleteForm = $this->createDeleteForm($operation);
        $editForm = $this->createForm('AppBundle\Form\OperationsType', $operation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('operations_edit', array('id' => $operation->getId()));
        }

        return $this->render('operations/edit.html.twig', array(
            'operation' => $operation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a operation entity.
     *
     * @Route("/{id}", name="operations_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Operations $operation)
    {
        $form = $this->createDeleteForm($operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($operation);
            $em->flush();
        }

        return $this->redirectToRoute('operations_index');
    }

    /**
     * Creates a form to delete a operation entity.
     *
     * @param Operations $operation The operation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Operations $operation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('operations_delete', array('id' => $operation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
