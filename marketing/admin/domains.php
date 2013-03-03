<h2>Domains</h2>
<?php

$Domain  = new Domain();

if (count($_POST))
{
    try
    {
        $Domain->insertFake($_POST['domain']);
        dump('Domain insterted');
    } 
    catch (Exception $e)
    {
        dump($e->getMessage());
    }
}

?>

<form action="" id="domain" method="post">
    <fieldset>
        <legend>Add new domain (to avoid pirate message) - will not be linked to any account</legend>
        <p>
            <label>Domain (format: http://domain.com, no ending slash, no subfolder) <input type="text" id="domain" name="domain" value="" /></label>
        </p>
        <p>
            <input type="submit" value="Add domain" class="button-primary" />
        </p>
    </fieldset>
</form>

<?php

$domains = $Domain->findAll();

if (count($domains))
{
    echo '<table cellspacing="0">
        <tr>
            <th>Name (email)</th>
            <th>Domain</th>
        </tr>';
    $Account = new Account();
    foreach ($domains as $domain)
    {
        $account = $Account->findById($domain['account_id']);
        $details = '';
        if ($account)
        {
            $details = sprintf('%s (%s)', $account['name'], $account['email']);
        }
        printf(
            '<tr>
                <td>%1$s</td>
                <td><a href="%2$s">%2$s</a></td>
            </tr>',
            $details,
            $domain['name']
        );
    }
    echo '</table>';
}