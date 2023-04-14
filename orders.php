<?php
$title = 'Orders';
require_once(__DIR__ . "/_includes/utils/_init.php");
require_once(__DIR__ . "/_includes/models/user.php");
require_once(__DIR__ . "/_includes/views/head.php");
$orders = $current_user->getOrders();
?>

<body>
  <?php require_once(__DIR__ . "/_includes/views/navbar.php"); ?>
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm divide-y-2 divide-gray-200">
      <thead>
        <tr>
          <th class="px-4 py-2 font-medium text-left text-gray-900 whitespace-nowrap">
            Name
          </th>
          <th class="px-4 py-2 font-medium text-left text-gray-900 whitespace-nowrap">
            Date
          </th>
          <th class="px-4 py-2 font-medium text-left text-gray-900 whitespace-nowrap">
            Total
          </th>
          <th class="px-4 py-2 font-medium text-left text-gray-900 whitespace-nowrap">
            Status
          </th>
          <th class="px-4 py-2 font-medium text-left text-gray-900 whitespace-nowrap">
            View
          </th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-200">
        <?php foreach ($orders as $order) { ?>
          <tr>
            <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
              <?= html($order->fname . " " . $order->lname) ?>
            </td>
            <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
              <?= html($order->created_at) ?>
            </td>
            <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
              <?= html($order->getCart()->getTotal()) ?>
            </td>
            <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
              <?= html($order->is_paid ? "Payee" : "Non payee") ?>
            </td>
            <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
              <a class="rounded-lg <?=$order->is_paid ?"bg-slate-600":"bg-red-600"?> text-sm p-2.5 text-white" href="<?= "command.php?id=" . $order->getID() ?>" class="text-blue-500 hover:text-blue-700">
                View &gt;
              </a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

</body>

</html>