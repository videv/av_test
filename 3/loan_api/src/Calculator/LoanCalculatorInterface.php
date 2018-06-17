<?php declare(strict_types=1);

namespace App\Calculator;

use App\Entity\LoanCalculateRequest;

interface LoanCalculatorInterface
{
    function calculatePayments(LoanCalculateRequest $loanCalculateRequest);
    function calculateTotalWithPercents(LoanCalculateRequest $loanCalculateRequest);
}
