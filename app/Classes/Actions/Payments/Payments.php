<?php

namespace App\Actions\Payments;

use App\Actions\DBAction;
use DateTime;
use PDO;
use InvalidArgumentException;

class Payments extends DBAction
{
    /**
     * The payment status validator that will validate the provided status parameter in getPayments().
     *
     * @var PaymentStatusValidator
     */
    private PaymentStatusValidator $paymentStatusValidator;

    /**
     * The payment due dates validator that will validate the provided status parameter in getPayments().
     *
     * @var PaymentDueDatesValidator
     */
    private PaymentDueDatesValidator $paymentDueDatesValidator;

    /**
     * Set up the payment status and payment due date validators.
     *
     * @param PDO $dbConnection - The PDO connection to the DB.
     * @param PaymentStatusValidator $paymentStatusValidator - The payment status validator that will be used.
     * @param PaymentDueDatesValidator $paymentDueDatesValidator - The payment due dates validator that will be used.
     *
     */
    public function __construct(PDO $dbConnection, PaymentStatusValidator $paymentStatusValidator, PaymentDueDatesValidator $paymentDueDatesValidator)
    {
        parent::__construct($dbConnection);
        $this->paymentStatusValidator = $paymentStatusValidator;
        $this->paymentDueDatesValidator = $paymentDueDatesValidator;
    }

    /**
     * Attempts to retrieve payments from the DB according to the supplied parameters.
     *
     * @param string $status - the status of the payments to be retrieved from the DB.
     * @param DateTime $startDate - the starting date for the range of dates of the payments to be retrieved from the DB.
     * @param DateTime $endDate - the ending date for the range of dates of the payments to be retrieved from the DB.
     *
     * @return array - returns an array with an error key if any exceptions are thrown; otherwise an array of rows from the DB result set.
     */
    public function getPayments(string $status, DateTime $startDate, DateTime $endDate):  array
    {
        // Validate the supplied parameters and return an error if an exception is thrown.
        try
        {
            $this->paymentStatusValidator->validate(['status' => $status]);
            $this->paymentDueDatesValidator->validate(['startDate' => $startDate, 'endDate' => $endDate]);
        } catch (InvalidArgumentException $e)
        {
            return [
                'error' => 'Error - ' . $e->getMessage()
            ];
        }

        $query = 'SELECT ';
        $query .= 'pl.plan_id, pc.pc_title, pc.pc_first_name, pc.pc_last_name, u.user_first_name, u.user_last_name, c.company_name ';
        $query .= 'FROM ';
        $query .= 'plan pl ';
        $query .= 'INNER JOIN plan_client pc ON pl.plan_id = pc.pc_plan_id ';
        $query .= 'INNER JOIN user u ON pl.plan_lead_gen = u.user_id ';
        $query .= 'INNER JOIN company c ON u.user_company = c.company_id ';
        $query .= 'INNER JOIN payment pay ON pl.plan_id = pay.payment_plan ';
        $query .= 'WHERE ';
        $query .= 'pay.payment_status = ? ';
        $query .= 'AND ';
        $query .= '( ';
        $query .= 'pay.payment_due_date > ? ';
        $query .= 'AND ';
        $query .= 'pay.payment_due_date < ? ';
        $query .= ')';

        $pdo = $this->dbConnection->prepare($query);

        // Convert the DateTime objects into date strings in a MySql-friendly format.
        $pdo->execute([$status, $startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        return $pdo->fetchAll(PDO::FETCH_OBJ);
    }
}