<?php
require_once(__DIR__ . "/../models/user.php");
require_once(__DIR__ . "/../models/product.php");
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = Product::find_by_id($id);
    if($product) {
        $name = $product->name;
        $description = $product->description;
        $price = $product->price;
        $category = $product->category;
        $age = $product->age;
        if(isset($_POST['submit'])) {
            $quantity = $_POST['quantity'];
            $cart = new Cart();
            $cart->add($product, $quantity);
            header("Location: ".PROJECT_URL."/cart.php");
        }
        require_once(__DIR__."/../views/product.php");
    }
}
