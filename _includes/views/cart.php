<?php
$title = "Cart";
require 'head.php';
?>

<body>
    <?php require_once(__DIR__ . "/navbar.php"); ?>

    <br>
    <div class="m-auto block w-screen max-w-sm p-10 border sm:px-12 bg-stone-100 border-stone-600" aria-modal="true" aria-label="Item added to your cart" role="dialog" tabindex="-1">
        <div class="flex items-start justify-between">
            <h2 class="flex items-center text-gray-700">
                <?php if (isset($_POST['add'])) { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>

                    <span class="ml-2 text-sm"> Item added to your cart </span>
                <?php } else if (isset($_POST['remove'])) { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>

                    <span class="ml-2 text-sm"> Item removed from your cart </span>
                <?php } else if (isset($_POST['clear'])) { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>

                    <span class="ml-2 text-sm"> Cart cleared </span>
                <?php } ?>
            </h2>
        </div>
        <form action="" method="post">
            <?php
            /**
             * @var Cart $cart
             */
            foreach ($cart->getCart() as $id => $item) { ?>
                <div class="flex items-start pt-8 pb-6">
                    <div class="ml-4">
                        <h3 class="text-sm"><?= $item['name'] ?></h3>

                        <dl class="mt-1 space-y-1 text-xs text-gray-500">
                            <div>
                                <dt class="inline">Price:</dt>
                                <dd class="inline"><?= $item['price'] ?></dd>
                            </div>
                            <div>
                                <dt class="inline"><button name="remove" type="submit" value="<?= $id ?>">-</button></dt>
                                &nbsp;
                                <dt class="inline">Quantity:</dt>
                                <dd class="inline"><?= $item['quantity'] ?></dd>
                                &nbsp;
                                <dt class="inline"><button name="add" type="submit" value="<?= $id ?>">+</button></dt>
                            </div>
                            <div>
                            </div>
                        </dl>
                    </div>
                </div>
            <?php } ?>
        </form>
        <div class="space-y-4 text-center pt-4">
                <a href="checkout.php" class="block w-full p-3 text-sm rounded-lg bg-stone-600 text-stone-100 hover:bg-stone-500" type="submit">
                    Check out
                </a>
            <form action="" method="POST">
                <button class="block w-full p-3 text-sm rounded-lg bg-red-600 text-stone-100 hover:bg-red-400" type="submit" name="clear">
                    Clear
                </button>
            </form>
            <a class="inline-block text-sm tracking-wide underline underline-offset-4 text-stone-500 hover:text-stone-600" href="search.php">
                Continue shopping
            </a>
        </div>
    </div>
</body>

</html>