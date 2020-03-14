<?php
$paths = array(
    'urls-v2/tenant.php',
    'urls-v2/configuration.php',
    'urls-v2/invoice.php',
    'urls-v2/ticket.php'
);

$api = array();

foreach ($paths as $path) {
    $myApi = include $path;
    $api = array_merge($api, $myApi);
}

return $api;

