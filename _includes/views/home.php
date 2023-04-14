<?php
$title = "Home";
require 'head.php';
?>

<body>
    <?php require_once(__DIR__ . "/navbar.php"); ?>
    <?php require_once(__DIR__ . "/card.php"); ?>
    <section>
        <div class="max-w-screen-xl px-4 py-12 mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-4 lg:items-start">
                <div class="lg:sticky lg:top-4">
                    <details open class="overflow-hidden border border-gray-200 rounded">
                        <summary class="flex items-center justify-between px-5 py-3 bg-gray-100 lg:hidden">
                            <span class="text-sm font-medium">
                                Toggle Filters
                            </span>

                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </summary>

                        <form action="" id="filters" class="border-t border-gray-200 lg:border-t-0">
                            <?php if (isset($_GET['sort_by'])) { ?>
                                <input type="hidden" name="sort_by" value="<?= html($_GET['sort_by']) ?>">
                            <?php } ?>
                            <div class="relative">
                                <label class="sr-only" for="search"> Search </label>

                                <input class="w-full py-4 pl-3 pr-16 text-sm border-2 border-gray-200 rounded-lg" id="serach" type="text" name="q" placeholder="Search" />

                                <button class="absolute p-2 text-white -translate-y-1/2 bg-blue-600 rounded-full top-1/2 right-4" type="submit">
                                    &rarr;
                                </button>
                            </div>
                            <fieldset>
                                <legend class="block w-full px-5 py-3 text-xs font-medium bg-gray-50">
                                    Type
                                </legend>

                                <div class="px-5 py-6 space-y-2">
                                    <?php foreach (Product::get_Categories() as $category) { ?>
                                        <div class="relative flex items-center">
                                            <input id="<?= $category['category'] ?>" type="radio" name="category" <?= $s_category == $category['category'] ? 'checked' : '' ?> value="<?= $category['category'] ?>" class="w-5 h-5 border-gray-300 rounded" />

                                            <label for="<?= $category['category'] ?>" class="ml-3 block text-sm leading-5 text-gray-900"><?= $category['category'] ?></label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </fieldset>

                            <div>
                                <fieldset>
                                    <legend class="block w-full px-5 py-3 text-xs font-medium bg-gray-50">
                                        Age
                                    </legend>
                                    <div class="px-5 py-6 space-y-2">
                                        <?php foreach (Product::get_Ages() as $age) { ?>
                                            <div class="flex items-center">
                                                <input id="<?= $age['age'] ?>+" type="radio" name="age" <?= $s_age == $age['age'] ? 'checked' : '' ?> value="<?= $age['age'] ?>" class="w-5 h-5 border-gray-300 rounded" />

                                                <label for="<?= $age['age'] ?>+" class="ml-3 text-sm font-medium">
                                                    <?= $age['age'] ?>+
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="flex justify-between px-5 py-3 m-auto border-t border-gray-200">
                                <a href="<?=PROJECT_URL?>/search.php" class="text-xs flex items-center font-medium text-gray-600 underline rounded">
                                    Reset All
                                </a>

                                <button type="submit" class="px-5 py-3 text-xs font-medium text-white bg-green-600 rounded">
                                    Apply Filters
                                </button>
                            </div>
                        </form>
                    </details>
                </div>

                <div class="lg:col-span-3">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500">
                            <span class="hidden sm:inline">
                                Showing
                            </span>
                            <?= count($products) ?> of <?= $count ?> Products
                        </p>
                        <div class="ml-4">
                            <form action="">
                                <?php if (isset($_GET['category'])) { ?>
                                    <input type="hidden" name="category" value="<?= html($_GET['category']) ?>">
                                <?php } ?>
                                <?php if (isset($_GET['age'])) { ?>
                                    <input type="hidden" name="age" value="<?= html($_GET['age']) ?>">
                                <?php } ?>
                                <label for="SortBy" class="sr-only">
                                    Sort
                                </label>
                                <select id="SortBy" name="sort_by" class="text-sm border-gray-100 rounded" onchange="this.form.submit()">
                                    <option readonly value="">Sort</option>
                                    <option value="title-asc" <?= $sort_by == 1 ? ' selected' : '' ?>>Title, A-Z</option>
                                    <option value="title-desc" <?= $sort_by == 2 ? ' selected' : '' ?>>Title, Z-A</option>
                                    <option value="price-asc" <?= $sort_by == 3 ? ' selected' : '' ?>>Price, Low-High</option>
                                    <option value="price-desc" <?= $sort_by == 4 ? ' selected' : '' ?>>Price, High-Low</option>
                                </select>
                                <button type="submit" class="px-5 py-3 text-xs font-medium text-white bg-green-600 rounded">
                                    Sort
                                </button>
                            </form>
                        </div>
                    </div>

                    <form action="cart.php" method="post">
                        <div class="grid grid-cols-1 gap-px mt-4 bg-gray-200 border border-gray-200 sm:grid-cols-2 lg:grid-cols-3">
                            <?php
                            /**
                             * @var Product[] $products
                             */
                            foreach ($products as $p) { ?>
                                <?= Card($p->getID(), $p->name, $p->price, '') ?>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        window.addEventListener('resize', () => {
            const desktopScreen = window.innerWidth < 768
            document.querySelector('details').open = !desktopScreen
        })
    </script>
</body>

</html>