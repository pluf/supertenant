<?php
$paths = array(
    'urls/tenant.php',
    'urls/config.php',
    'urls/setting.php',
    'urls/invoice.php',
    'urls/ticket.php'
);

$myApi = array();

foreach ($paths as $path) {
    $myApi = include $path;
    $myApi = array_merge($myApi, $myApi);
}

return $myApi;

