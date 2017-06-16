<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Wallet;


/**
 * Team
 *
 * @ORM\Table(name="team")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeamRepository")
 */
class Team
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="invitationURL", type="string", length=255, unique=true)
     */
    private $invitationURL;

    /**
     * @var int
     *
     * @ORM\Column(name="creatorId", type="integer", unique=false)
     */
    private $creatorId;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="logoLink", type="string", length=255)
     */
    private $logoLink;
    /**
     * Many Teams have Many Tournaments.
     * @ORM\ManyToMany(targetEntity="Tournament", inversedBy="teams")
     * @ORM\JoinTable(name="teams_tournaments")
     */
    private $tournaments;

    /**
     * Many Teams have Many Users.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="teams")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="invitingTeams")
     */
    private $invitedUsers;
    
    /**
     * Many Teams have Many Matches.
     * @ORM\ManyToMany(targetEntity="Matches", inversedBy="teams")
     * @ORM\JoinTable(name="teams_matches")
     */
    private $matches;


    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
        $this->wallet = new Wallet();
        $this->users = new ArrayCollection();
        $this->invitedUsers = new ArrayCollection();
        $this->matches = new ArrayCollection();
        $this->status = 1;

    }
    /**
     * One Product has One Shipment.
     * @ORM\OneToOne(targetEntity="Wallet", cascade={"persist"})
     * @ORM\JoinColumn(name="wallet_id", referencedColumnName="id")
     */
    private $wallet;



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
     * Set name
     *
     * @param string $name
     * @return Team
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
     * Set invitationURL
     *
     * @param string $invitationURL
     * @return Team
     */
    public function setInvitationURL($invitationURL)
    {
        $this->invitationURL = $invitationURL;

        return $this;
    }

    /**
     * Get invitationURL
     *
     * @return string 
     */
    public function getInvitationURL()
    {
        return $this->invitationURL;
    }

    /**
     * Set creatorId
     *
     * @param integer $creatorId
     * @return Team
     */
    public function setCreatorId($creatorId)
    {
        $this->creatorId = $creatorId;

        return $this;
    }

    /**
     * Get creatorId
     *
     * @return integer 
     */
    public function getCreatorId()
    {
        return $this->creatorId;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Team
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
     * Set logoLink
     *
     * @param string $logoLink
     * @return Team
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
     * Add tournaments
     *
     * @param \AppBundle\Entity\Tournament $tournaments
     * @return Team
     */
    public function addTournament(\AppBundle\Entity\Tournament $tournaments)
    {
        $this->tournaments[] = $tournaments;

        return $this;
    }

    /**
     * Remove tournaments
     *
     * @param \AppBundle\Entity\Tournament $tournaments
     */
    public function removeTournament(\AppBundle\Entity\Tournament $tournaments)
    {
        $this->tournaments->removeElement($tournaments);
    }

    /**
     * Get tournaments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTournaments()
    {
        return $this->tournaments;
    }

    /**
     * Set wallet
     *
     * @param \AppBundle\Entity\Wallet $wallet
     * @return Team
     */
    public function setWallet(\AppBundle\Entity\Wallet $wallet = null)
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * Get wallet
     *
     * @return \AppBundle\Entity\Wallet 
     */
    public function getWallet()
    {
        return $this->wallet;
    }

    /**
     * Add users
     *
     * @param \AppBundle\Entity\User $users
     * @return Team
     */
    public function addUser(\AppBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\User $users
     */
    public function removeUser(\AppBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add matches
     *
     * @param \AppBundle\Entity\Matches $matches
     * @return Team
     */
    public function addMatch(\AppBundle\Entity\Matches $matches)
    {
        $this->matches[] = $matches;

        return $this;
    }

    /**
     * Remove matches
     *
     * @param \AppBundle\Entity\Matches $matches
     */
    public function removeMatch(\AppBundle\Entity\Matches $matches)
    {
        $this->matches->removeElement($matches);
    }

    /**
     * Get matches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Add invitedUsers
     *
     * @param \AppBundle\Entity\User $invitedUsers
     * @return Team
     */
    public function addInvitedUser(\AppBundle\Entity\User $invitedUsers)
    {
        $this->invitedUsers[] = $invitedUsers;

        return $this;
    }

    /**
     * Remove invitedUsers
     *
     * @param \AppBundle\Entity\User $invitedUsers
     */
    public function removeInvitedUser(\AppBundle\Entity\User $invitedUsers)
    {
        $this->invitedUsers->removeElement($invitedUsers);
    }

    /**
     * Get invitedUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvitedUsers()
    {
        return $this->invitedUsers;
    }
}
