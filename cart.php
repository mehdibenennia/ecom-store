<?php
    require_once(__DIR__."/_includes/utils/_init.php");
    require_once(__DIR__."/_includes/models/user.php");
    require_once(__DIR__."/_includes/models/product.php");
    require_once(__DIR__."/_includes/models/cart.php");
    if(isset($_POST['add'])) {
        $product = Product::find_by_id($_POST['add']);
        if($product) {
            $cart = new Cart();
            $cart->add($product);
        }
    } else if(isset($_POST['remove'])) {
        $product = Product::find_by_id($_POST['remove']);
        if($product) {
            $cart = new Cart();
            $cart->remove($product->getID());
        }
    } else if(isset($_POST['clear'])) {
        $cart = new Cart();
        $cart->clear();
    }
    $cart = new Cart();
    require_once(__DIR__ . "/_includes/views/cart.php");