<?php

namespace App\Dto;

use DateTime;

/**
 * Create Order Data Transfer Object (DTO)
 * 
 */
class OrderDTO
{
    /**
     * The unique id of the order
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
     * Total sale of the order without decimals
     *
     * @var int
     */
    public readonly int $totalSales;

    /**
     * The currency use for the order
     *
     * @var int
     */
    public readonly int $currencyId;

    /**
     * Status of the order
     *
     * @var int
     */
    public readonly int $orderStatusId;

    /**
     * Amount of reward point claimed
     *
     * @var int
     */
    public readonly int $pointClaimed;

    /**
     * When the order is placed
     *
     * @var int|null
     */
    public readonly int|null $createdAt;

    /**
     * When the order is updated
     *
     * @var int|null
     */
    public readonly int|null $updatedAt;

    /**
     * Setter for order id
     * 
     * @param int|string $id
     * @return $this
     */
    public function setId(int|string $id)
    {
        $this->id = intval($id);

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
        $this->userId = intval($userId);

        return $this;
    }

    /**
     * Setter for order total sales
     * 
     * @param int|string $totalSales
     * @return $this
     */
    public function setTotalSales(int|string $totalSales)
    {
        $this->totalSales = intval($totalSales);

        return $this;
    }

    /**
     * Setter for currency ID
     * 
     * @param int $currencyId
     * @return $this
     */
    public function setCurrencyId(int|string $currencyId)
    {
        $this->currencyId = intval($currencyId);

        return $this;
    }

    /**
     * Setter for order status ID
     * 
     * @param int $orderStatusId
     * @return $this
     */
    public function setOrderStatusId(int|string $orderStatusId)
    {
        $this->orderStatusId = intval($orderStatusId);

        return $this;
    }

    /**
     * Setter for order Claimed Point
     * 
     * @param int|string $pointClaimed
     * @return $this
     */
    public function setPointClaimed(int|string $pointClaimed)
    {
        $this->pointClaimed = intval($pointClaimed);

        return $this;
    }

    /**
     * Setter for created at in Unix seconds
     * 
     * @param int|DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(int|string|null|DateTime $createdAt)
    {
        if ($createdAt instanceof DateTime) {
            $createdAt = $createdAt->getTimestamp();
        }

        if (gettype($createdAt) == 'string') {
            $createdAt = strtotime($createdAt);
        }

        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Setter for updated at in Unix seconds
     * 
     * @param int|DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(int|string|null|DateTime $updatedAt)
    {
        if ($updatedAt instanceof DateTime) {
            $updatedAt = $updatedAt->getTimestamp();
        }

        if (gettype($updatedAt) == 'string') {
            $updatedAt = strtotime($updatedAt);
        }

        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get effective price for order payment
     * 
     */
    public function getNetPrice()
    {
        return $this->totalSales - ($this->pointClaimed ?: 0);
    }

    /**
     * Setter to update all related information from table
     * 
     */
    public function updateFromQuery(array $queryData)
    {
        $this->setId($queryData["id"]);
        $this->setUserId($queryData["user_id"]);
        $this->setTotalSales($queryData["total_sales"]);
        $this->setCurrencyId($queryData["currency_id"]);
        $this->setOrderStatusId($queryData["order_status_id"]);
        $this->setCreatedAt($queryData["created_at"]);
        $this->setUpdatedAt($queryData["updated_at"]);
    }
}
