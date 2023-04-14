<?php
$title = "Register";
require 'head.php';
?>

<body>
    <div class="max-w-screen-xl px-4 py-16 mx-auto sm:px-6 lg:px-8">
        <div class="max-w-lg mx-auto text-center">
            <h1 class="text-2xl font-bold sm:text-3xl">Get started today!</h1>

            <p class="mt-4 text-gray-500">
                <?php if ($error) { ?>
            <div class="alert">
                <?= $error ?>
            </div>
        <?php } ?>
        </p>
        </div>

        <?php function Input($type, $name, $placeholder, $value = "")
        { ?>
            <div>
                <label for="<?= $name ?>" class="sr-only"><?= $placeholder ?></label>
                <div class="relative">
                    <input type="<?= $type ?>" id="<?= $name ?>" name="<?= $name ?>" class="w-full p-4 pr-12 text-sm border-gray-200 rounded-lg shadow-sm" placeholder="<?= $placeholder ?>" value="<?= $value ?>" />
                </div>
            </div>
        <?php } ?>

        <form action="" method="POST" class="max-w-md mx-auto mt-8 mb-0 space-y-4">
            <?php Input("text", "username", "Username") ?>
            <?php Input("text", "first_name", "First Name") ?>
            <?php Input("text", "last_name", "Last Name") ?>
            <?php Input("email", "email", "Email") ?>
            <?php Input("password", "password", "Password") ?>
            <?php Input("password", "password2", "Repeat Password") ?>

            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Already registred?
                    <a class="underline" href="./">Sign in</a>
                </p>

                <button type="submit" name="submit" class="inline-block px-5 py-3 ml-3 text-sm font-medium text-white bg-blue-500 rounded-lg">
                    Sign up
                </button>
            </div>
        </form>
    </div>
</body>

</html>