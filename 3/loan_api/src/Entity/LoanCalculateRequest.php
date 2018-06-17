<?php declare(strict_types=1);

namespace App\Entity;

use App\DTO\LoanCalculateRequestDTO;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="loan_calculate_request")
 * @ORM\Entity()
 */
class LoanCalculateRequest
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="uuid", type="guid", unique=true, nullable=false)
     * @Serializer\Expose()
     * @Serializer\Type("string")
     * @Serializer\Groups({"loanRequest"})
     */
    public $uuid;

    /**
     * @var float
     * @ORM\Column(name="loan_amount", type="decimal", precision=10, scale=2, nullable=false)
     * @Assert\NotNull()
     * @Serializer\Expose()
     * @Serializer\Type("float")
     * @Serializer\Groups({"loanRequest"})
     */
    public $loanAmount;

    /**
     * @var int
     * @ORM\Column(name="loan_term", type="int")
     * @Assert\NotNull()
     * @Serializer\Expose()
     * @Serializer\Type("int")
     * @Serializer\Groups({"loanRequest"})
     */
    public $loanTerm;

    /**
     * @var float
     * @ORM\Column(name="loan_interest", type="decimal", precision=10, scale=2, nullable=false)
     * @Assert\NotNull()
     * @Serializer\Expose()
     * @Serializer\Type("float")
     * @Serializer\Groups({"loanRequest"})
     */
    public $loanInterest;

    /**
     * @var LoanCalculateResult
     * @ORM\OneToMany(targetEntity="App\Entity\LoanCalculateResult", cascade={"persist", "remove", "refresh"})
     * @ORM\JoinColumn(name="loan_calculate_result_id", onDelete="CASCADE", nullable=false)
     * @Serializer\Expose()
     * @Serializer\Type("App\Entity\LoanCalculateResult")
     * @Serializer\Groups({"loanRequest"})
     */
    public $loanCalculateResult;

    /**
     * @var \DateTime
     * @ORM\Column(name="payment_date", type="date", nullable=false)
     * @Assert\NotNull()
     * @Assert\Date()
     * @Serializer\Expose()
     * @Serializer\Type("DateTime<'Y-m-d'>")
     * @Serializer\Groups({"loanRequest"})
     */
    public $loanFirstPaymentDate;

    private function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    public static function createFromDTO(LoanCalculateRequestDTO $dto)
    {
        $obj = new self;
        $obj->uuid = $dto->getUuid();
        $obj->loanAmount = $dto->getLoanAmount();
        $obj->loanTerm = $dto->getLoanTerm();
        $obj->loanInterest = $dto->getLoanInterest();
        $obj->loanFirstPaymentDate = $dto->getLoanFirstPaymentDate();
        return $obj;
    }

    public function getUuuid(): string
    {
        return $this->uuid;
    }

    public function getLoanAmount(): float
    {
        return $this->loanAmount;
    }

    public function getLoanTerm(): int
    {
        return $this->loanTerm;
    }

    public function getLoanInterest(): float
    {
        return $this->loanInterest;
    }

    public function getLoanFirstPaymentDate(): \DateTime
    {
        return $this->loanFirstPaymentDate;
    }

    public function setLoanCalculateResult(LoanCalculateResult $loanCalculateResult): self
    {
        $this->loanCalculateResult = $loanCalculateResult;
        return $this;
    }

    public function getLoanCalculateResult(): LoanCalculateResult
    {
        return $this->loanCalculateResult;
    }
}
