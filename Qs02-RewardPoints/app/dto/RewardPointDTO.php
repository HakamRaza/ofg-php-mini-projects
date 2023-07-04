<?php

namespace App\Dto;

use DateTime;

/**
 * Create Reward points Data Transfer Object (DTO)
 * 
 */
class RewardPointDTO
{
    /**
     * The unique id of the point reward
     *
     * @var int
     */
    public readonly int $id;

    /**
     * The unique id of the user
     *
     * @var int
     */
    public readonly int $userId;

    /**
     * When the order is placed
     *
     * @var int
     */
    public readonly int $createdAt;


    /**
     * Setter for order id
     * 
     * @param int|string $orderId
     * @return $this
     */
    public function setRewardId(int|string $id)
    {
        $this->$id = (int) $id;

        return $this;
    }

    /**
     * Setter for user id
     * 
     * @param int|string $userId
     * @return $this
     */
    public function setUserId(int|string $userId)
    {
        $this->userId = (int) $userId;

        return $this;
    }

    /**
     * Setter for created at in Unix seconds
     * 
     * @param int|DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(int|DateTime $createdAt)
    {
        if ($createdAt instanceof DateTime) {

            $createdAt = $createdAt->getTimestamp();
        }

        $this->createdAt = $createdAt;

        return $this;
    }
}
