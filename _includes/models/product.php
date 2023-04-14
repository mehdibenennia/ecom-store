<?php
require_once(__DIR__ . "/model.php");
class Product extends Model
{
    private $id = null;
    public $name;
    public $description;
    public $price;
    public $category;
    public $age;
    public $quantity = 1;
    public function __construct(int $id = null)
    {
        parent::__construct();
        if ($id) {
            $this->id = $id;
            $this->load();
        }
    }
    public function getID()
    {
        return $this->id;
    }
    static function newProduct(
        string $name,
        string $description,
        float $price,
        string $category,
        int $age,
    ) {
        $product = new Product();
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;
        $product->category = $category;
        $product->age = $age;
        $product->create("products", [
            "name" => $product->name,
            "description" => $product->description,
            "price" => $product->price,
            "category" => $product->category,
            "age" => $product->age,
        ]);
        return $product;
    }
    public function copy(array $product)
    {
        $this->id = $product["id"];
        $this->name = $product["name"];
        $this->description = $product["description"];
        $this->price = $product["price"];
        $this->category = $product["category"];
        $this->age = $product["age"];
    }
    public function loadProduct(string $name)
    {
        $product = $this->get("products", "name", $name);
        if ($product)
            $this->copy($product);
        return $product;
    }
    public function load()
    {
        $product = $this->get("products", "id", $this->id);
        if ($product)
            $this->copy($product);
        return $product;
    }
    public static function listProducts()
    {
        global $__def_product;
        $products = [];
        $result = $__def_product->fetchColumn("products", [], "name", "name", "ASC");
        foreach ($result as $product) {
            $products[] = $product["name"];
        }
        return $products;
    }
    public static function count()
    {
        global $__def_product;
        $count = $__def_product->countAll("products");
        return $count;
    }
    public static function find(array $conditions = [])
    {
        $product = new Product();
        $conds = [];
        $order = null;
        $asc = null;
        if (isset($conditions["age"])) {
            $conds["age?lte"] = $conditions["age"];
        }
        if (isset($conditions["q"]) && !empty($conditions["q"])) {
            $conds[] = [
                ["name?contains" => $conditions["q"]],
                ["description?contains" => $conditions["q"]],
            ];
        }
        if (isset($conditions["sort_by"])) {
            $sort = explode("-",$conditions["sort_by"]);
            $sorts = ["title"=>"name","price"=>"price"];
            $ord = ["asc"=>"ASC","desc"=>"DESC"];
            if(count($sort) == 2) {
                if(isset($sorts[$sort[0]]) && isset($ord[$sort[1]])) {
                    $order = $sorts[$sort[0]];
                    $asc = $ord[$sort[1]];
                }
            }
        }
        if (isset($conditions["category"])) {
            $conds["category"] = $conditions["category"];
        }
        $p = $product->fetchAll("products", $conds, $order, $asc);
        $count = Product::count();
        $products = [];
        foreach ($p as $product) {
            $prod = new Product();
            $prod->copy($product);
            $products[] = $prod;
        }
        return [
            "products" => $products,
            "count" => $count,
        ];
    }
    public static function find_by_name(string $name)
    {
        $product = new Product();
        if ($product->loadProduct($name)) {
            return $product;
        }
        return null;
    }
    public static function find_by_category(string $category)
    {
        global $__def_product;
        $products = $__def_product->getAll("products", "category", $category);
        return $products;
    }
    public static function find_by_age(int $age)
    {
        global $__def_product;
        $products = $__def_product->getAll("products", "age", $age);
        return $products;
    }
    public static function find_by_id(int $id)
    {
        $product = new Product();
        $product->id = $id;
        if ($product->load()) {
            return $product;
        }
        return false;
    }
    public static function find_by_price(float $price)
    {
        global $__def_product;
        $products = $__def_product->getAll("products", "price", $price);
        return $products;
    }
    public static function get_Categories()
    {
        global $__def_product;
        $categories = $__def_product->fetchColumn("products", [], "DISTINCT category", "category", "ASC");
        return $categories;
    }
    public static function get_Ages()
    {
        global $__def_product;
        $ages = $__def_product->fetchColumn("products", [], "DISTINCT age", "age", "ASC");
        return $ages;
    }
    public function save()
    {
        return $this->updateWhere("products", [
            "name" => $this->name,
            "description" => $this->description,
            "price" => $this->price,
            "category" => $this->category,
            "age" => $this->age,
        ], "id", $this->id);
    }
    public function delete()
    {
        return $this->deleteWhere("products", "id", $this->id);
    }
}
$__def_product = new Product();