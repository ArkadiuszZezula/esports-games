<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Wallet;
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
    */

    protected $id;

    /**
     * @ORM\Column(name="steam_id", type="string")
     */
    private $steamId;
    /**
     * @ORM\Column(name="activity", type="string")
     */
    private $activity;
    /**
     * One User has One Wallet.
     * @ORM\OneToOne(targetEntity="Wallet", cascade={"persist"})
     * @ORM\JoinColumn(name="wallet_id", referencedColumnName="id")
     */
    private $wallet;
    /**
    * Many Users have Many Teams.
    * @ORM\ManyToMany(targetEntity="Team", inversedBy="users")
    * @ORM\JoinTable(name="users_teams")
    */

    private $teams;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->wallet = new Wallet();
        $this->teams = new ArrayCollection();
        $this->activity = 1;
    }



    /**
     * Set steamId
     *
     * @param string $steamId
     * @return User
     */
    public function setSteamId($steamId)
    {
        $this->steamId = $steamId;

        return $this;
    }

    /**
     * Get steamId
     *
     * @return string 
     */
    public function getSteamId()
    {
        return $this->steamId;
    }

    /**
     * Set activity
     *
     * @param string $activity
     * @return User
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return string 
     */
    public function getActivity()
    {
        return $this->activity;
    }


    /**
     * Set wallet
     *
     * @param \AppBundle\Entity\Wallet $wallet
     * @return User
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
     * Add teams
     *
     * @param \AppBundle\Entity\Team $teams
     * @return User
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
