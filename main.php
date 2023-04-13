<?php

    require_once 'siteFunctions/masterPage.php';

    $pg = new MasterPage();

    $content = '<div><p>This is a minimum viable product with core functionality<br>
    You will need to <strong><a href="build.php">build the database</a></strong> before you can begin working with this site.<br>
    All Login Information for testing purposes is below<br>
    You can also log into an admin account and then create new accounts for your business</p></div>';

    $content.= '<table>
                    <tr><th>Email</th><th>Password</th><th>Account Type</th></tr>
                    <tr><td>adamjensen@sarrifindustries.com</td><td>password</td><td>Buyer</td></tr>
                    <tr><td>francispritchard@sarrifindustries.com</td><td>password</td><td>Seller</td></tr>
                    <tr><td>davidsarrif@sarrifindustries.com</td><td>password</td><td>Admin</td></tr>
                    <tr><td>lawrencebarret@belltower.com</td><td>password</td><td>Buyer</td></tr>
                    <tr><td>pieterburke@belltower.com</td><td>password</td><td>Seller</td></tr>
                    <tr><td>rogerjohnffolkes@belltower.com</td><td>password</td><td>Admin</td></tr>
                </table>';


    $pg->setTitle('Welcome to the Agora');
    $pg->setContent($content);
    print $pg->getHtml();

?> 