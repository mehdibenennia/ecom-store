<?php
require_once(__DIR__ . "/_init.php");
require_once(__DIR__ . "/../_includes/models/user.php");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user = User::find_by_username($id) or header("Location: " . PROJECT_URL . "/admin/users.php");
}
$error = "";
if (isset($_POST['submit'])) {
    switch ($_POST['submit']) {
        case 'Update':
            if (count($fields_error = m_isset($_POST, ['username', 'email', 'first_name', 'last_name'])) > 0) {
                $error .= implode(" & ", $fields_error) . ' are required \n';
            } else if (count($alpha_error = m_checkalpha($_POST, ['first_name', 'last_name'])) > 0) {
                $error .= implode(" & ", $alpha_error) . ' must be alphabetic\n';
            } else if (!check_email($_POST['email'])) {
                $error .= "Invalid email\n";
            } else if (!check_username($_POST['username'])) {
                $error .= "Username must be at least 3 characters\n";
            } else {
                $user = User::find_by_username($id);
                if ($user) {
                    $user->username = $_POST['username'];
                    if (isset($_POST['password']) && !empty($_POST['password'])) {
                        if (!check_password($_POST['password'])) {
                            $error .= "Password must be at least 6 characters\n";
                        } else if ($_POST['password'] != $_POST['password2']) {
                            $error .= "Passwords do not match\n";
                        } else {
                            $user->setPassword($_POST['password']);
                        }
                    }
                    if (isset($_POST['is_admin'])) {
                        $user->is_admin = 1;
                    } else {
                        $user->is_admin = 0;
                    }
                    $user->email = $_POST['email'];
                    $user->first_name = $_POST['first_name'];
                    $user->last_name = $_POST['last_name'];
                    $user->save();
                } else {
                    $error .= "User not found\n";
                }
            }
            break;
        case 'Delete':
            $user = User::find_by_username($id);
            if ($user) {
                $user->delete();
            } else {
                $error .= "User not found\n";
            }
            break;
    }
} else {
    $user = User::find_by_username($id);
    if (!$user) {
        $error .= "User not found\n";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>

<body>
    <a href="./users.php">&larr; Return</a>
    <form action="" method="post">
        <table>
            <tr>
                <td><label for="username">Username</label></td>
                <td><input type="text" id="username" name="username" value="<?= html($user->username) ?>"></td>
            </tr>
            <tr>
                <td><label for="first_name">First Name</label></td>
                <td><input type="text" id="first_name" name="first_name" value="<?= html($user->first_name) ?>"></td>
            </tr>
            <tr>
                <td><label for="last_name">Last Name</label></td>
                <td><input type="text" id="last_name" name="last_name" value="<?= html($user->last_name) ?>"></td>
            </tr>
            <tr>
                <td><label for="email">Email</label></td>
                <td><input type="email" id="email" name="email" value="<?= html($user->email) ?>"></td>
            </tr>
            <tr>
                <td><label for="password">Password</label></td>
                <td><input type="password" id="password" name="password" value=""></td>
            </tr>
            <tr>
                <td><label for="password2">Repeat Password</label></td>
                <td><input type="password" id="password2" name="password2" value=""></td>
            </tr>
            <tr>
                <td><label for="is_admin">Admin</label></td>
                <td><input type="checkbox" name="is_admin" id="is_admin" <?= $user->is_admin ? "checked" : "" ?>></td>
            </tr>
            <tr>
                <td><input type="button" formaction="" name="submit" value="Delete"></td>
                <td><input type="submit" name="submit" value="Update"></td>
            </tr>
            <tr>
                <td></td>
                <td><?= $error ?></td>
            </tr>
        </table>
    </form>
</body>

</html>