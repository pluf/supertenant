<?php
return array(
    // ************************************************************* Schema
    array(
        'regex' => '#^/tickets/schema$#',
        'model' => 'Pluf_Views',
        'method' => 'getSchema',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'SuperTenant_Ticket'
        )
    ),
    // **************************************************************** Ticket
    array( // Read (list)
        'regex' => '#^/tickets$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Ticket',
            'sortOrder' => array(
                'id',
                'DESC'
            )
        )
    ),
    array( // Read
        'regex' => '#^/tickets/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'SuperTenant_Ticket'
        )
    ),
    array( // Update
        'regex' => '#^/tickets/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Ticket'
        )
    ),
    array( // Delete
        'regex' => '#^/tickets/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteObject',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Ticket'
        )
    ),
    // **************************************************************** Comments on Ticket
    array( // Create
        'regex' => '#^/tickets/(?P<parentId>\d+)/comments$#',
        'model' => 'SuperTenant_Views_Ticket',
        'method' => 'createManyToOne',
        'http-method' => array(
            'PUT',
            'POST'
        ),
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Read (list)
        'regex' => '#^/tickets/(?P<parentId>\d+)/comments$#',
        'model' => 'Pluf_Views',
        'method' => 'findManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'SuperTenant_Ticket',
            'parentKey' => 'ticket_id',
            'model' => 'SuperTenant_Comment',
            'sortOrder' => array(
                'id',
                'DESC'
            )
        )
    ),
    array( // Get
        'regex' => '#^/tickets/(?P<parentId>\d+)/comments/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'SuperTenant_Ticket',
            'parentKey' => 'ticket_id',
            'model' => 'SuperTenant_Comment'
        )
    ),
    array( // Update
        'regex' => '#^/tickets/(?P<parentId>\d+)/comments/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateManyToOne',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'SuperTenant_Ticket',
            'parentKey' => 'ticket_id',
            'model' => 'SuperTenant_Comment'
        )
    ),
    array( // Delete
        'regex' => '#^/tickets/(?P<parentId>\d+)/comments/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteManyToOne',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'SuperTenant_Ticket',
            'parentKey' => 'ticket_id',
            'model' => 'SuperTenant_Comment'
        )
    )
);