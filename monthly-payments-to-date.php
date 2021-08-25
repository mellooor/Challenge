<?php
require_once('include/init.php');

use App\Actions\Payments\Payments;
use App\Actions\Payments\PaymentStatusValidator;
use App\Actions\Payments\PaymentDueDatesValidator;
use App\DateTime\DateDurationCalculator;

// Payment due date range is from the first day of the month to the current day and only payment statuses of 'failed' are targeted.
$startDate = new DateTime("first day of this month");
$endDate = new DateTime("today");
$paymentStatus = "failed";

/*
 * Create a new instance of the payments class, using the $dbConnection variable that was initialised in init.php
 * as an argument, along with fresh instances of the PaymentStatusValidator and PaymentDueDatesValidator classes.
 */
$payments = new Payments($dbConnection, new PaymentStatusValidator(), new PaymentDueDatesValidator(new DateDurationCalculator()));

// Retrieve any payments that meet the supplied criteria.
$retrievedPayments = $payments->getPayments($paymentStatus, $startDate, $endDate);

?>

<?php require_once('include/head.php') ?>

<body>
<div id="failed-payments-container">
    <?php if (array_key_exists('error', $retrievedPayments)) : // Display the error message if one is returned. ?>
        <p class="error-message"><?php echo $retrievedPayments['error'] ?></p>
    <?php else : ?>
        <h1 id="container-title">Failed Payments for Date Range (August)</h1>
        <?php if (count($retrievedPayments) === 0) : // If no payments are returned, display a message to inform the user. ?>
            <p>There are no failed payments for August</p>
        <?php else : ?>
            <div id="table-container">
                <table id="failed-payments-table">
                    <thead>
                    <tr>
                        <th>Plan ID</th>
                        <th>Plan Owner</th>
                        <th>Created By</th>
                        <th>Company</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($retrievedPayments as $payment) : // Display all of the relevant payment information in the table for each payment. ?>
                        <tr>
                            <td><?php echo $payment->plan_id ?></td>
                            <td><?php echo $payment->pc_title . ' ' . $payment->pc_first_name . ' ' . $payment->pc_last_name ?></td>
                            <td><?php echo $payment->user_first_name . ' ' . $payment->user_last_name ?></td>
                            <td><?php echo $payment->company_name ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif ?>
</div>
</body>

<?php require_once('include/foot.php') ?>
