<?php
require_once(__DIR__ . "/model.php");
require_once(__DIR__ . "/cart.php");
class Address
{
    public $address;
    public $city;
    public $zip;
    public $country;
    public function __construct($address, $city, $zip, $country)
    {
        $this->address = $address;
        $this->city = $city;
        $this->zip = $zip;
        $this->country = $country;
    }
    public function toArray()
    {
        return [
            "address" => $this->address,
            "city" => $this->city,
            "zip" => $this->zip,
            "country" => $this->country,
        ];
    }
    public function __serialize(): array
    {
        return $this->toArray();
    }
    public function __unserialize(array $data): void
    {
        $this->address = $data["address"];
        $this->city = $data["city"];
        $this->zip = $data["zip"];
        $this->country = $data["country"];
    }
}
class CCard
{
    public $number;
    public $exp_month;
    public $exp_year;
    public $cvc;
    public function __construct($number, $exp_month, $exp_year, $cvc)
    {
        $this->number = $number;
        $this->exp_month = $exp_month;
        $this->exp_year = $exp_year;
        $this->cvc = $cvc;
    }
    public function toArray()
    {
        return [
            "card_number" => $this->number,
            "card_exp_month" => $this->exp_month,
            "card_exp_year" => $this->exp_year,
            "card_cvc" => $this->cvc,
        ];
    }
    public function __serialize(): array
    {
        return $this->toArray();
    }
    public function __unserialize(array $data): void
    {
        $this->number = $data["card_number"];
        $this->exp_month = $data["card_exp_month"];
        $this->exp_year = $data["card_exp_year"];
        $this->cvc = $data["card_cvc"];
    }
}

class Command extends Model
{
    private $id;
    public $fname;
    public $lname;
    public $address;
    public $card;
    public $created_at;
    public $is_paid = false;
    public Cart $cart;
    public function __construct($fname = "",$lname = "",$cart = null,$address = null, $card = null)
    {
        global $current_user;
        parent::__construct();
        $this->user_id = $current_user->getID();
        $this->fname = $fname;
        $this->lname = $lname;
        $this->cart = $cart ?? new Cart();
        $this->address = $address ?? new Address("", "", "", "");
        $this->card = $card;
    }
    public function getID(): int
    {
        return $this->id;
    }
    public function copy(array $command)
    {
        $this->id = $command["id"];
        $this->fname = $command["first_name"];
        $this->lname = $command["last_name"];
        $this->is_paid = $command["payed"];
        $this->created_at = $command["date"];
        $this->address = new Address(
            $command["address"],
            $command["city"],
            $command["zip"],
            $command["country"]
        );
        $this->card = new CCard(
            $command["card_number"],
            $command["card_exp_month"],
            $command["card_exp_year"],
            $command["card_cvc"]
        );
    }
    public function addCart(Cart $cart) {
        $this->cart = $cart;
    }
    public static function newCommand($user_id)
    {
        $command = new Command();
        $command->user_id = $user_id;
        $command->save();
        return $command;
    }
    public static function find_by_user($user_id)
    {
        $c = new Command();
        $orders = $c->fetchColumn("orders", ["client" => $user_id], "id");
        $orders = array_map(function ($order) {
            $command = new Command();
            $command->id = $order["id"];
            $command->load();
            return $command;
        }, $orders);
        return $orders;
    }
    public static function find_by_id($id)
    {
        $command = new Command();
        $command->id = $id;
        $command->load();
        return $command;
    }
    private function load()
    {
        $command = $this->get("orders", "id", $this->id);
        $this->copy($command);
        $products = $this->getAllWhere("order_prod", "command", $this->id);
        $cart = [];
        $total = 0;
        foreach ($products as $product) {
            $cart[$product['id']] = [
                "quantity" => $product['quantity'],
                "price" => $product['price'],
                "name" => $this->get("products", "id", $product['id'])['name'],
            ];
            $total += $product['price'] * $product['quantity'];
        }
        $data = new stdClass();
        $data->cart = $cart;
        $data->total = $total;
        $this->cart = new Cart($data);
    }
    public function getCart()
    {
        return $this->cart;
    }
    public function getUser()
    {
        return [
            "firstname" => $this->fname,
            "lastname" => $this->lname,
        ];
    }
    public function save()
    {
        if(!$this->card){
            $this->is_paid = false;
            $this->card = new CCard("", "", "", "");
        } else {
            $this->is_paid = true;
        }
        $id = $this->create("orders", array_merge([
            "first_name" => $this->fname,
            "last_name" => $this->lname,
            "client" => $this->user_id,
            "payed" => $this->is_paid ? 1 : 0,
            "date" => date("Y-m-d H:i:s"),
        ], $this->address->toArray(), $this->card->toArray()));
        if($id){
            $this->id = $id;
            foreach ($this->cart->getCart() as $id => $product) {
                $this->create("order_prod", [
                    "command" => $this->id,
                    "product" => $id,
                    "quantity" => $product['quantity'],
                    "price" => $product['price'],
                ]);
            }
            return true;
        }
        return false;
    }
}
