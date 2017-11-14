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

    /*
     * Tenant
     */
    array(
        'regex' => '#^/tenant/current$#',
        'model' => 'Tenant_Views',
        'method' => 'current',
        'http-method' => 'GET',
        'precond' => array()
    ),
    array(
        'regex' => '#^/tenant/current$#',
        'model' => 'Tenant_Views',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/tenant/current$#',
        'model' => 'Tenant_Views',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),

    array( // Tenant urls
        'regex' => '#^/tenant/new$#',
        'model' => 'SuperTenant_Views',
        'method' => 'create',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::loginRequired'
        ),
        'params' => array(
            'model' => 'Pluf_Tenant'
        )
    ),
    array(
        'regex' => '#^/tenant/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Pluf_Tenant',
            'listFilters' => array(
                'title',
                'description'
            ),
            'listDisplay' => array(
                'title' => 'title',
                'description' => 'description'
            ),
            'searchFields' => array(
                'title',
                'description'
            ),
            'sortFields' => array(
                'title',
                'description',
                'creation_date',
                'modif_dtime'
            )
        )
    ),
    array(
        'regex' => '#^/tenant/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'model' => 'Pluf_Tenant'
        )
    ),
    array(
        'regex' => '#^/tenant/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteObject',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'model' => 'Pluf_Tenant'
        )
    ),
    array(
        'regex' => '#^/tenant/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'model' => 'Pluf_Tenant'
        )
    ),
    array(
        'regex' => '#^/tenant/(?P<parentId>\d+)/ticket/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Ticket'
        )
    ),
    array(
        'regex' => '#^/tenant/(?P<parentId>\d+)/ticket/new$#',
        'model' => 'Pluf_Views',
        'method' => 'createManyToOne',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Ticket'
        )
    ),
    array(
        'regex' => '#^/tenant/(?P<parentId>\d+)/invoice/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Invoice'
        )
    ),
    array(
        'regex' => '#^/tenant/(?P<parentId>\d+)/invoice/new$#',
        'model' => 'Pluf_Views',
        'method' => 'createManyToOne',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Invoice'
        )
    ),
    
    /*
     * Ticket
     */
    array(
        'regex' => '#^/ticket/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'SuperTenant_Ticket',
            'listFilters' => array(
                'status',
                'type',
                'requester'
            ),
            'listDisplay' => array(),
            'searchFields' => array(
                'subject',
                'description'
            ),
            'sortFields' => array(
                'id',
                'status',
                'type',
                'modif_dtime',
                'creation_dtime'
            ),
            'sortOrder' => array(
                'id',
                'DESC'
            )
        )
    ),
    array(
        'regex' => '#^/ticket/new$#',
        'model' => 'Pluf_Views',
        'method' => 'createObject',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'Tenant_Ticket'
        )
    ),
    array(
        'regex' => '#^/ticket/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'Tenant_Ticket'
        )
    ),
    array(
        'regex' => '#^/ticket/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'Tenant_Ticket'
        )
    ),
    array(
        'regex' => '#^/ticket/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteObject',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'parent' => 'Pluf_Tenant',
            'parentKey' => 'tenant',
            'model' => 'Tenant_Ticket'
        )
    ),
    array(
        'regex' => '#^/ticket/(?P<parentId>\d+)/comment/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Comment',
            'parent' => 'Tenant_Ticket',
            'parentKey' => 'ticket',
            'listFilters' => array(
                'status',
                'type',
                'requester'
            ),
            'listDisplay' => array(),
            'searchFields' => array(
                'subject',
                'description'
            ),
            'sortFields' => array(
                'id',
                'status',
                'type',
                'modif_dtime',
                'creation_dtime'
            ),
            'sortOrder' => array(
                'id',
                'DESC'
            )
        )
    ),
    array(
        'regex' => '#^/ticket/(?P<parentId>\d+)/comment/new$#',
        'model' => 'Tenant_Views_Ticket',
        'method' => 'createManyToOne',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Comment',
            'parent' => 'Tenant_Ticket',
            'parentKey' => 'ticket'
        )
    ),
    array(
        'regex' => '#^/ticket/(?P<parentId>\d+)/comment/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Comment',
            'parent' => 'Tenant_Ticket',
            'parentKey' => 'ticket'
        )
    ),
    array(
        'regex' => '#^/ticket/(?P<parentId>\d+)/comment/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateManyToOne',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Comment',
            'parent' => 'Tenant_Ticket',
            'parentKey' => 'ticket'
        )
    ),
    array(
        'regex' => '#^/ticket/(?P<parentId>\d+)/comment/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteManyToOne',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Comment',
            'parent' => 'Tenant_Ticket',
            'parentKey' => 'ticket'
        )
    ),
    
    
    /*
     * invoices
     */
    array(
        'regex' => '#^/invoice/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Invoice',
            'listFilters' => array(
                'id',
                'status'
            ),
            'listDisplay' => array(),
            'searchFields' => array(
                'title',
                'description'
            ),
            'sortFields' => array(
                'id',
                'status',
                'amount',
                'due_dtiem',
                'modif_dtime',
                'creation_dtime'
            ),
            'sortOrder' => array(
                'id',
                'DESC'
            )
        )
    ),
    array(
        'regex' => '#^/invoice/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Invoice'
        )
    )
);
    
    
    
