<?php

use App\DateTime\DateDurationCalculator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DateDurationCalculatorTest extends TestCase
{
    public function testCanCalculateDateDuration():   void
    {
        /*
         * Create the mocks that will be used in the test.
         */
        $dateInterval = $this->createMock(DateInterval::class);

        /*
         * Set up the mock methods that will be used in the test, along with expectations and return values.
         */

        // $dateInterval->format() expects to be called once, take an argument string for for the format of the return value and a valid return value.
        $this->setUpDateIntervalMockFormatMethod($dateInterval, 1, '%r%d', '1');

        /*
	    *  Create a new instance of DateDurationCalculator and assert that the calculateDays() response is as
         * expected.
	    */
        $dateDurationCalc = new DateDurationCalculator();
        $result = $dateDurationCalc->calculateDays($dateInterval);
        $this->assertEquals(1, $result);
    }

    /**
     * Sets up the format() method for a mock instance of DateTime, according to the arguments supplied.
     *
     * @param MockObject $dateInterval - The mock DateInterval instance that the method will be set up for.
     * @param int $numCalls - The number of calls that are expected to be made to the mock method.
     * @param string $formatArgument - The argument that the mock method expects to receive.
     * @param string $returnValue - The response that is expected when the DateInterval format() method runs successfully.
     *
     * @return void
     */
    private function setUpDateIntervalMockFormatMethod(MockObject $dateInterval, int $numCalls, string $formatArgument, string $returnValue)
    {
        $dateInterval->expects($this->exactly($numCalls))
            ->method('format')
            ->with($formatArgument)
            ->willReturn($returnValue);
    }
}