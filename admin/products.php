<?php
require_once(__DIR__ . "/_init.php");
require_once(__DIR__ . "/../_includes/models/product.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>
</head>

<body>
    <a href="./">&larr; Return</a>
    <table>
        <tr>
            <th>
                Title
            </th>
            <th>
            </th>
        </tr>
        <?php foreach (Product::listProducts() as $product) { ?>
            <tr>
                <td>
                    <?= html($product) ?>
                </td>
                <td>
                    <a href="product.php?id=<?= html($product) ?>">Edit</a>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td>
                <a href="newProduct.php">Add Product</a>
            </td>
        </tr>
    </table>
</body>

</html>