<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Matches;
use AppBundle\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Operations;


use FOS\UserBundle\Model\User;
/**
 * Tournament controller.
 *
 * @Route("tournament")
 */
class TournamentController extends Controller
{
    /**
     * Lists all tournament entities.
     *
     * @Route("/", name="tournament_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tournaments = $em->getRepository('AppBundle:Tournament')->findAll();

        return $this->render('tournament/index.html.twig', array(
            'tournaments' => $tournaments,
        ));
    }

    /**
     * Creates a new tournament entity.
     *
     * @Route("/new", name="tournament_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $tournament = new Tournament();
        $form = $this->createForm('AppBundle\Form\TournamentType', $tournament);
        $form->handleRequest($request);






//        $em = $this->getDoctrine()->getManager();
//        $em->persist($wallet->setAmount($wallet->getAmount() - $tournament->getPrizePool()));
//        $em->flush();



        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $walletSenderId = $user->getWallet()->getId();
            $walletRecieverId = 2;
            $amount = $tournament -> getPrizePool();
            $do = $this->getDoctrine();
            $this->get('operations')->newAction($walletSenderId,$walletRecieverId,$amount,$do);


           $loggedUserId = $this->getUser()->getId();
           $tournament->setCreatorId($loggedUserId);


           $do->getManager()->persist($tournament);
           $do->getManager()->flush();

            return $this->redirectToRoute('tournament_show', array('id' => $tournament->getId()));
        }

        return $this->render('tournament/new.html.twig', array(
            'tournament' => $tournament,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tournament entity.
     *
     * @Route("/{id}", name="tournament_show")
     * @Method("GET")
     */
    public function showAction(Tournament $tournament, $id)
    {
        $deleteForm = $this->createDeleteForm($tournament);
        $repositoryTournament = $this->getDoctrine()->getRepository('AppBundle:Tournament');
        $tournament = $repositoryTournament->findOneBy(array('id'=>$id));
        $teams = $tournament->getTeams();

        return $this->render('tournament/show.html.twig', array(
            'tournament' => $tournament,
            'teams' => $teams,
            'delete_form' => $deleteForm->createView(),
            'id' => $id,
        ));
    }

    /**
     * Displays a form to edit an existing tournament entity.
     *
     * @Route("/{id}/edit", name="tournament_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tournament $tournament)
    {
        $deleteForm = $this->createDeleteForm($tournament);
        $editForm = $this->createForm('AppBundle\Form\TournamentType', $tournament);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tournament_edit', array('id' => $tournament->getId()));
        }

        return $this->render('tournament/edit.html.twig', array(
            'tournament' => $tournament,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Gives an opportunity to captain to choose which team to join.
     *
     * @Route("/{id}/join", name="tournament_join_select_team")
     * @Method("GET")
     */
    public function joinChooseTeamAction ($id){


        $repositoryTeam = $this->getDoctrine()->getRepository('AppBundle:Team');
        $loggedUserId = $this->getUser()->getId();
        $teamsOfLoggedUser = $repositoryTeam->findBy(array
                                                     ('creatorId'=>$loggedUserId));


        return $this->render('tournament/choose_team.html.twig', array(
            'teams' => $teamsOfLoggedUser,
            'id' => $id,
        ));

    }
    /**
     * Gives an opportunity to captain to choose which team to join.
     *
     * @Route("/{id}/join/{teamId}", name="tournament_join")
     * @Method("GET")
     */
    public function joinAction ($id, $teamId, $do = null){


        $repositoryTeam = $this->getDoctrine()->getRepository('AppBundle:Team');
        $repositoryTournament = $this->getDoctrine()->getRepository('AppBundle:Tournament');

        $team = $repositoryTeam->findOneBy(array('id'=>$teamId));
        $tournament = $repositoryTournament->findOneBy(array('id'=>$id));
        $tournament->addTeam($team);
        $team->addTournament($tournament);
        $em = $this->getDoctrine()->getManager();
        $em->persist($tournament);
        $em->persist($team);
        $em->flush();
        $joinedTeams = $tournament->getTeams();

        return $this->render('tournament/show.html.twig', array(
            'tournament' => $tournament,
            'teams' => $joinedTeams,
            'id' => $id,
        ));

    }
    /**
     * @Route("/{id}/generate")
     */
    public function generateAction($id){
        $repositoryTournament = $this->getDoctrine()->getRepository('AppBundle:Tournament');
        $tournament= $repositoryTournament->find($id);
        $teams = $tournament->getTeams();
        $em = $this ->getDoctrine()->getManager();

        for($k=0;$k < count($teams)-1 ;$k++){
            for($j=$k+1;$j < count($teams);$j++){
                $match = new Matches();
                $match->setTeam1($teams[$k]->getId());
                $match->setTeam2($teams[$j]->getId());
                $match->setTournamentId($id);
                $em->persist($match);
                $em->flush();

            }
        }
        return new Response('Matches schedule generated');
    }


    /**
     * Deletes a tournament entity.
     *
     * @Route("/{id}", name="tournament_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Tournament $tournament)
    {
        $form = $this->createDeleteForm($tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tournament);
            $em->flush();
        }

        return $this->redirectToRoute('tournament_index');
    }

    /**
     * Creates a form to delete a tournament entity.
     *
     * @param Tournament $tournament The tournament entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tournament $tournament)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tournament_delete', array('id' => $tournament->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
