<?

namespace App\DTO;

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
     * When the order is placed
     *
     * @var int
     */
    public readonly int $createdAt;

    /**
     * When the order is updated
     *
     * @var int
     */
    public readonly int $updatedAt;


    /**
     * Setter for order id
     * 
     * @param int|string $id
     * @return $this
     */
    public function setOrderId(int|string $id)
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
     * Setter for order total sales
     * 
     * @param int|string $totalSales
     * @return $this
     */
    public function setTotalSales(int|string $totalSales)
    {
        $this->totalSales = (int) $totalSales;

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
        $this->currencyId = (int) $currencyId;

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
        $this->orderStatusId = (int) $orderStatusId;

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

    /**
     * Setter for updated at in Unix seconds
     * 
     * @param int|DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(int|DateTime $updatedAt)
    {
        if ($updatedAt instanceof DateTime) {

            $updatedAt = $updatedAt->getTimestamp();
        }

        $this->updatedAt = $updatedAt;

        return $this;
    }
}
