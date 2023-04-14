<?php
    require_once(__DIR__."/_init.php");
    require_once(__DIR__."/../_includes/models/product.php");
    $error = "";
    if(isset($_POST['submit'])){
        if(count($fields_error = m_isset($_POST,['name','description','price','age','category'])) > 0) {
            $error .= implode(" & ",$fields_error) . ' are required \n';
        } else if (count($alpha_error = m_checkalpha($_POST,['name','category'])) > 0) {
            $error .= implode(" & ",$alpha_error) . ' must be alphabetic\n';
        } else if(!check_price($_POST['price'])) {
            $error .= "Invalid price\n";
        } else if(!check_age($_POST['age'])) {
            $error .= "Invalid age\n";
        } else {
            $product = Product::find_by_name($id);
            if(!$product) {
                $product = Product::newProduct(
                    $_POST['name'],
                    $_POST['description'],
                    $_POST['price'],
                    $_POST['category'],
                    $_POST['age']
                );
                header("Location: product.php?id=".$product->name);
            } else {
                $error .= "Product Exist\n";
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
    <title>New Product</title>
</head>
<body>
    <a href="./">&larr; Return</a>
    <form action="" method="post">
        <table>
            <tr>
                <td><label for="name">Name</label></td>
                <td><input type="text" id="name" name="name"></td>
            </tr>
            <tr>
                <td><label for="description">Description</label></td>
                <td><input type="text" id="description" name="description"></td>
            </tr>
            <tr>
                <td><label for="price">Price</label></td>
                <td><input type="number" min="0" id="price" name="price"></td>
            </tr>
            <tr>
                <td><label for="age">Age</label></td>
                <td><input type="number" min="1" id="age" name="age"></td>
            </tr>
            <tr>
                <td><label for="category">Category</label></td>
                <td><input type="text" id="category" name="category"></td>
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