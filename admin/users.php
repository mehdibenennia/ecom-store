<?php
require_once(__DIR__ . "/_init.php");
require_once(__DIR__ . "/../_includes/models/user.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
</head>

<body>
    <a href="./">&larr; Return</a>
    <table>
        <tr>
            <th>
                username
            </th>
            <th>
            </th>
        </tr>
        <?php foreach(User::listUsers() as $user){ ?>
        <tr>
            <td>
                <?=html($user)?>
            </td>
            <td>
                <a href="user.php?id=<?=html($user)?>">Edit</a>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td>
                <a href="newUser.php">New User</a>
            </td>
        </tr>
    </table>
</body>

</html>