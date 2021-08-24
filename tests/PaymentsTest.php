<?php

use PHPUnit\Framework\TestCase;
use App\Actions\Payments\Payments;
use App\Actions\Payments\PaymentStatusValidator;
use App\Actions\Payments\PaymentDueDatesValidator;
use PHPUnit\Framework\MockObject\MockObject;

class PaymentsTest extends TestCase
{
    /**
    * Checks to see that payments are successfully retrieved when the correct parameters are
    * supplied to getPayments().
    */
    public function testPaymentsRetrieved():	void
    {
        // Create the payment status string.
        $status = 'failed';

        /*
         * Create the mocks that will be used in the test.
         */
        $startDate = $this->createMock(DateTime::class);
        $endDate = $this->createMock(DateTime::class);
        $stmt = $this->createMock(PDOStatement::class);
        $pdo = $this->createMock(PDO::class);
        $paymentStatusValidator = $this->createMock(PaymentStatusValidator::class);
        $paymentDueDatesValidator = $this->createMock(PaymentDueDatesValidator::class);

        /*
         * Set up the mock methods that will be used in the test, along with expectations and return values.
         */

        // $startDate->format() expects to be called twice (once within the test itself), take an argument of 'Y-m-d' and return a valid SQL-friendly string formatted date.
        $this->setUpDateTimeMockFormatMethod($startDate, 2, 'Y-m-d', '2021-08-01');

        // $endDate->format() also expects to be called twice (once within the test itself), take an argument of 'Y-m-d' and return a valid SQL-friendly string formatted date.
        $this->setUpDateTimeMockFormatMethod($endDate, 2, 'Y-m-d', '2021-08-24');

        // $stmt->execute() expects to be called once and to take an argument array containing the payment status string plus the payment due dates strings.
        $this->setUpPDOStatementMockExecuteMethod($stmt, 1, [$status, $startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        // $stmt->fetchAll() expects to be called once and will return the type of array that would be expected to be returned upon a successful SQL query in the Payments class getPayments() method.
        $stmtReturnArray = [
            'plan_id' => '1',
            'pc_title' => 'Mr',
            'pc_first_name' => 'Foo',
            'pc_last_name' => 'Bar',
            'user_first_name' => 'Far',
            'user_last_name' => 'Boo',
            'company_name' => 'Foo Bar Ltd'
        ];
        $this->setUpPDOStatementMockFetchAllMethod($stmt, 1, $stmtReturnArray);

        // $pdo->prepare() expects to be called once, to take an argument of the SQL query that's used in the Payments class getPayments() method and will return the PDOStatement mock.
        $this->setUpPDOMockPrepareMethod($pdo, 1, $this->getPaymentsSQLQuery(), $stmt);

        // $paymentStatusValidator->validate() expects to be called once, take an array argument of the payment status string and return a value of true.
        $this->setUpPaymentStatusValidatorMockValidateMethod($paymentStatusValidator, 1, ['status' => $status], true);

        // $paymentDueDatesValidator->validate() expects to be called once, take an array argument of the payment start and end dates for the due date range and return a value of true.
        $this->setUpPaymentDueDatesValidatorMockValidateMethod($paymentDueDatesValidator, 1, ['startDate' => $startDate, 'endDate' => $endDate], true);

        /*
	    *  Create a new instance of Payments and assert that the getPayments() response is as expected.
	    */
        $payments = new Payments($pdo, $paymentStatusValidator, $paymentDueDatesValidator);
        $this->assertEquals($stmtReturnArray, $payments->getPayments($status, $startDate, $endDate));
    }

    /**
    * Checks to see that an exception is thrown when an invalid payment status is supplied.
    */
    public function testErrorWhenStatusValidatorFails():	void
    {
        // Create the payment status and error message strings.
        $status = 'invalidStatus';
        $errorMessage = 'Error Message';

        /*
         * Create the mocks that will be used in the test.
         */
        $startDate = $this->createMock(DateTime::class);
        $endDate = $this->createMock(DateTime::class);
        $paymentStatusValidator = $this->createMock(PaymentStatusValidator::class);
        $paymentDueDatesValidator = $this->createMock(PaymentDueDatesValidator::class);
        $pdo = $this->createMock(PDO::class);

        /*
         *  Set up the mock methods that will be used in the test, along with expectations and return values.
         */

        // $paymentStatusValidator->validate() expects to be called once, take an array argument of the payment status string to throw an InvalidArgumentException.
        $this->setUpPayStatusValidMockValidateExceptionMethod($paymentStatusValidator, 1, ['status' => $status], $errorMessage);

        /*
	    *  Create a new instance of Payments and assert that the getPayments() response is an error message
        *  as well as that the contents of the error message are as expected.
	    */
        $payments = new Payments($pdo, $paymentStatusValidator, $paymentDueDatesValidator);
        $result = $payments->getPayments($status, $startDate, $endDate);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('Error - ' . $errorMessage, $result['error']);
    }

    /**
    * Checks to see that an exception is thrown when an invalid payment status is supplied.
    */
    public function testErrorWhenInvalidDatesArePassedTest():	void
    {
        // Set the payment status string and mock start + end DateTime objects for the test.
        $status = 'failed';
        $errorMessage = 'Error Message';

        /*
         * Create the mocks that will be used in the test.
         */
        $startDate = $this->createMock(DateTime::class);
        $endDate = $this->createMock(DateTime::class);
        $paymentStatusValidator = $this->createMock(PaymentStatusValidator::class);
        $paymentDueDatesValidator = $this->createMock(PaymentDueDatesValidator::class);
        $pdo = $this->createMock(PDO::class);

        /*
         * Set up the mock methods that will be used in the test, along with expectations and return values.
         */

        // $paymentStatusValidator->validate() expects to be called once, take an array argument of the payment status string and return a value of true.
        $this->setUpPaymentStatusValidatorMockValidateMethod($paymentStatusValidator, 1, ['status' => $status], true);

        // $paymentDueDatesValidator->validate() expects to be called once, take an array argument of the payment start and end dates for the due date range and return a value of true.
        $this->setUpPayDueDatesValidMockValidateExceptionMethod($paymentDueDatesValidator, 1, ['startDate' => $startDate, 'endDate' => $endDate], $errorMessage);

        /*
	    *  Create a new instance of Payments and assert that the getPayments() response is an error message
         * as well as that the contents of the error message are as expected.
	    */
        $payments = new Payments($pdo, $paymentStatusValidator, $paymentDueDatesValidator);
        $result = $payments->getPayments($status, $startDate, $endDate);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('Error - ' . $errorMessage, $result['error']);
    }

    /**
     * Constructs the same SQL query that's used in the getPayments() method of the Payments class
     * and returns it for use in the mocked PDO class in testPaymentsRetrieved().
     *
     * @return string - The SQL query.
     */
    private function getPaymentsSQLQuery(): string
    {
        $query = 'SELECT';
        $query .= 'pl.plan_id, pc.pc_title, pc.pc_first_name, pc.pc_last_name, u.user_first_name, u.user_last_name, c.company_name';
        $query .= 'FROM';
        $query .= 'plan pl';
        $query .= 'INNER JOIN plan_client pc ON pl.plan_id = pc.pc_plan_id';
        $query .= 'INNER JOIN user u ON pl.plan_lead_gen = u.user_id';
        $query .= 'INNER JOIN company c ON u.user_company = c.company_id';
        $query .= 'INNER JOIN payment pay ON pl.plan_id = pay.payment_plan';
        $query .= 'WHERE';
        $query .= 'pay.payment_status = ?';
        $query .= 'AND';
        $query .= '(';
        $query .= 'pay.payment_due_date > ?';
        $query .= 'AND';
        $query .= 'pay.payment_due_date < ?';
        $query .= ')';

        return $query;
    }

    /**
     * Sets up the format() method for a mock instance of DateTime, according to the arguments supplied.
     *
     * @param MockObject $date - The mock DateTime instance that the method will be set up for.
     * @param int $numCalls - The number of calls that are expected to be made to the mock method.
     * @param string $formatArgument - The argument that the mock method expects to receive.
     * @param string $returnValue - The response that is expected when the DateTime format() method runs successfully.
     *
     * @return void
     */
    private function setUpDateTimeMockFormatMethod(MockObject $date, int $numCalls, string $formatArgument, string $returnValue)
    {
        $date->expects($this->exactly($numCalls))
            ->method('format')
            ->with($formatArgument)
            ->willReturn($returnValue);
    }

    /**
     * Sets up the execute() method for a mock instance of PDOStatement, according to the arguments supplied.
     *
     * @param MockObject $stmt - The mock PDOStatement instance that the method will be set up for.
     * @param int $numCalls - The number of calls that are expected to be made to the mock method.
     * @param array $executeArgument - The argument that the mock method expects to receive.
     *
     * @return void
     */
    private function setUpPDOStatementMockExecuteMethod(MockObject $stmt, int $numCalls, array $executeArgument)
    {
        $stmt->expects($this->exactly($numCalls))
            ->method('execute')
            ->with($executeArgument);
    }

    /**
     * Sets up the fetchAll() method for a mock instance of PDOStatement, according to the arguments supplied.
     *
     * @param MockObject $stmt - The mock PDOStatement instance that the method will be set up for.
     * @param int $numCalls - The number of calls that are expected to be made to the mock method.
     * @param array $returnValue - The response that is expected when the PDOStatement fetchAll() method runs successfully.
     *
     * @return void
     */
    private function setUpPDOStatementMockFetchAllMethod(MockObject $stmt, int $numCalls, array $returnValue)
    {
        $stmt->expects($this->exactly($numCalls))
            ->method('fetchAll')
            ->willReturn($returnValue);
    }

    /**
     * Sets up the prepare() method for a mock instance of PDO, according to the arguments supplied.
     *
     * @param MockObject $pdo - The mock PDO instance that the method will be set up for.
     * @param int $numCalls - The number of calls that are expected to be made to the mock method.
     * @param string $prepareArgument - The argument that the mock method expects to receive.
     * @param MockObject $returnValue - The response that is expected when the PDO prepare() method runs successfully.
     *
     * @return void
     */
    private function setUpPDOMockPrepareMethod(MockObject $pdo, int $numCalls, string $prepareArgument, MockObject $returnValue)
    {
        $pdo->expects($this->exactly($numCalls))
            ->method('prepare')
            ->with($prepareArgument)
            ->willReturn($returnValue);
    }

    /**
     * Sets up the validate() method for a mock instance of PaymentStatusValidator, according to the arguments supplied.
     *
     * @param MockObject $paymentStatusValidator - The mock PaymentStatusValidator instance that the method will be set up for.
     * @param int $numCalls - The number of calls that are expected to be made to the mock method.
     * @param array $validateArgument - The argument that the mock method expects to receive.
     * @param bool $returnValue - The response that is expected when the PaymentStatusValidator validate() method runs successfully.
     *
     * @return void
     */
    private function setUpPaymentStatusValidatorMockValidateMethod(MockObject $paymentStatusValidator, int $numCalls, array $validateArgument, bool $returnValue)
    {
        $paymentStatusValidator->expects($this->exactly($numCalls))
            ->method('validate')
            ->with($validateArgument)
            ->willReturn($returnValue);
    }

    /**
     * Sets up the validate() method for a mock instance of PaymentStatusValidator that will throw an InvalidArgumentException.
     *
     * @param MockObject $paymentStatusValidator - The mock PaymentStatusValidator instance that the method will be set up for.
     * @param int $numCalls - The number of calls that are expected to be made to the mock method.
     * @param array $validateArgument - The argument that the mock method expects to receive.
     * @param string $errorMessage - The message that will be passed on by the InvalidArgumentException mock instance.
     *
     * @return void
     */
    private function setUpPayStatusValidMockValidateExceptionMethod(MockObject $paymentStatusValidator, int $numCalls, array $validateArgument, string $errorMessage)
    {
        $paymentStatusValidator->expects($this->exactly($numCalls))
            ->method('validate')
            ->with($validateArgument)
            ->willThrowException(new InvalidArgumentException($errorMessage));
    }

    /**
     * Sets up the validate() method for a mock instance of PaymentDueDatesValidator, according to the arguments supplied.
     *
     * @param MockObject $paymentDueDatesValidator - The mock PaymentDueDates instance that the method will be set up for.
     * @param int $numCalls - The number of calls that are expected to be made to the mock method.
     * @param array $validateArgument - The argument that the mock method expects to receive.
     * @param bool $returnValue - The response that is expected when the PaymentDueDates validate() method runs successfully.
     *
     * @return void
     */
    private function setUpPaymentDueDatesValidatorMockValidateMethod(MockObject $paymentDueDatesValidator, int $numCalls, array $validateArgument, bool $returnValue)
    {
        $paymentDueDatesValidator->expects($this->exactly($numCalls))
            ->method('validate')
            ->with($validateArgument)
            ->willReturn($returnValue);
    }

    /**
     * Sets up the validate() method for a mock instance of PaymentDueDates that will throw an InvalidArgumentException.
     *
     * @param MockObject $paymentDueDates - The mock PaymentDueDates instance that the method will be set up for.
     * @param int $numCalls - The number of calls that are expected to be made to the mock method.
     * @param array $validateArgument - The argument that the mock method expects to receive.
     * @param string $errorMessage - The message that will be passed on by the InvalidArgumentException mock instance.
     *
     * @return void
     */
    private function setUpPayDueDatesValidMockValidateExceptionMethod(MockObject $paymentDueDates, int $numCalls, array $validateArgument, string $errorMessage)
    {
        $paymentDueDates->expects($this->exactly($numCalls))
            ->method('validate')
            ->with($validateArgument)
            ->willThrowException(new InvalidArgumentException($errorMessage));
    }
}