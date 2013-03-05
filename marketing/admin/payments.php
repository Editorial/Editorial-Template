<h2>Payments</h2>
<?php

// load payments so we can (re)send emails
$Purchase = new Purchase();

// resend something?
if (array_key_exists('resend', $_GET))
{
    $Purchase->sendDownloadMail($_GET['resend']);
    dump('Download email sent');
}
else if (array_key_exists('complete', $_GET))
{
    $Purchase->complete($_GET['complete']);
    dump('Purchase completed');
}

$payments = $Purchase->findConfirmed();

printf('<p>Total number of purchases: <strong>%d</strong></p>', count($payments));

if (count($payments))
{
    echo '<table cellspacing="0">
        <tr>
            <th>Name (email)</th>
            <th>Address</th>
            <th>Country</th>
            <th>Domains</th>
            <th>Status</th>
            <th>Options</th>
        </tr>';
    $Account = new Account();
    foreach ($payments as $payment)
    {
        $account = $Account->findById($payment['account_id']);
        printf(
            '<tr>
                <td>%s (%s)</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
            </tr>',
            $account['name'],
            $account['email'],
            $account['address'],
            $account['country'],
            implode('<br />', json_decode($payment['domains'])),
            $payment['status'] == Purchase::STATUS_CONFIRMED ? 'Confirmed' : 'Completed',
            $payment['status'] == Purchase::STATUS_COMPLETED 
                ? sprintf(
                    '<a href="?page=marketing&resend=%d">Resend email</a>',
                    $payment['purchase_id']
                ) 
                : sprintf(
                    '<a href="?page=marketing&complete=%d">Complete payment</a>',
                    $payment['purchase_id']
                )
        );
    }
    echo '</table>';
}

?>