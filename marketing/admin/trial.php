<h2>Trial</h2>
<?php

$Trial  = new Trial();
$trials = $Trial->findInTrial();

if (count($trials))
{
    echo '<table cellspacing="0">
        <tr>
            <th>Email</th>
            <th>Path</th>
            <th>Password</th>
            <th>Status</th>
        </tr>';
    $Account = new Account();
    foreach ($trials as $trial)
    {
        printf(
            '<tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
            </tr>',
            $trial['email'],
            sprintf('<a href="http://trial.editorialtemplate.com/%1$s/">Trial %1$s</a>', $trial['trial']),
			$trial['password'],
			$trial['status'] == Trial::TRIAL_STARTED ? 'Started' : 'Reminded'
        );
    }
    echo '</table>';
}