<?php
    require_once(__DIR__."/_init.php");
    require_once(__DIR__."/../_includes/models/product.php");
    $error = "";
    if(isset($_POST['submit'])){
        if(count($fields_error = m_isset($_POST,['username','email','first_name','last_name'])) > 0) {
            $error .= implode(" & ",$fields_error) . ' are required \n';
        } else if (count($alpha_error = m_checkalpha($_POST,['first_name','last_name'])) > 0) {
            $error .= implode(" & ",$alpha_error) . ' must be alphabetic\n';
        } else if(!check_email($_POST['email'])) {
            $error .= "Invalid email\n";
        } else if(!check_username($_POST['username'])) {
            $error .= "Username must be at least 3 characters\n";
        } else {
            $user = User::find_by_username($_POST['username']);
            if(!$user){
                $user = User::newUser(
                    $_POST['username'],
                    $_POST['password'],
                    $_POST['email'],
                    $_POST['first_name'],
                    $_POST['last_name'],
                );
                header("Location: user.php?id=".$user->username);
            } else {
                $error .= "User already exists\n";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User</title>
</head>
<body>
    <form action="" method="post">
        <table>
        <tr>
                <td><label for="username">Username</label></td>
                <td><input type="text" id="username" name="username"></td>
            </tr>
            <tr>
                <td><label for="first_name">First Name</label></td>
                <td><input type="text" id="first_name" name="first_name"></td>
            </tr>
            <tr>
                <td><label for="last_name">Last Name</label></td>
                <td><input type="text" id="last_name" name="last_name"></td>
            </tr>
            <tr>
                <td><label for="email">Email</label></td>
                <td><input type="email" id="email" name="email"></td>
            </tr>
            <tr>
                <td><label for="password">Password</label></td>
                <td><input type="password" id="password" name="password"></td>
            </tr>
            <tr>
                <td><label for="password2">Repeat Password</label></td>
                <td><input type="password" id="password2" name="password2"></td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit" name="submit" value="new">New</button></td>
            </tr>
            <tr>
                <td></td>
                <td><?=$error?></td>
            </tr>
        </table>
    </form>
</body>
</html>