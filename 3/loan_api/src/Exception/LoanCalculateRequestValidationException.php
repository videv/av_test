<?php declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\Exception\ValidatorException;
use Throwable;

class LoanCalculateRequestValidationException extends ValidatorException
{
    const EXCEPTION_MESSAGE = "Calculate request validation error: ";

    /** @var array */
    protected $constraints = [];

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = !empty($message) ? $message : self::EXCEPTION_MESSAGE;
        parent::__construct($message, $code, $previous);
    }

    public function setConstraints(\Traversable $constraints): self
    {
        $this->constraints = $constraints;
        $this->message .= $this->implodeConstraints();
        return $this;
    }

    public function implodeConstraints(): string
    {
        $constraintsStr = '';
        /** @var \Symfony\Component\Validator\ConstraintViolation $constraint */
        foreach($this->constraints as $constraint) {
            $constraintsStr .= sprintf("LoanCalculateRequest[%s] - %s ", $constraint->getPropertyPath(), $constraint->getMessage());
        }
        return $constraintsStr;
    }
}
