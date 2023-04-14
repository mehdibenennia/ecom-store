<?php
    class Cart
    {
        private $cart = [];
        private $total = 0;
        public function __construct($from = null)
        {
            if($from) {
                $this->cart = $from->cart;
                $this->total = $from->total;
            } else if (isset($_SESSION["cart"])) {
                $this->cart = $_SESSION["cart"];
            }
            $this->total = 0;
            foreach ($this->cart as $product) {
                $this->total += $product["price"] * $product["quantity"];
            }
        }
        public function add(Product $product, int $quantity = 1)
        {
            if(isset($this->cart[$product->getID()])){
                $this->cart[$product->getID()]["quantity"] += $quantity;
            } else {
                $this->cart[$product->getID()] = [
                    "name" => $product->name,
                    "price" => $product->price,
                    "quantity" => $quantity,
                ];
            }
            $_SESSION["cart"] = $this->cart;
        }
        public function add_products(array $products)
        {
            foreach ($products as $product) {
                $this->add($product);
            }
        }
        public function remove(int $id)
        {
            if(isset($this->cart[$id])) {
                if($this->cart[$id]["quantity"] > 1) {
                    $this->cart[$id]["quantity"]--;
                } else {
                    unset($this->cart[$id]);
                }
            }
            $_SESSION["cart"] = $this->cart;
        }
        public function getTotal()
        {
            return $this->total;
        }
        public function getCart()
        {
            return $this->cart;
        }
        public function count()
        {
            $count = 0;
            foreach ($this->cart as $product) {
                $count += $product["quantity"];
            }
            return $count;
        }
        public function isEmpty()
        {
            return empty($this->cart);
        }
        public function clear()
        {
            $this->cart = [];
            $_SESSION["cart"] = $this->cart;
        }
    }
    