<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Operations;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function newAction($senderWalletId,$receiverWalletId,$amount,$do = null)
    {
        $operation = new Operations();
        $operation->setAmount($amount);
        $operation->setReceiverWalletId($receiverWalletId);
        $operation->setSenderWalletId($senderWalletId);
        $do = $do?:$this->getDoctrine();

        //getting actual wallet amounts for sender and receiver
        $repository = $do->getRepository('AppBundle:Wallet');
        $senderAmount = $repository->find($senderWalletId)->getAmount();
        $receiverAmount = $repository->find($receiverWalletId)->getAmount();

        //check if sender has enough credits in his wallet to conclude operation
        if ($senderAmount < $amount) {
            return new Response('Not enough credits in your wallet');
        }
        $newWalletSender = $do->getRepository('AppBundle:Wallet')->find($senderWalletId);
        $newWalletSender->setAmount($senderAmount - $amount);

        $newWalletReceiver = $do->getRepository('AppBundle:Wallet')->find($receiverWalletId);
        $newWalletReceiver->setAmount($receiverAmount + $amount);

        $do->getManager()->persist($operation);
        $do->getManager()->flush();

//            $em->persist($operation);
//            $em->flush();
//            //geting last operation id
//            $query = $em->createQuery('SELECT MAX(w.id) FROM AppBundle:Operations w');
//            $lastOperationId = $query->getResult()[0][1];
//        //setting variables of last operation details
//        $repository = $this->getDoctrine()->getRepository('AppBundle:Operations');
//        $lastOperation = $repository->find($lastOperationId);
//        $senderWalletId = $lastOperation->getSenderWalletId();
//        $receiverWalletId = $lastOperation->getReceiverWalletId();
//        $amount = $lastOperation->getAmount();
//        //set new values of wallets sender and receiver
//            $em->flush();

//            return $this->redirectToRoute('operations_show', array('id' => $operation->getId()));


        return $amount;
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
