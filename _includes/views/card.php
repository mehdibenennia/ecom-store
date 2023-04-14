<?php
function Card($id, $title, $price)
{
?>
    <div class="relative block bg-white">

        <div class="p-6">

            <h5 class="mt-4 text-lg font-bold">
                <a href="product.php?id=<?= $id ?>"><?= $title ?></a>
            </h5>

            <p class="mt-2 text-sm font-medium text-gray-600">
                <?= $price ?> MAD
            </p>

            <button name="add" value="<?= $id ?>" type="submit" class="flex items-center justify-center w-full px-8 py-4 mt-4 bg-yellow-500 rounded-sm">
                <span class="text-sm font-medium">
                    Add to Cart
                </span>

                <svg class="w-5 h-5 ml-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </button>
        </div>
    </div>
<?php
}
