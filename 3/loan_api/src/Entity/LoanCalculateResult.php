<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="loan_calculate_result")
 * @ORM\Entity()
 */
class LoanCalculateResult
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="uuid", type="guid", unique=true, nullable=false)
     * @Serializer\Expose()
     * @Serializer\Type("string")
     * @Serializer\Groups({"loanResult","loanRequest"})
     */
    protected $uuid;

    /**
     * @var float
     * @ORM\Column(name="total_owed", type="decimal", precision=10, scale=2, nullable=false)
     * @Assert\NotNull()
     * @Serializer\Expose()
     * @Serializer\Type("float")
     * @Serializer\Groups({"loanResult"})
     */
    protected $totalOwed;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\LoanCalculateResultRow", mappedBy="loanCalculateResult", cascade={"persist", "remove", "refresh"})
     * @Serializer\Expose()
     * @Serializer\Type("ArrayCollection<App\Entity\LoanCalculateResultRow>")
     * @Serializer\Groups({"loanResult"})
     */
    protected $resultRows;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
        $this->resultRows = new ArrayCollection();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getResultRows(): ArrayCollection
    {
        return $this->resultRows;
    }

    public function addRow(LoanCalculateResultRow $row): self
    {
        $this->resultRows[$row->getPaymentOrder()] = $row;
        return $this;
    }

    public function addRows(array $rows): self
    {
        foreach ($rows as $row) {
            if (!$row instanceof LoanCalculateResultRow) {
                throw (new \Exception('Unsupported loan calcualte result row type given'));
            }
            $this->addRow($row);
        }
        return $this;
    }

    public function setTotal(float $total): self
    {
        $this->totalOwed = $total;
        return $this;
    }

    public function getTotal(): float
    {
        return $this->totalOwed;
    }
}
