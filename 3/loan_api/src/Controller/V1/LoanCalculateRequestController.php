<?php declare(strict_types=1);

namespace App\Controller\V1;

use App\Calculator\AnnuityLoanCalculator;
use App\DTO\LoanCalculateRequestDTO;
use App\Entity\LoanCalculateResultRow;
use App\Exception\LoanCalculateRequestValidationException;
use App\Manager\LoanCalculateResultManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Manager\LoanCalculateRequestManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class LoanCalculateRequestController extends FOSRestController
{
    /** @var LoanCalculateRequestManager */
    protected $loanCalculateRequestManager;

    /** @var LoanCalculateResultManager */
    protected $loanCalculateResultManager;

    /** @var ValidatorInterface */
    protected $validator;

    /*************************
     * TODO: implements LoanCalculatorCollector and choose calculator by type from the request
     * @var AnnuityLoanCalculator
     */
    protected $annuityLoanCalculator;

    public function __construct(
        LoanCalculateRequestManager $loanCalculateRequestManager,
        LoanCalculateResultManager $loanCalculateResultManager,
        AnnuityLoanCalculator $annuityLoanCalculator,
        ValidatorInterface $validator)
    {
        $this->loanCalculateRequestManager = $loanCalculateRequestManager;
        $this->loanCalculateResultManager = $loanCalculateResultManager;
        $this->annuityLoanCalculator = $annuityLoanCalculator;
        $this->validator = $validator;
    }

    /**
     * @Rest\Post("/")
     * @Rest\View(statusCode=201)
     * ParamConverter("dto", class="App\DTO\LoanCalculateRequestDTO", converter="fos_rest.request_body")
     */
    public function postLoanCalculateRequestAction(LoanCalculateRequestDTO $dto
        , ConstraintViolationListInterface $validationErrors
    ): Response
    {

        if (!empty($validationErrors)) {
            throw (new LoanCalculateRequestValidationException())->setConstraints($validationErrors);
        }

        $loanCalculateRequest = $this->loanCalculateRequestManager->createFromDTO($dto);
        $this->loanCalculateRequestManager
            ->save($loanCalculateRequest)
            //TODO: move processing to RabbitMQ by "Created Request Event"
            ->process($loanCalculateRequest, $this->annuityLoanCalculator);
        $this->loanCalculateResultManager->save($loanCalculateRequest->getLoanCalculateResult());

        return new Response($loanCalculateRequest);
    }
}
