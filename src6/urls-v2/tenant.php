<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
return array(
    // ************************************************************* Schema
    array(
        'regex' => '#^/tenants/schema$#',
        'model' => 'Pluf_Views',
        'method' => 'getSchema',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Pluf_Tenant'
        )
    ),
    // **************************************************************** Tenant
    array( // Create
        'regex' => '#^/tenants$#',
        'model' => 'SuperTenant_Views',
        'method' => 'create',
        'http-method' => array(
            'POST',
            'PUT'
        )
    ),
    array( // Read (list)
        'regex' => '#^/tenants$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Pluf_Tenant'
        )
    ),
    array( // Read
        'regex' => '#^/tenants/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Pluf_Tenant'
        )
    ),
    array( // Update
        'regex' => '#^/tenants/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Pluf_Tenant'
        )
    ),
    array( // Delete
        'regex' => '#^/tenants/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteObject',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Pluf_Tenant'
        )
    ),
    // **************************************************************** Invoices of a Tenant
    array( // Create
        'regex' => '#^/tenants/(?P<parentId>\d+)/invoices$#',
        'model' => 'Pluf_Views',
        'method' => 'createManyToOne',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Invoice'
        )
    ),
    array( // Read (list)
        'regex' => '#^/tenants/(?P<parentId>\d+)/invoices$#',
        'model' => 'Pluf_Views',
        'method' => 'findManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Invoice'
        )
    ),
    array( // Read
        'regex' => '#^/tenants/(?P<parentId>\d+)/invoices/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Invoice'
        )
    ),
    array( // Update
        'regex' => '#^/tenants/(?P<parentId>\d+)/invoices/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateManyToOne',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Invoice'
        )
    ),
    array( // Delete
        'regex' => '#^/tenants/(?P<parentId>\d+)/invoices/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteManyToOne',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Invoice'
        )
    ),
    // **************************************************************** Tickets of a Tenant
    array( // Create
        'regex' => '#^/tenants/(?P<parentId>\d+)/tickets$#',
        'model' => 'Pluf_Views',
        'method' => 'createManyToOne',
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
    array( // Read (list)
        'regex' => '#^/tenants/(?P<parentId>\d+)/tickets$#',
        'model' => 'Pluf_Views',
        'method' => 'findManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Ticket'
        )
    ),
    array( // Read
        'regex' => '#^/tenants/(?P<parentId>\d+)/tickets/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Ticket'
        )
    ),
    // array( // Update
    // 'regex' => '#^/tenants/(?P<parentId>\d+)/tickets/(?P<modelId>\d+)$#',
    // 'model' => 'Pluf_Views',
    // 'method' => 'updateManyToOne',
    // 'http-method' => 'POST',
    // 'precond' => array(
    // 'User_Precondition::ownerRequired'
    // ),
    // 'params' => array(
    // 'parent' => 'Pluf_Tenant',
    // 'parentKey' => 'tenant',
    // 'model' => 'SuperTenant_Ticket'
    // )
    // ),
    array( // Delete
        'regex' => '#^/tenants/(?P<parentId>\d+)/tickets/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteManyToOne',
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
    // **************************************************************** Configs of a Tenant
    array( // Read (list)
        'regex' => '#^/tenants/(?P<parentId>\d+)/configurations$#',
        'model' => 'Pluf_Views',
        'method' => 'findManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Configuration'
        )
    ),
    array( // Read
        'regex' => '#^/tenants/(?P<parentId>\d+)/configurations/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Configuration'
        )
    ),
    array( // Read (by key)
        'regex' => '#^/tenants/(?P<tenantId>\d+)/configurations/(?P<key>[^/]+)$#',
        'model' => 'SuperTenant_Views_Configuration',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Create
        'regex' => '#^/tenants/(?P<parentId>\d+)/configurations$#',
        'model' => 'Pluf_Views',
        'method' => 'createManyToOne',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Configuration'
        )
    ),
    array( // Update
        'regex' => '#^/tenants/(?P<parentId>\d+)/configurations/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateManyToOne',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Configuration'
        )
    ),
    array( // Delete
        'regex' => '#^/tenants/(?P<parentId>\d+)/configurations/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteManyToOne',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Configuration'
        )
    ),

    // ************************************************** Accounts (Members) of a Tenant

    // array( // Create
    // 'regex' => '#^/tenants/(?P<parentId>\d+)/members$#',
    // 'model' => 'User_Views_Account',
    // 'method' => 'create',
    // 'http-method' => array(
    // 'PUT',
    // 'POST'
    // )
    // ),
    array( // Read (list)
        'regex' => '#^/tenants/(?P<parentId>\d+)/members$#',
        'model' => 'Pluf_Views',
        'method' => 'findManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Member',
            'sql' => 'is_deleted=false'
        )
    ),
    array( // Read
        'regex' => '#^/tenants/(?P<parentId>\d+)/members/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Member',
            'sql' => 'is_deleted=false'
        )
    ),
    // array( // Update
    // 'regex' => '#^/tenants/(?P<parentId>\d+)/accounts/(?P<userId>\d+)$#',
    // 'model' => 'User_Views_Account',
    // 'method' => 'update',
    // 'precond' => array(
    // 'User_Precondition::ownerRequired'
    // ),
    // 'http-method' => 'POST'
    // ),
    array( // Delete
        'regex' => '#^/tenants/(?P<tenantId>\d+)/members/(?P<userId>\d+)$#',
        'model' => 'SuperTenant_Views_Member',
        'method' => 'delete',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'http-method' => 'DELETE'
    ),

    // ************************************************************ SPA
    array( // Read (list)
        'regex' => '#^/tenants/(?P<parentId>\d+)/spas$#',
        'model' => 'Pluf_Views',
        'method' => 'findManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_SPA'
        )
    ),
    array( // Read
        'regex' => '#^/tenants/(?P<parentId>\d+)/spas/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_SPA'
        )
    )
);



    
    
    
