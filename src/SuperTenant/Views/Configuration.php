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

/**
 * Configurations of Tenant
 *
 * @author hadi
 *        
 */
class SuperTenant_Views_Configuration extends Pluf_Views
{

    /**
     * Getting system configuration
     *
     * Get configuration properties from the system.
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function get($request, $match)
    { 
        $model = $this->internalGet($request, $match);
        if (! isset($model)) {
            throw new Pluf_Exception_DoesNotExist('Configuration not found');
        }
        $tenant = null;
        if(isset($request->REQUEST['tenant'])){
            $tenant = Pluf_Shortcuts_GetObjectOr404('Pluf_Tenant', $request->REQUEST['tenant']);
        }else if(isset($match['tenantId'])){
            $tenant = Pluf_Shortcuts_GetObjectOr404('Pluf_Tenant', $match['tenantId']);
        }
        if(isset($tenant) && $tenant->id !== $model->tenant){
            return new Pluf_HTTP_Error404('Invalid relation');
        }
        return $model;
    }

    /**
     * Returns configuration with given key in the $match array. Returns null if such configuration does not exist.
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return SuperTenant_Configuration|NULL
     */
    private function internalGet($request, $match){
        $model = new SuperTenant_Configuration();
        $model = $model->getOne("`key`='" . $match['key'] . "'");
        return $model;
    }
    
}

