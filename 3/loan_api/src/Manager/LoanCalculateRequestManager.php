<?php declare(strict_types=1);

namespace App\Manager;

use App\Calculator\LoanCalculatorInterface;
use App\DTO\LoanCalculateRequestDTO;
use App\Entity\LoanCalculateRequest;
use Doctrine\ORM\EntityManagerInterface;

class LoanCalculateRequestManager
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var LoanCalculateResultManager*/
    protected $loanCalculateResultManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoanCalculateResultManager $loanCalculateResultManager
    ) {
        $this->entityManager = $entityManager;
        $this->loanCalculateResultManager = $loanCalculateResultManager;
    }

    public function createFromDTO(LoanCalculateRequestDTO $dto): LoanCalculateRequest
    {
        $loanCalculateRequest = LoanCalculateRequest::createFromDTO($dto);
        $loanCalculateResult = $this->loanCalculateResultManager->createLoanCalculateResult();
        $loanCalculateRequest->setLoanCalculateResult($loanCalculateResult);
        $this->entityManager->persist($loanCalculateRequest);
        return $loanCalculateRequest;
    }

    public function save(LoanCalculateRequest $loanCalculateRequest): self
    {
        $this->entityManager->flush($loanCalculateRequest);
        return $this;
    }

    public function process(
        LoanCalculateRequest $loanCalculateRequest,
        LoanCalculatorInterface $loanCalculator
    ): self {
        try {
            $loanCalculator->calculatePayments($loanCalculateRequest);
            return $this;
        } catch (LoanCalculateDataException $e) {
            throw (new LoanCalculateRequestProcessException());
        }
    }    
}
