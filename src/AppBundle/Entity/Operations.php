<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Operations
 *
 * @ORM\Table(name="operations")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OperationsRepository")
 */
class Operations
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
     * @ORM\Column(name="amount", type="integer", nullable=true)
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="senderWalletId", type="integer")
     */
    private $senderWalletId;

    /**
     * @var int
     *
     * @ORM\Column(name="receiverWalletId", type="integer")
     */
    private $receiverWalletId;


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
     * Set amount
     *
     * @param integer $amount
     * @return Operations
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set senderWalletId
     *
     * @param integer $senderWalletId
     * @return Operations
     */
    public function setSenderWalletId($senderWalletId)
    {
        $this->senderWalletId = $senderWalletId;

        return $this;
    }

    /**
     * Get senderWalletId
     *
     * @return integer 
     */
    public function getSenderWalletId()
    {
        return $this->senderWalletId;
    }

    /**
     * Set receiverWalletId
     *
     * @param integer $receiverWalletId
     * @return Operations
     */
    public function setReceiverWalletId($receiverWalletId)
    {
        $this->receiverWalletId = $receiverWalletId;

        return $this;
    }

    /**
     * Get receiverWalletId
     *
     * @return integer 
     */
    public function getReceiverWalletId()
    {
        return $this->receiverWalletId;
    }
}
