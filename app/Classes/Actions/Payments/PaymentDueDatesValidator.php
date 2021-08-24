<?php


namespace App\Actions\Payments;

use App\Validation\Validation;
use DateTime;
use InvalidArgumentException;
use App\DateTime\DateDurationCalculator;

class PaymentDueDatesValidator extends Validation
{
    private $dateDurationCalc;

    public function __construct(DateDurationCalculator $dateDurationCalc)
    {
        $this->dateDurationCalc = $dateDurationCalc;
    }

    /**
    * Checks to make sure that the supplied start and end payment due dates meet the validation criteria.
    *
    * @param array $args - The payment due dates to be validated.
    *
    * @throws InvalidArgumentException if the supplied end date occurs on or before the supplied start date.
    *
    * @return bool
    */
    public function validate(array $args):  bool
    {
        // Ensure that the array has 'startDate' and 'endDate' keys.
        if (!((array_key_exists('startDate', $args)) && (array_key_exists('endDate', $args))))
        {
            throw new InvalidArgumentException('Error - start date/end date is missing.');
        }
        // Ensure that both arguments are instances of the DateTime class.
        else if (!(($args['startDate'] instanceof DateTime) && ($args['endDate'] instanceof DateTime)))
        {
            throw new InvalidArgumentException('Error - start and end dates need to be instances of the DateTime class.');
        }
        // Ensure that the end date is after the start date.
        else if ($this->dateDurationCalc->calculateDays($args['startDate']->diff($args['endDate'])) <= 0) {
            throw new InvalidArgumentException('Error - supplied end date is on or before the supplied start date.');
        }

        return true;
    }
}