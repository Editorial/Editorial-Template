<h2>Promo codes</h2>
<?php

$Promo = new Promo();

// try to add promo code
if (count($_POST))
{
    try
    {
        $Promo->insert($_POST);
        dump('Promo code inserted');
    } 
    catch (Exception $e)
    {
        dump($e->getMessage());
    }
}

// outputs param if it is in post
function evo($param)
{
    echo array_key_exists($param, $_POST) ? $_POST[$param] : '';
}

?>
<form action="" id="promo" method="post">
    <fieldset>
        <legend>Add new promo code</legend>
        <p>
            <label>Title <input type="text" id="title" name="title" value="<?php evo('title'); ?>" /></label>
        </p>
        <p>
            <label>Code (leave empty to generate) <input type="text" id="code" name="code" value="<?php evo('code'); ?>" /></label>
        </p>
        <p>
            <label>Discount (0-99) <input type="text" id="discount" name="discount" value="<?php evo('discount'); ?>" /></label>
        </p>
        <p>
            <label>Count (number of times the code can be used) <input type="text" id="count" name="count" value="<?php evo('count'); ?>" /></label>
        </p>
        <p>
            <label>Date valid (format 2012-01-07 22:05) <input type="text" id="date_valid" name="date_valid" value="<?php evo('date_valid'); ?>" /></label>
        </p>
        <p>
            <input type="submit" value="Add promo code" class="button-primary" />
        </p>
    </fieldset>
</form>
<?php

// find active promo codes
$promos = $Promo->findActive();

if (count($promos))
{
    echo '<table cellspacing="0" id="promo_list">
        <tr>
            <th>Title</th>
            <th>Code</th>
            <th>Dicount</th>
            <th>Count (Used)</th>
            <th>Date valid</th>
        </tr>';
    $Account = new Account();
    foreach ($promos as $promo)
    {
        printf(
            '<tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%d (%d)</td>
                <td>%s</td>
            </tr>',
            $promo['title'],
            $promo['code'],
            $promo['discount'],
            $promo['count'],
            $promo['used'],
            $promo['date_valid']
        );
    }
    echo '</table>';
}