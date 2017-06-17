<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Invitations;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
/**
 * Team controller.
 *
 * @Route("team")
 */
class TeamController extends Controller
{
        /**
     * @Route("/team_debug")
     */
    public function debugAction()
    {
        $team = $this->getDoctrine()->getRepository('AppBundle:Team')->find(1);
        $user1 = new User();
        $user2 = new User();
        $team->addInvitedUser($user1);
        $team->addInvitedUser($user2);
        
        return $this->render('::debug.html.twig',['data'=>$team]);
    }
    
    /**
     * Lists all team entities.
     *
     * @Route("/", name="team_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $teams = $em->getRepository('AppBundle:Team')->findAll();

        return $this->render('team/index.html.twig', array(
            'teams' => $teams,
        ));
    }

    /**
     * Creates a new team entity.
     *
     * @Route("/new", name="team_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $team = new Team();
        $form = $this->createForm('AppBundle\Form\TeamType', $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $team->setCreatorId($this->getUser()->getId());
            
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('team_show', array('id' => $team->getId()));
        }

        return $this->render('team/new.html.twig', array(
            'team' => $team,
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/{id}/join")
     */
    public function joinTeamAction($id) {
        $repositoryTeam = $this->getDoctrine()->getRepository('AppBundle:Team');
        $team = $repositoryTeam->find($id);
        $user = $this->getUser();

        $team->addInvitedUser($user);
        $user->addInvitingTeam($team);

        $em = $this->getDoctrine()->getManager();
        $em->persist($team);
        $em->persist($user);
        $em->flush();

        return new Response('Join request has been sent to team '.$id);
    }
    
    /**
     * 
     * @Route("/{id}/pending")
     */
    public function pendingRequestsAction($id) {

        $repositoryTeam = $this->getDoctrine()->getRepository('AppBundle:Team');
        $invitedUsers = $repositoryTeam->find($id)->getInvitedUsers();

        return $this->render('team/pending.html.twig', array(
            'invitedUsers' => $invitedUsers,
            'id'=>$id
        ));


    }
    /**
     *
     * @Route("/{id}/accept/{userId}")
     */

    public function acceptRequestAction($id,$userId) {

        $repositoryTeam = $this->getDoctrine()->getRepository('AppBundle:Team');
        $repositoryUser = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repositoryUser->find($userId);
        $team = $repositoryTeam->find($id);

        $team->removeInvitedUser($user);
        $team->addUser($user);

        $user->removeInvitingTeam($team);
        $user->addTeam($team);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->persist($team);

        $em->flush();

        return $this->render('team/show.html.twig', array(
            'team' => $team,

        ));


        
    } /**
     *
     * @Route("/{id}/decline/{userId}")
     */

    public function declineRequestAction($id,$userId) {

        $repositoryTeam = $this->getDoctrine()->getRepository('AppBundle:Team');
        $repositoryUser = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repositoryUser->find($userId);
        $team = $repositoryTeam->find($id);

        $team->removeInvitedUser($user);
        $user->removeInvitingTeam($team);


        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->persist($team);

        $em->flush();

        return $this->render('team/show.html.twig', array(
            'team' => $team,

        ));



    }

    /**
     * Finds and displays a team entity.
     *
     * @Route("/{id}", name="team_show")
     * @Method("GET")
     */
    public function showAction(Team $team,$id)
    {
        $deleteForm = $this->createDeleteForm($team);

        return $this->render('team/show.html.twig', array(
            'team' => $team,
            'delete_form' => $deleteForm->createView(),


        ));
    }

    /**
     * Displays a form to edit an existing team entity.
     *
     * @Route("/{id}/edit", name="team_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Team $team)
    {
        $deleteForm = $this->createDeleteForm($team);
        $editForm = $this->createForm('AppBundle\Form\TeamType', $team);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('team_edit', array('id' => $team->getId()));
        }

        return $this->render('team/edit.html.twig', array(
            'team' => $team,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * @Route("/{id}/deposit")
     */
    public function depositTeamAction($id){

        $repositoryTeam = $this->getDoctrine()->getRepository('AppBundle:Team');
        $team = $repositoryTeam->find($id);
        $user = $this->getUser();
        $amount = 42;

        $walletSenderId = $user->getWallet()->getId();
        $walletRecieverId = $team->getWallet()->getId();

        $do = $this->getDoctrine();
        $this->get('operations')->newAction($walletSenderId,$walletRecieverId,$amount,$do);


        return $this->redirect('AppBundle:Team:show.html.twig');


    }
    /**
     * Deletes a team entity.
     *
     * @Route("/{id}", name="team_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Team $team)
    {
        $form = $this->createDeleteForm($team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($team);
            $em->flush();
        }

        return $this->redirectToRoute('team_index');
    }

    /**
     * Creates a form to delete a team entity.
     *
     * @param Team $team The team entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Team $team)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('team_delete', array('id' => $team->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    

}
