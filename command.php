<?php
require_once(__DIR__."/_includes/utils/_init.php");
require_once(__DIR__ . "/_includes/models/user.php");
require_once(__DIR__ . "/_includes/models/cart.php");
require_once(__DIR__ . "/_includes/models/command.php");
$title = 'Commande';
if(isset($_GET['id'])) {
    $command = Command::find_by_id($_GET['id']);
    $cart = $command->getCart();
} else {
    header("Location: ".PROJECT_URL."/");
    exit;
}
require_once(__DIR__ . "/_includes/views/head.php");
?>
<body>
    <?php require_once(__DIR__ . "/_includes/views/navbar.php");?>
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

                                <input readonly class="rounded-lg shadow-sm border-gray-200 w-full text-sm p-2.5" type="text" id="first_name" name="first_name" value="<?= html($command->fname) ?>" />
                            </div>

                            <div class="col-span-3">
                                <label class="block mb-1 text-sm text-gray-600" for="last_name">
                                    Last Name
                                </label>

                                <input readonly class="rounded-lg shadow-sm border-gray-200 w-full text-sm p-2.5" type="text" id="last_name" name="last_name" value="<?= html($command->lname) ?>" />
                            </div>
                            

                            <?php if($command->is_paid) { ?>
                            <fieldset class="col-span-6">
                                <legend class="block mb-1 text-sm text-gray-600">
                                    Card Details
                                </legend>

                                <div class="-space-y-px bg-white rounded-lg shadow-sm">
                                    <div x-data="{ cardNumber: '<?=html($command->card->number)?>' }">
                                        <label class="sr-only" for="card-number">Card Number</label>

                                        <input readonly value="<?=html($command->card->number)?>" x-model="cardNumber" x-mask:dynamic="$input.startsWith('34') || $input.startsWith('37') ? '9999 999999 99999' : '9999 9999 9999 9999'" class="border-gray-200 relative rounded-t-lg w-full focus:z-10 text-sm p-2.5 placeholder-gray-400" type="text" name="card-number" id="card-number" placeholder="Card number" />
                                    </div>

                                    <div class="flex -space-x-px">
                                        <div class="flex-1" x-data="{ cardExpiryDate: '<?=html($command->card->exp_month."/".$command->card->exp_year)?>' }">
                                            <label class="sr-only" for="card-expiration-date">
                                                Expiration Date
                                            </label>

                                            <input readonly value="<?=html($command->card->exp_month."/".$command->card->exp_year)?>" x-model="cardExpiryDate" x-mask="99/99" class="border-gray-200 relative rounded-bl-lg w-full focus:z-10 text-sm p-2.5 placeholder-gray-400" type="text" name="card-expiration-date" id="card-expiration-date" placeholder="MM / YY" />
                                        </div>

                                        <div class="flex-1" x-data="{ cardCVC: '<?=html($command->card->cvc)?>' }">
                                            <label class="sr-only" for="card-cvc">CVC</label>

                                            <input readonly value="<?=html($command->card->cvc)?>" x-model="cardCVC" x-mask="999" class="border-gray-200 relative rounded-br-lg w-full focus:z-10 text-sm p-2.5 placeholder-gray-400" type="text" name="card-cvc" id="card-cvc" placeholder="CVC" />
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <?php } ?>
                            <fieldset class="col-span-6">
                                <legend class="block mb-1 text-sm text-gray-600">
                                    Billing Address
                                </legend>

                                <div class="-space-y-px bg-white rounded-lg shadow-sm">
                                    <?php function input1($name, $placeholder, $value)
                                    { ?>
                                        <div>
                                            <label class="sr-only" for="<?= $name ?>">
                                                <?= $name ?>
                                            </label>

                                            <input value="<?=html($value)?>" class="border-gray-200 relative rounded-t-lg w-full focus:z-10 text-sm p-2.5 placeholder-gray-400" type="text" name="<?= $name ?>" id="<?= $name ?>" placeholder="<?= $placeholder ?>" />
                                        </div>
                                    <?php } ?>
                                    <?php input1('country', 'Country',$command->address->country) ?>
                                    <?php input1('address', 'Address',$command->address->address) ?>
                                    <?php input1('city', 'City',$command->address->city) ?>
                                    <?php input1('zip', 'ZIP/Post Code',$command->address->zip) ?>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>