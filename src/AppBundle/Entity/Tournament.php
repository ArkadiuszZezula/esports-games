<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tournament
 *
 * @ORM\Table(name="tournament")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TournamentRepository")
 */
class Tournament
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
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="buyIn", type="string", length=255)
     */
    private $buyIn;

    /**
     * @var string
     *
     * @ORM\Column(name="prizePool", type="string", length=255)
     */
    private $prizePool;

    /**
     * @var string
     *
     * @ORM\Column(name="creatorId", type="string", length=255, nullable=true)
     */
    private $creatorId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="invitationLink", type="string", length=255)
     */
    private $invitationLink;

    /**
     * @var string
     *
     * @ORM\Column(name="logoLink", type="string", length=255)
     */
    private $logoLink;
    /**
     * Many Teams have Many Tournaments.
     * @ORM\ManyToMany(targetEntity="Team", mappedBy="tournaments")
     * @ORM\JoinTable(name="teams_tournaments")
     */
    private $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->status = 1;
        $this->setLogoLink('/images/samplelogo.jpg');
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
     * Set status
     *
     * @param string $status
     * @return Tournament
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set buyIn
     *
     * @param string $buyIn
     * @return Tournament
     */
    public function setBuyIn($buyIn)
    {
        $this->buyIn = $buyIn;

        return $this;
    }

    /**
     * Get buyIn
     *
     * @return string 
     */
    public function getBuyIn()
    {
        return $this->buyIn;
    }

    /**
     * Set prizePool
     *
     * @param string $prizePool
     * @return Tournament
     */
    public function setPrizePool($prizePool)
    {
        $this->prizePool = $prizePool;

        return $this;
    }

    /**
     * Get prizePool
     *
     * @return string 
     */
    public function getPrizePool()
    {
        return $this->prizePool;
    }

    /**
     * Set creatorId
     *
     * @param string $creatorId
     * @return Tournament
     */
    public function setCreatorId($creatorId)
    {
        $this->creatorId = $creatorId;

        return $this;
    }

    /**
     * Get creatorId
     *
     * @return string 
     */
    public function getCreatorId()
    {
        return $this->creatorId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Tournament
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set invitationLink
     *
     * @param string $invitationLink
     * @return Tournament
     */
    public function setInvitationLink($invitationLink)
    {
        $this->invitationLink = $invitationLink;

        return $this;
    }

    /**
     * Get invitationLink
     *
     * @return string 
     */
    public function getInvitationLink()
    {
        return $this->invitationLink;
    }

    /**
     * Set logoLink
     *
     * @param string $logoLink
     * @return Tournament
     */
    public function setLogoLink($logoLink)
    {
        $this->logoLink = $logoLink;

        return $this;
    }

    /**
     * Get logoLink
     *
     * @return string 
     */
    public function getLogoLink()
    {
        return $this->logoLink;
    }

    /**
     * Add teams
     *
     * @param \AppBundle\Entity\Team $teams
     * @return Tournament
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
