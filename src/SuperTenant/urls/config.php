<?php
return array(
    // **************************************************************** Config
    array(
        'regex' => '#^/config/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'SuperTenant_Configuration',
            'listFilters' => array(
                'id',
                'key',
                'value'
            ),
            'listDisplay' => array(),
            'searchFields' => array(
                'key',
                'value',
                'description'
            ),
            'sortFields' => array(
                'id',
                'key',
                'value'
            ),
            'sortOrder' => array(
                'id',
                'DESC'
            )
        )
    )
);

