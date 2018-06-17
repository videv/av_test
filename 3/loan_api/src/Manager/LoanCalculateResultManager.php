<?php declare(strict_types=1);

namespace App\Manager;

use App\Calculator\LoanCalculatorInterface;
use App\DTO\LoanCalculateRequestDTO;
use App\Entity\LoanCalculateRequest;
use App\Entity\LoanCalculateResult;
use App\Entity\LoanCalculateResultRow;
use Doctrine\ORM\EntityManager;

class LoanCalculateResultManager
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createLoanCalculateResultRow(
        int $paymentOrder,
        \DateTime $paymentDate,
        float $paymentMainPart,
        float $paymentPercentage,
        float $owed
    ): LoanCalculateResultRow {
        $loanCalculateResultRow = new LoanCalculateResultRow(
            $paymentOrder,
            $paymentDate,
            $paymentMainPart,
            $paymentPercentage,
            $owed
        );
        $this->entityManager->persist($loanCalculateResultRow);
        return $loanCalculateResultRow;
    }

    public function createLoanCalculateResult(): LoanCalculateResult
    {
        $loanCalculateResult = new LoanCalculateResult();
        $this->entityManager->persist($loanCalculateResult);
        return $loanCalculateResult;
    }

    public function save(LoanCalculateResult $loanCalculateResult): self
    {
        $this->entityManager->transactional(
            function () use ($loanCalculateResult) {
                foreach ($loanCalculateResult->getResultRows() as $resultRow) {
                    $this->entityManager->flush($resultRow);
                }
                $this->entityManager->flush($loanCalculateResult);
            }
        );
        return $this;
    }
}
