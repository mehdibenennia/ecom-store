<?php
require_once(__DIR__ . "/_init.php");
require_once(__DIR__ . "/../_includes/models/product.php");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = Product::find_by_name($id) or header("Location: " . PROJECT_URL . "/admin/products.php");
} else {
    header("Location: " . PROJECT_URL . "/admin/newProduct.php");
}
$error = "";
$err_product = new Product();
if (isset($_POST['submit'])) {
    switch ($_POST['submit']) {
        case 'Update':
            if (count($fields_error = m_isset($_POST, ['name', 'description', 'price', 'age', 'category'])) > 0) {
                $error .= implode(" & ", $fields_error) . ' are required \n';
            } else if (count($alpha_error = m_checkalpha($_POST, ['category'])) > 0) {
                $error .= implode(" & ", $alpha_error) . ' must be alphabetic\n';
            } else if (!check_price($_POST['price'])) {
                $error .= "Invalid price\n";
            } else if (!check_age($_POST['age'])) {
                $error .= "Invalid age\n";
            } else {
                $product = Product::find_by_name($id);
                if ($product) {
                    $product->name = $_POST['name'];
                    $product->description = $_POST['description'];
                    $product->price = $_POST['price'];
                    $product->age = $_POST['age'];
                    $product->category = $_POST['category'];
                    $product->save();
                } else {
                    $error .= "Product not found\n";
                }
            }
            break;
        case 'Delete':
            $product = Product::find_by_name($id);
            if ($product) {
                $product->delete();
            } else {
                $error .= "Product not found\n";
            }
            break;
    }
}
if (!$product) {
    $error .= "Product not found";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
</head>

<body>
    <a href="./products.php">&larr; Return</a>
    <form action="" method="post">
        <table>
            <tr>
                <td><label for="id">ID</label></td>
                <td><input type="text" id="id" name="id" value="<?= html($product->getID()) ?>"></td>
            </tr>
            <tr>
                <td><label for="name">Name</label></td>
                <td><input type="text" id="name" name="name" value="<?= html($product->name) ?>"></td>
            </tr>
            <tr>
                <td><label for="description">Description</label></td>
                <td><input type="text" id="description" name="description" value="<?= html($product->description) ?>"></td>
            </tr>
            <tr>
                <td><label for="price">Price</label></td>
                <td><input type="number" id="price" name="price" min="0" step=".01" value="<?= html($product->price) ?>"></td>
            </tr>
            <tr>
                <td><label for="age">Age</label></td>
                <td><input type="number" id="age" name="age" min="1" value="<?= html($product->age) ?>"></td>
            </tr>
            <tr>
                <td><label for="category">Category</label></td>
                <td><input type="text" id="category" name="category" value="<?= html($product->category) ?>"></td>
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