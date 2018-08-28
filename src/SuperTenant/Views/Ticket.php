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
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * View to manage Tickets of tenants (super tenant tickets)
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 * @author hadi<mohammad.hadi.mansouri@dpq.co.ir>
 */
class SuperTenant_Views_Ticket
{

    /**
     * Create new comment
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @param array $p
     */
    public function createManyToOne($request, $match)
    {
        $parent = Pluf_Shortcuts_GetObjectOr404('SuperTenant_Ticket', $match['parentId']);
        $object = new SuperTenant_Comment();
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST);
        $object = $form->save(false);
        $object->ticket_id = $parent;
        $object->author_id = $request->user;
        // Set tenant
        $tenant = $request->tenant;
        if(isset($request->REQUEST['tenant'])){
            $tenant = Pluf_Shortcuts_GetObjectOr404('Pluf_Tenant', $request->REQUEST['tenant']);
        }else if(isset($match['tenantId'])){
            $tenant = Pluf_Shortcuts_GetObjectOr404('Pluf_Tenant', $match['tenantId']);
        }
        $object->tenant = $tenant;        
        $object->create();
        return $object;
    }
}