<?php

use PHPUnit\Framework\TestCase;
use App\Actions\Payments\PaymentDueDatesValidator;
use App\DateTime\DateDurationCalculator;
use PHPUnit\Framework\MockObject\MockObject;

class PaymentDueDatesValidatorTest extends TestCase
{
    /**
    * Checks to see that payment due date start and end dates are successfully
    * validated when valid arguments are supplied.
    */
    public function testPaymentDueDatesValidated():	void
    {
        /*
         * Create the mocks that will be used in the test.
         */
        $startDate = $this->createMock(DateTime::class);
        $endDate = $this->createMock(DateTime::class);
        $dateDurationCalculator = $this->createMock(DateDurationCalculator::class);
        $dateInterval = $this->createMock(DateInterval::class);

        /*
         * Set up the mock methods that will be used in the test, along with expectations and return values.
         */

        // $startDate->diff() expects to be called once, take an argument of the end date DateTime mock and return the DateInterval mock.
        $this->setUpMockDateTimeDiffMethod($startDate, 1, $endDate, $dateInterval);

        // $dateDurationCalculator->calculateDays() expects to be called once, take an array argument of the DateInterval mock and return a valid response.
        $this->setUpMockDateDurationCalculatorCalculateDaysMethod($dateDurationCalculator, 1, $dateInterval, 1);

        /*
	    *  Create a new instance of PaymentDueDatesValidator and assert that the validate() response is as
         * expected.
	    */
        $paymentDueDatesValidator = new PaymentDueDatesValidator($dateDurationCalculator);
        $result = $paymentDueDatesValidator->validate(['startDate' => $startDate, 'endDate' => $endDate]);
        $this->assertEquals(true, $result);
    }

    /**
    * Checks to see that an exception is thrown when an array that lacks both ‘startDate’
    * and ‘endDate’ keys is supplied.
    */
    public function testExceptionThrownWhenBothArrayKeysInvalid():		void
    {
        /*
         * Create the mocks that will be used in the test.
         */
        $startDate = $this->createMock(DateTime::class);
        $endDate = $this->createMock(DateTime::class);
        $dateDurationCalculator = $this->createMock(DateDurationCalculator::class);

        /*
	    *  Create a new instance of PaymentDueDatesValidator and assert that the validate() method throws
         * an InvalidArgumentException with the expected exception message.
	    */
        $paymentDueDatesValidator = new PaymentDueDatesValidator($dateDurationCalculator);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error - start date/end date is missing.');
        $paymentDueDatesValidator->validate(['incorrectKey' => $startDate, 'anotherIncorrectKey' => $endDate]);
    }

    /**
    * Checks to see that an exception is thrown when an array that lacks a ‘startDate’
    *  key is supplied.
    */
    public function testExceptionThrownWhenStartDateArrayKeyInvalid():	void
    {
        /*
         * Create the mocks that will be used in the test.
         */
        $startDate = $this->createMock(DateTime::class);
        $endDate = $this->createMock(DateTime::class);
        $dateDurationCalculator = $this->createMock(DateDurationCalculator::class);

        /*
	    *  Create a new instance of PaymentDueDatesValidator and assert that the validate() method throws
         * an InvalidArgumentException with the expected exception message.
	    */
        $paymentDueDatesValidator = new PaymentDueDatesValidator($dateDurationCalculator);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error - start date/end date is missing.');
        $paymentDueDatesValidator->validate(['incorrectKey' => $startDate, 'endDate' => $endDate]);
    }

    /**
    * Checks to see that an exception is thrown when an array that lacks an ‘endDate’
    *  key is supplied.
    */
    public function testExceptionThrownWhenEndDateArrayKeyInvalid():	void
    {
        /*
         * Create the mocks that will be used in the test.
         */
        $startDate = $this->createMock(DateTime::class);
        $endDate = $this->createMock(DateTime::class);
        $dateDurationCalculator = $this->createMock(DateDurationCalculator::class);

        /*
	    *  Create a new instance of PaymentDueDatesValidator and assert that the validate() method throws
         * an InvalidArgumentException with the expected exception message.
	    */
        $paymentDueDatesValidator = new PaymentDueDatesValidator($dateDurationCalculator);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error - start date/end date is missing.');
        $paymentDueDatesValidator->validate(['startDate' => $startDate, 'incorrectKey' => $endDate]);
    }

    /**
    * Checks to see that an exception is thrown when both the supplied start date
    * and end date are not instances of the DateTime class.
    */
    public function testExceptionThrownWhenBothDatesAreNotValidType():	void
    {
        /*
         * Create the startDate and endDate strings that will be used in the test.
         */
        $startDate = '2021-08-01';
        $endDate = '2021-08-23';

        /*
         * Create the mocks that will be used in the test.
         */
        $dateDurationCalculator = $this->createMock(DateDurationCalculator::class);

        /*
	    *  Create a new instance of PaymentDueDatesValidator and assert that the validate() method throws
         * an InvalidArgumentException with the expected exception message.
	    */
        $paymentDueDatesValidator = new PaymentDueDatesValidator($dateDurationCalculator);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error - start and end dates need to be instances of the DateTime class.');
        $paymentDueDatesValidator->validate(['startDate' => $startDate, 'endDate' => $endDate]);
    }

    /**
    * Checks to see that an exception is thrown when just the supplied start date
    * is not an instance of the DateTime class.
    */
    public function testExceptionThrownWhenStartDateIsNotValidType():	void
    {
        /*
         * Create the startDate string that will be used in the test.
         */
        $startDate = '2021-08-01';

        /*
         * Create the mocks that will be used in the test.
         */
        $endDate = $this->createMock(DateTime::class);
        $dateDurationCalculator = $this->createMock(DateDurationCalculator::class);

        /*
	    *  Create a new instance of PaymentDueDatesValidator and assert that the validate() method throws
         * an InvalidArgumentException with the expected exception message.
	    */
        $paymentDueDatesValidator = new PaymentDueDatesValidator($dateDurationCalculator);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error - start and end dates need to be instances of the DateTime class.');
        $paymentDueDatesValidator->validate(['startDate' => $startDate, 'endDate' => $endDate]);
    }

    /**
    * Checks to see that an exception is thrown when just the supplied end date
    * is not an instance of the DateTime class.
    */
    public function testExceptionThrownWhenEndDateIsNotValidType():	void
    {
        /*
         * Create the endDate string that will be used in the test.
         */
        $endDate = '2021-08-23';

        /*
         * Create the mocks that will be used in the test.
         */
        $startDate = $this->createMock(DateTime::class);
        $dateDurationCalculator = $this->createMock(DateDurationCalculator::class);

        /*
	    *  Create a new instance of PaymentDueDatesValidator and assert that the validate() method throws
         * an InvalidArgumentException with the expected exception message.
	    */
        $paymentDueDatesValidator = new PaymentDueDatesValidator($dateDurationCalculator);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error - start and end dates need to be instances of the DateTime class.');
        $paymentDueDatesValidator->validate(['startDate' => $startDate, 'endDate' => $endDate]);
    }

    /**
    * Checks to see that an exception is thrown when the supplied start date
    * is the same date as the supplied end date.
    */
    public function testExceptionThrownWhenStartDateOnEndDate():		void
    {
        /*
         * Create the mocks that will be used in the test.
         */
        $startDate = $this->createMock(DateTime::class);
        $endDate = $this->createMock(DateTime::class);
        $dateDurationCalculator = $this->createMock(DateDurationCalculator::class);
        $dateInterval = $this->createMock(DateInterval::class);

        // $startDate->diff() expects to be called once, take an argument of the end date DateTime mock and return the DateInterval mock.
        $this->setUpMockDateTimeDiffMethod($startDate, 1, $endDate, $dateInterval);

        // $dateDurationCalculator->calculateDays() expects to be called once, take an array argument of the DateInterval mock and return an invalid response.
        $this->setUpMockDateDurationCalculatorCalculateDaysMethod($dateDurationCalculator, 1, $dateInterval, 0);

        /*
	    *  Create a new instance of PaymentDueDatesValidator and assert that the validate() method throws
         * an InvalidArgumentException with the expected exception message.
	    */
        $paymentDueDatesValidator = new PaymentDueDatesValidator($dateDurationCalculator);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error - supplied end date is on or before the supplied start date.');
        $paymentDueDatesValidator->validate(['startDate' => $startDate, 'endDate' => $endDate]);
    }

    /**
    * Checks to see that an exception is thrown when the supplied start date
    * is a later date than the end date.
    */
    public function testExceptionThrownWhenStartDateAfterEndDate():	void
    {
        /*
         * Create the mocks that will be used in the test.
         */
        $startDate = $this->createMock(DateTime::class);
        $endDate = $this->createMock(DateTime::class);
        $dateDurationCalculator = $this->createMock(DateDurationCalculator::class);
        $dateInterval = $this->createMock(DateInterval::class);

        // $startDate->diff() expects to be called once, take an argument of the end date DateTime mock and return the DateInterval mock.
        $this->setUpMockDateTimeDiffMethod($startDate, 1, $endDate, $dateInterval);

        // $dateDurationCalculator->calculateDays() expects to be called once, take an array argument of the DateInterval mock and return an invalid response.
        $this->setUpMockDateDurationCalculatorCalculateDaysMethod($dateDurationCalculator, 1, $dateInterval, -1);

        /*
	    *  Create a new instance of PaymentDueDatesValidator and assert that the validate() method throws
         * an InvalidArgumentException with the expected exception message.
	    */
        $paymentDueDatesValidator = new PaymentDueDatesValidator($dateDurationCalculator);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error - supplied end date is on or before the supplied start date.');
        $paymentDueDatesValidator->validate(['startDate' => $startDate, 'endDate' => $endDate]);
    }

    /**
     * Sets up the diff() method for a mock instance of DateTime, according to the arguments supplied.
     *
     * @param MockObject $date - The mock DateTime instance that the method will be set up for.
     * @param int $numCalls - The number of calls that are expected to be made to the mock method.
     * @param DateTime $otherDate - The other DateTime date that is expected to be passed into the mock method.
     * @param DateInterval $returnValue - The DateInterval response that is expected when the DateTime diff() method runs successfully.
     *
     * @return void
     */
    private function setUpMockDateTimeDiffMethod(MockObject $date, int $numCalls, DateTime $otherDate, DateInterval $returnValue)
    {
      $date->expects($this->exactly($numCalls))
        ->method('diff')
        ->with($otherDate)
        ->willReturn($returnValue);
    }

    /**
     * Sets up the calculateDays() method for a mock instance of DateDurationCalculator, according to the arguments supplied.
     *
     * @param MockObject $dateDurationCalc - The mock DateDurationCalculator instance that the method will be set up for.
     * @param int $numCalls - The number of calls that are expected to be made to the mock method.
     * @param DateInterval $calculateDaysArgument - The argument that the mock method expects to receive.
     * @param int $returnValue - The response that is expected when the DateDurationCalculator calculateDays() method runs successfully.
     *
     * @return void
     */
    private function setUpMockDateDurationCalculatorCalculateDaysMethod(MockObject $dateDurationCalc, int $numCalls, DateInterval $calculateDaysArgument, int $returnValue)
    {
        $dateDurationCalc->expects($this->exactly($numCalls))
            ->method('calculateDays')
            ->with($calculateDaysArgument)
            ->willReturn($returnValue);
    }
}