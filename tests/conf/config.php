<?php
// $cfg = include 'mysql.config.php';
$cfg = include 'sqlite.config.php';

$cfg['test'] = false;
$cfg['timezone'] = 'Europe/Berlin';

// Set the debug variable to true to force the recompilation of all
// the templates each time during development
$cfg['debug'] = true;
$cfg['installed_apps'] = array(
    'Pluf',
    'User',
    'Monitor',
    'Tenant',
    'SuperTenant'
);

/*
 * Middlewares
 */
$cfg['middleware_classes'] = array(
    '\Pluf\Middleware\Tenant',
    '\Pluf\Middleware\Session',
    'User_Middleware_Session'
);

$cfg['secret_key'] = '5a8d7e0f2aad8bdab8f6eef725412850';

// Temporary folder where the script is writing the compiled templates,
// cached data and other temporary resources.
// It must be writeable by your webserver instance.
// It is mandatory if you are using the template system.
$cfg['tmp_folder'] = '/tmp';
$cfg['upload_path'] = '/tmp';

// The folder in which the templates of the application are located.
$cfg['templates_folder'] = array(
    __DIR__ . '/../templates'
);

/*
 * Template tags
 */
$cfg['template_tags'] = array(
    'config' => 'Pluf_Template_Tag_Mytag',
    'setting' => 'Tenant_Template_Tag_Setting',
    'config' => 'SuperTenant_Template_Tag'
);

// Default mimetype of the document your application is sending.
// It can be overwritten for a given response if needed.
$cfg['mimetype'] = 'text/html';


/***********************************************************************
 * Tenant
 **********************************************************************/
$cfg['multitenant'] = true;
$cfg['tenant_default'] = 'main';
$cfg['tenant_notfound_url'] = 'https://pluf.ir';
    
    
/***********************************************************************
 * Supper Tenant
 **********************************************************************/



return $cfg;

