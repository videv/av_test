<?php declare(strict_types=1);

namespace App\DTO;

use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class LoanCalculateRequestDTO
{
    /**
     * @var string
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    public $uuid;

    /**
     * @var float
     * @Serializer\Expose()
     * @Serializer\Type("float")
     * @Assert\NotBlank()
     * @Assert\Regex("/[0-9]+(\.[0-9][0-9]?)?/")
     */
    public $loanAmount;

    /**
     * @var int
     * @Serializer\Expose()
     * @Serializer\Type("int")
     * @Assert\Type("int")
     * @Assert\NotBlank()
     */
    public $loanTerm;

    /**
     * @var float
     * @Serializer\Expose()
     * @Serializer\Type("float")
     * @Assert\NotBlank()
     * @Assert\Regex("/[0-9]+(\.[0-9][0-9]?)?/")
     */
    public $loanInterest;

    /**
     * @var \DateTime
     *
     * @Serializer\Expose()
     * @Serializer\Type("DateTime<'Y-m-d'>")
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    public $loanFirstPaymentDate;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function getUuid(): string
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
}
