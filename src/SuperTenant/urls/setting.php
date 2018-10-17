<?php
return array(
    // ********************************************************************** Setting
    // TODO: maso, 2017: some attributes are not readable by users
    array(
        'regex' => '#^/setting/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(),
        'params' => array(
            'model' => 'Tenant_Setting',
            'listFilters' => array(
                'id',
                'key',
                'value',
                'description'
            ),
            'listDisplay' => array(
                'key' => 'key',
                'description' => 'description'
            ),
            'searchFields' => array(
                'title',
                'symbol',
                'description'
            ),
            'sortFields' => array(
                'title',
                'symbol',
                'description',
                'creation_date',
                'modif_dtime'
            )
        )
    ),
    array(
        'regex' => '#^/setting/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Tenant_Setting'
        )
    ),
    array( // Result is just in the current tenant
        'regex' => '#^/setting/(?P<key>[^/]+)$#',
        'model' => 'Tenant_Views_Setting',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/setting/(?P<modelId>[^/]+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteObject',
        'http-method' => 'DELETE',
        'params' => array(
            'model' => 'Tenant_Setting'
        ),
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/setting/new$#',
        'model' => 'Pluf_Views',
        'method' => 'createObject',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Tenant_Setting'
        ),
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/setting/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Tenant_Setting'
        ),
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    )
);

