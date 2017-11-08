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
    array( // Tenant urls
        'regex' => '#^/new$#',
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
        'regex' => '#^/find$#',
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
        'regex' => '#^/(?P<modelId>\d+)$#',
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
        'regex' => '#^/(?P<modelId>\d+)$#',
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
        'regex' => '#^/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::staffRequired'
        ),
        'params' => array(
            'model' => 'Pluf_Tenant'
        )
    )
    // TODO: Payment
    // TODO: Ticket
);
    
    
    