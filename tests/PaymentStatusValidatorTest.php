<?php

use PHPUnit\Framework\TestCase;
use App\Actions\Payments\PaymentStatusValidator;

class PaymentStatusValidatorTest extends TestCase
{
    /**
     * Checks to see that a payment status string is successfully validated when a valid status
     * is supplied.
     */
    public function testPaymentStatusValidated():	void
    {
        $paymentStatusValidator = new PaymentStatusValidator();
        $result = $paymentStatusValidator->validate(['status' => 'failed']);

        $this->assertEquals(true, $result);
    }

    /**
     * Checks to see that an exception is thrown when an array that lacks a ‘status’ key
     * is supplied.
     */
    public function testExceptionThrownWhenIncorrectArrayKeySupplied():	void
    {
        $paymentStatusValidator = new PaymentStatusValidator();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error - status is missing');
        $paymentStatusValidator->validate(['incorrectKey' => 'failed']);
    }

    /**
     * Checks to see that an exception is thrown when the supplied status
     * is not a string.
     */
    public function testExceptionThrownWhenStatusIsNotString():	void
    {
        $paymentStatusValidator = new PaymentStatusValidator();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error - invalid data type supplied for status, string expected.');
        $paymentStatusValidator->validate(['status' => 2]);
    }

    /**
     * Checks to see that an exception is thrown when the supplied status
     * does not match any of the allowed values.
     */
    public function testExceptionThrownWhenStatusIsNotValid():	void
    {
        $paymentStatusValidator = new PaymentStatusValidator();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Error - Payment status "invalidStatus" is invalid.');
        $paymentStatusValidator->validate(['status' => 'invalidStatus']);
    }
}