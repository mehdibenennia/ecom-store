<?php
$title = "Product - $name";
require 'head.php';
?>

<body>
    <?php require_once 'navbar.php'; ?>
    <style>
        .no-spinners {
            -moz-appearance: textfield;
        }

        .no-spinners::-webkit-outer-spin-button,
        .no-spinners::-webkit-inner-spin-button {
            margin: 0;
            -webkit-appearance: none;
        }
    </style>

    <section>
        <div class="relative max-w-screen-xl px-4 py-8 mx-auto">
            <div class="grid items-start grid-cols-1 gap-8">

                <div class="sticky top-0">

                    <div class="flex justify-between mt-8">
                        <div class="max-w-[35ch]">
                            <h1 class="text-2xl font-bold">
                                <?= $name ?>
                            </h1>
                        </div>

                        <p class="text-lg font-bold">
                            <?= $price ?> MAD
                        </p>
                    </div>

                    <div class="relative mt-4 group">

                        <div class="pb-6 prose max-w-none">
                            <p>
                                <?= $description ?>
                            </p>
                        </div>
                    </div>

                    <form action="" method="POST" class="mt-8">
                        <div class="flex mt-8">
                            <div>
                                <label for="quantity" class="sr-only">Qty</label>

                                <input type="number" name="quantity" id="quantity" min="1" value="1" class="w-12 py-3 text-xs text-center border-gray-200 rounded no-spinners" />
                            </div>

                            <button type="submit" name="submit" class="block px-5 py-3 ml-3 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-500">
                                Add to Cart
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>