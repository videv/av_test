<?php declare(strict_types=1);

namespace App\Entity;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="loan_calculate_result_row,
 *  uniqueConstraints={
 *        @UniqueConstraint(name="loan_calculate_result_row_order_uuid",
 *            columns={"payment_order", "loan_calculate_result_uuid"})
 *    }")
 * @ORM\Entity()
 */
class LoanCalculateResultRow
{
    /**
     * @var int
     * @ORM\Column(name="payment_order", type="integer", nullable=false)
     * @Assert\NotNull()
     * @Serializer\Expose()
     * @Serializer\Type("int")
     * @Serializer\Groups({"loanResult"})
     */
    protected $paymentOrder;

    /**
     * @var \DateTime
     * @ORM\Column(name="payment_date", type="date", nullable=false)
     * @Assert\NotNull()
     * @Assert\Date()
     * @Serializer\Expose()
     * @Serializer\Type("DateTime<'Y-m-d'>")
     * @Serializer\Groups({"loanResult"})
     */
    protected $paymentDate;

    /**
     * @var float
     * @ORM\Column(name="payment_main_part", type="decimal", precision=10, scale=2, nullable=false)
     * @Assert\NotNull()
     * @Serializer\Expose()
     * @Serializer\Type("float")
     * @Serializer\Groups({"loanResult"})
     */
    protected $paymentMainPart;
    /**
     * @var float
     * @ORM\Column(name="payment_percentage", type="decimal", precision=10, scale=2, nullable=false)
     * @Assert\NotNull()
     * @Serializer\Expose()
     * @Serializer\Type("float")
     * @Serializer\Groups({"loanResult"})
     */
    protected $paymentPercentage;

    /**
     * @var float
     * @ORM\Column(name="payment_total", type="decimal", precision=10, scale=2, nullable=false)
     * @Assert\NotNull()
     * @Serializer\Expose()
     * @Serializer\Type("float")
     * @Serializer\Groups({"loanResult"})
     */
    protected $paymentTotal;

    /**
     * @var float
     * @ORM\Column(name="payment_owed", type="decimal", precision=10, scale=2, nullable=false)
     * @Assert\NotNull()
     * @Serializer\Expose()
     * @Serializer\Type("float")
     * @Serializer\Groups({"loanResult"})
     */
    protected $owed;

    /**
     * @var LoanCalculateResult
     *
     * @ORM\ManyToOne(targetEntity="LoanCalculateResult")
     * @ORM\JoinColumn(name="loan_calculate_result_uuid", referencedColumnName="uuid", onDelete="CASCADE")
     * @Serializer\Exclude()
     */
    protected $loanCalculateResult;


    public function __construct(
        int $paymentOrder,
        \DateTime $paymentDate,
        float $paymentMainPart,
        float $paymentPercentage,
        float $owed
    ) {
        $this->paymentOrder = $paymentOrder;
        $this->paymentDate = $paymentDate;
        $this->paymentPercentage = $paymentPercentage;
        $this->paymentMainPart = $paymentMainPart;
        $this->paymentTotal = $this->paymentPercentage + $paymentMainPart;
        $this->owed = $owed;
    }

    public function getPaymentOrder(): int
    {
        return $this->paymentOrder;
    }

    public function getPaymentDate(): \DateTime
    {
        return $this->paymentDate;
    }

    public function getPaymentPercentage(): float
    {
        return $this->paymentPercentage;
    }

    public function getPayementMainPart(): float
    {
        return $this->paymentMainPart;
    }

    public function getPaymentTotal(): float
    {
        return $this->paymentTotal;
    }

    public function getOwed(): float
    {
        return $this->owed;
    }
}
