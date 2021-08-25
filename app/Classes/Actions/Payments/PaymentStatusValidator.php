<?php


namespace App\Actions\Payments;

use App\Validation\Validation;
use InvalidArgumentException;

class PaymentStatusValidator extends Validation
{
    /**
     * The payment status validation rules.
     *
     * @var array
     */
    private array $allowedStatusValues = [
        'pending',
        'paid',
        'failed'
    ];

    /**
     * Checks to make sure that a supplied status string meets the validation criteria.
     *
     * @param array $args - The status to be validated.
     *
     * @throws InvalidArgumentException if the provided status is not valid.
     *
     * @return boolean
     */
    public function validate(array $args):  bool
    {
        // Ensure that the array has a 'status' key.
        if (!array_key_exists('status', $args))
        {
            throw new InvalidArgumentException('Error - status is missing');
        }
        // Ensure that the supplied status is a string.
        else if (!is_string($args['status']))
        {
            throw new InvalidArgumentException('Error - invalid data type supplied for status, string expected.');
        }
        // Ensure that the supplied status is on the list of allowed payment status values.
        else if (!in_array($args['status'], $this->allowedStatusValues))
        {
            throw new InvalidArgumentException('Error - Payment status "' . $args['status'] . '" is invalid.');
        }

        return true;
    }
}