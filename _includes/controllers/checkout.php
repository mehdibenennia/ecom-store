<?php
require_once(__DIR__ . "/../utils/_init.php");
require_once(__DIR__ . "/../models/product.php");
require_once(__DIR__ . "/../models/user.php");
require_once(__DIR__ . "/../models/cart.php");
require_once(__DIR__ . "/../models/command.php");
$cart = new Cart();
$error = "";
if (isset($_POST["submit"])) {
    switch ($_POST["submit"]) {
        case 'pay_now':
            if (count($set_errors = m_isset($_POST, ["first_name", "last_name", "email", "address", "city", "zip", "country", "card-number", "card-expiration-date", "card-cvc"])) > 0) {
                $error .= "Please fill in all the fields";
            } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $error .= "Please enter a valid email";
            } else if (!filter_var($_POST["zip"], FILTER_VALIDATE_INT)) {
                $error .= "Please enter a valid zip code";
            } else if (m_checkalpha($_POST, ["first_name", "last_name"])) {
                $error .= "Please enter a valid name";
            } else {
                $exp = explode("/", $_POST["card-expiration-date"]);
                if(count($exp) != 2) {
                    $error .= "Please enter a valid expiration date";
                } else {
                $exp_month = $exp[0];
                $exp_year = $exp[1];
                $address = new Address($_POST["address"], $_POST["city"], $_POST["zip"], $_POST["country"]);
                $card = new CCard($_POST["card-number"], $exp_month, $exp_year, $_POST["card-cvc"]);
                $command = new Command($_POST['first_name'], $_POST['last_name'], $cart, $address, $card);
                $command->save();
                $cart->clear();
                header("Location: ".PROJECT_URL."/command.php?id=" . $command->getID());
                }
            }
            break;
        case 'pay_later':
            if (count($set_errors = m_isset($_POST, ["first_name", "last_name", "email", "address", "city", "zip", "country"])) > 0) {
                $error .= "Please fill in all the fields";
            } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $error .= "Please enter a valid email";
            } else if (!filter_var($_POST["zip"], FILTER_VALIDATE_INT)) {
                $error .= "Please enter a valid zip code";
            } else if (m_checkalpha($_POST, ["first_name", "last_name"])) {
                $error .= "Please enter a valid name";
            } else {
                $address = new Address($_POST["address"], $_POST["city"], $_POST["zip"], $_POST["country"]);
                $command = new Command($_POST['first_name'], $_POST['last_name'], $cart, $address);
                if($command->save()){
                    $cart->clear();
                    header("Location: ".PROJECT_URL."/command.php?id=" . $command->getID());
                    exit;
                }
            }
    }
}
require_once(__DIR__ . "/../views/checkout.php");
