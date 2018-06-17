<?php declare(strict_types=1);

namespace App\Calculator;

use App\Entity\LoanCalculateRequest;
use App\Entity\LoanCalculateResultRow;
use Doctrine\ORM\EntityManagerInterface;

class AnnuityLoanCalculator implements LoanCalculatorInterface
{

    /**
     * @var EntityManagerInterface
     *
     * For sure it was better to create smth like LoanCalculatorResultRow manager
     * and place the EntityManager there but it's test task, like as MVP and
     * I didn't want to spent a lot of time for such things but I've described
     * it there instead.
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculatePayments(LoanCalculateRequest $loanCalculateRequest): self
    {
        $totalWP = $this->calculateTotalWithPercents($loanCalculateRequest);
        $loanCalculateRequest->getLoanCalculateResult()->setTotal($totalWP);

        $monthlyRate = $this->calculateMonthlyRate($loanCalculateRequest)/100;
        $monthlyPayment = $this->calculateMonthlyPayment($loanCalculateRequest);
        $amount = $loanCalculateRequest->getLoanAmount();
        for ($i=1; $i<=$loanCalculateRequest->getLoanTerm(); $i++) {
            $paymentDate = (clone $loanCalculateRequest->loanFirstPaymentDate)->modify("+{$i} month");
            $monthlyPercentage = $this->calculateMonthlyPercents($amount, $monthlyRate);
            $monthlyMainPart = $monthlyPayment - $monthlyPercentage;
            $paymentOwed = $totalWP - $monthlyPayment;

            $loanCalculateRequestRow = new LoanCalculateResultRow(
                $i,
                $paymentDate,
                $monthlyMainPart,
                $monthlyPercentage,
                $paymentOwed
            );

            $loanCalculateRequest->getLoanCalculateResult()->addRow($loanCalculateRequestRow);
            $amount -= $monthlyMainPart;
            $totalWP -= $monthlyPayment;
        }
        return $this;
    }

    public function calculateTotalWithPercents(LoanCalculateRequest $loanCalculateRequest): float
    {
        $monthlyRate = $this->calculateMonthlyRate($loanCalculateRequest)/100;
        $term = $loanCalculateRequest->getLoanTerm();
        $amount = $loanCalculateRequest->getLoanAmount();

        return round($amount * (1+($monthlyRate*$term)), 2);
    }

    private function calculateMonthlyRate(LoanCalculateRequest $loanCalculateRequest): float
    {
        return round($loanCalculateRequest->getLoanInterest()/12, 2);
    }

    private function calculateMonthlyPayment(LoanCalculateRequest $loanCalculateRequest): float
    {
        $requestedAmount = $loanCalculateRequest->getLoanAmount();
        $monthlyRate = $this->calculateMonthlyRate($loanCalculateRequest)/100;
        $term = $loanCalculateRequest->getLoanTerm();

        return round($requestedAmount * ( $monthlyRate + ( $monthlyRate / ( (1+$monthlyRate)**$term - 1))), 2);
    }

    private function calculateMonthlyPercents($owed, $monthlyRate): float
    {
        return $owed * $monthlyRate;
    }
}
