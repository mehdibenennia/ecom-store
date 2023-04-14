<?php
$title = "Checkout";
require 'head.php';
?>

<body>
    <?php require_once(__DIR__ . "/navbar.php"); ?>
    <?php if ($error) { ?>
        <div class="alert">
            <?= $error ?>
        </div>
    <?php } ?>
    <section>
        <h1 class="sr-only">Checkout</h1>

        <div class="relative mx-auto max-w-screen-2xl">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="py-12 bg-gray-50 md:py-24">
                    <div class="max-w-lg px-4 mx-auto lg:px-8">
                        <div class="flex items-center">
                            <span class="w-10 h-10 bg-blue-900 rounded-full"></span>

                            <h2 class="ml-4 font-medium"><a href="search.php">Mini Project</a></h2>
                        </div>

                        <div class="mt-8">
                            <p class="text-2xl font-medium tracking-tight"><?=
                                                                            /**
                                                                             * @var Cart $cart
                                                                             */
                                                                            $cart->getTotal() ?> MAD</p>
                            <p class="mt-1 text-sm text-gray-500">For the purchase of</p>
                        </div>

                        <div class="mt-12">
                            <div class="flow-root">
                                <ul class="-my-4 divide-y divide-gray-200">
                                    <?php function Card($title, $price, $qty)
                                    { ?>
                                        <li class="flex items-center justify-between py-4">
                                            <div class="flex items-start">
                                                <div class="ml-4">
                                                    <p class="text-sm"><?= $title ?></p>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm">
                                                    <?= $price ?> MAD
                                                    <small class="text-gray-500">x<?= $qty ?></small>
                                                </p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php
                                    /**
                                     * @var Cart $cart
                                     */
                                    foreach ($cart->getCart() as $item) { ?>
                                        <?= Card($item['name'], $item['price'], $item['quantity']) ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-12 bg-white md:py-24">

                    <div class="max-w-lg px-4 mx-auto lg:px-8">
                        <form action="" method="POST" class="grid grid-cols-6 gap-4">
                            <div class="col-span-3">
                                <label class="block mb-1 text-sm text-gray-600" for="first_name">
                                    First Name
                                </label>

                                <input class="rounded-lg shadow-sm border-gray-200 w-full text-sm p-2.5" type="text" id="first_name" name="first_name" value="<?= $current_user->first_name ?>" />
                            </div>

                            <div class="col-span-3">
                                <label class="block mb-1 text-sm text-gray-600" for="last_name">
                                    Last Name
                                </label>

                                <input class="rounded-lg shadow-sm border-gray-200 w-full text-sm p-2.5" type="text" id="last_name" name="last_name" value="<?= $current_user->last_name ?>" />
                            </div>

                            <div class="col-span-6">
                                <label class="block mb-1 text-sm text-gray-600" for="email">
                                    Email
                                </label>

                                <input class="rounded-lg shadow-sm border-gray-200 w-full text-sm p-2.5" type="email" id="email" name="email" value="<?= $current_user->email ?>" />
                            </div>

                            <fieldset class="col-span-6">
                                <legend class="block mb-1 text-sm text-gray-600">
                                    Card Details
                                </legend>

                                <div class="-space-y-px bg-white rounded-lg shadow-sm">
                                    <div x-data="{ cardNumber: '' }">
                                        <label class="sr-only" for="card-number">Card Number</label>

                                        <input x-model="cardNumber" x-mask:dynamic="$input.startsWith('34') || $input.startsWith('37') ? '9999 999999 99999' : '9999 9999 9999 9999'" class="border-gray-200 relative rounded-t-lg w-full focus:z-10 text-sm p-2.5 placeholder-gray-400" type="text" name="card-number" id="card-number" placeholder="Card number" />
                                    </div>

                                    <div class="flex -space-x-px">
                                        <div class="flex-1" x-data="{ cardExpiryDate: '' }">
                                            <label class="sr-only" for="card-expiration-date">
                                                Expiration Date
                                            </label>

                                            <input x-model="cardExpiryDate" x-mask="99/99" class="border-gray-200 relative rounded-bl-lg w-full focus:z-10 text-sm p-2.5 placeholder-gray-400" type="text" name="card-expiration-date" id="card-expiration-date" placeholder="MM / YY" />
                                        </div>

                                        <div class="flex-1" x-data="{ cardCVC: '' }">
                                            <label class="sr-only" for="card-cvc">CVC</label>

                                            <input x-model="cardCVC" x-mask="999" class="border-gray-200 relative rounded-br-lg w-full focus:z-10 text-sm p-2.5 placeholder-gray-400" type="text" name="card-cvc" id="card-cvc" placeholder="CVC" />
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="col-span-6">
                                <legend class="block mb-1 text-sm text-gray-600">
                                    Billing Address
                                </legend>

                                <div class="-space-y-px bg-white rounded-lg shadow-sm">
                                    <div>
                                        <label class="sr-only" for="country">Country</label>

                                        <select class="border-gray-200 relative rounded-t-lg w-full focus:z-10 text-sm p-2.5" id="country" name="country" autocomplete="country-name">
                                            <option>Morocco</option>
                                        </select>
                                    </div>
                                    <?php function input1($name, $placeholder)
                                    { ?>
                                        <div>
                                            <label class="sr-only" for="<?= $name ?>">
                                                <?= $name ?>
                                            </label>

                                            <input class="border-gray-200 relative rounded-t-lg w-full focus:z-10 text-sm p-2.5 placeholder-gray-400" type="text" name="<?= $name ?>" id="<?= $name ?>" placeholder="<?= $placeholder ?>" />
                                        </div>
                                    <?php } ?>
                                    <?php input1('address', 'Address') ?>
                                    <?php input1('city', 'City') ?>
                                    <?php input1('zip', 'ZIP/Post Code') ?>
                                </div>
                            </fieldset>

                            <div class="col-span-6">
                                <button class="rounded-lg bg-black text-sm p-2.5 text-white w-full block" type="submit" name="submit" value="pay_now">
                                    Pay Now
                                </button>
                                <br>
                                <button class="rounded-lg bg-slate-600 text-sm p-2.5 text-white w-full block" type="submit" name="submit" value="pay_later">
                                    Pay After Delivery
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>