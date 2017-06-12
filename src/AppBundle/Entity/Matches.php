<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Matches
 *
 * @ORM\Table(name="matches")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MatchesRepository")
 */
class Matches
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="team1", type="integer")
     */
    private $team1;

    /**
     * @var int
     *
     * @ORM\Column(name="team2", type="integer")
     */
    private $team2;

    /**
     * @var string
     *
     * @ORM\Column(name="score1", type="string", length=255)
     */
    private $score1;

    /**
     * @var string
     *
     * @ORM\Column(name="score2", type="string", length=255)
     */
    private $score2;

    /**
     * @var string
     *
     * @ORM\Column(name="tournamentId", type="string", length=255, nullable=true)
     */
    private $tournamentId;
    /**
     * Many Matches have Many Teams.
     * @ORM\ManyToMany(targetEntity="Team", mappedBy="matches")
     */
    private $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();

    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set team1
     *
     * @param integer $team1
     * @return Matches
     */
    public function setTeam1($team1)
    {
        $this->team1 = $team1;

        return $this;
    }

    /**
     * Get team1
     *
     * @return integer 
     */
    public function getTeam1()
    {
        return $this->team1;
    }

    /**
     * Set team2
     *
     * @param integer $team2
     * @return Matches
     */
    public function setTeam2($team2)
    {
        $this->team2 = $team2;

        return $this;
    }

    /**
     * Get team2
     *
     * @return integer 
     */
    public function getTeam2()
    {
        return $this->team2;
    }

    /**
     * Set score1
     *
     * @param string $score1
     * @return Matches
     */
    public function setScore1($score1)
    {
        $this->score1 = $score1;

        return $this;
    }

    /**
     * Get score1
     *
     * @return string 
     */
    public function getScore1()
    {
        return $this->score1;
    }

    /**
     * Set score2
     *
     * @param string $score2
     * @return Matches
     */
    public function setScore2($score2)
    {
        $this->score2 = $score2;

        return $this;
    }

    /**
     * Get score2
     *
     * @return string 
     */
    public function getScore2()
    {
        return $this->score2;
    }

    /**
     * Set tournamentId
     *
     * @param string $tournamentId
     * @return Matches
     */
    public function setTournamentId($tournamentId)
    {
        $this->tournamentId = $tournamentId;

        return $this;
    }

    /**
     * Get tournamentId
     *
     * @return string 
     */
    public function getTournamentId()
    {
        return $this->tournamentId;
    }

    /**
     * Add teams
     *
     * @param \AppBundle\Entity\Team $teams
     * @return Matches
     */
    public function addTeam(\AppBundle\Entity\Team $teams)
    {
        $this->teams[] = $teams;

        return $this;
    }

    /**
     * Remove teams
     *
     * @param \AppBundle\Entity\Team $teams
     */
    public function removeTeam(\AppBundle\Entity\Team $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeams()
    {
        return $this->teams;
    }
}
