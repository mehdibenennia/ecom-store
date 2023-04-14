<?php
$title = "Login";
require 'head.php';
?>

<body>
    <div class="max-w-screen-xl px-4 py-16 mx-auto sm:px-6 lg:px-8">
        <div class="max-w-lg mx-auto text-center">
            <h1 class="text-2xl font-bold sm:text-3xl">Get started today!</h1>

            <?php if ($error) { ?>
                <div class="alert">
                    <?= $error ?>
                </div>
            <?php } ?>
        </div>

        <form action="" method="POST" class="max-w-md mx-auto mt-8 mb-0 space-y-4">
            <div>
                <label for="username" class="sr-only">Username</label>

                <div class="relative">
                    <input type="text" id="username" name="username" class="w-full p-4 pr-12 text-sm border-gray-200 rounded-lg shadow-sm" placeholder="Enter Username" />
                </div>
            </div>

            <div>
                <label for="password" class="sr-only">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" class="w-full p-4 pr-12 text-sm border-gray-200 rounded-lg shadow-sm" placeholder="Enter password" />
                </div>
            </div>

            <div class="flex items-center">
                <input id="remember" type="checkbox" name="remember" class="w-5 h-5 border-gray-300 rounded" />

                <label for="remember" class="ml-3 text-sm font-medium">
                    Remember me (30 days)
                </label>
            </div>

            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    No account?
                    <a class="underline" href="signup.php">Sign up</a>
                </p>

                <button type="submit" name="submit" class="inline-block px-5 py-3 ml-3 text-sm font-medium text-white bg-blue-500 rounded-lg">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</body>

</html>