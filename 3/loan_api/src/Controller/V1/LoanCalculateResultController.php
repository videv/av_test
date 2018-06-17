<?php declare(strict_types=1);

namespace App\Controller\V1;

use App\Entity\LoanCalculateResult;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class LoanCalculateResultController extends FOSRestController
{
    /**
     * @Rest\Get("/", options={"expose": true})
     */
    public function getLoanCalculateResultAction(LoanCalculateResult $loanCalculateResult): Response
    {
        return new Response($loanCalculateResult);
    }
}
