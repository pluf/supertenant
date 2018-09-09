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
 * Main view
 *
 * Manage tenants and offers basics of tenant
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 */
class SuperTenant_Views extends Pluf_Views
{

    /**
     * Create a new tenant
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function create($request, $match)
    {
        // Create a tenant
        $tenant = new Pluf_Tenant();
        if (! isset($request->REQUEST['domain'])) {
            $request->REQUEST['domain'] = $request->REQUEST['subdomain'] . '.' . Pluf::f('general_domain', 'pluf.ir');
        }
        $form = Pluf_Shortcuts_GetFormForModel($tenant, $request->REQUEST);
        $tenant = $form->save();

        // Init the Tenant
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->init($tenant);

        // TODO: update user api to get user by login directly
        $user = new User_Account();
        $user = $user->getUser('admin');

        $credit = new User_Credential();
        $credit->setFromFormData(array(
            'account_id' => $user->id
        ));
        $credit->setPassword('admin');
        $credit->create();

        // Set owner
        $role = User_Role::getFromString('Pluf.owner');
        $user->setAssoc($role);

        // install spacs
        $spas = Pluf::f('spas', array());
        if (sizeof($spas) > 0 && class_exists('Spa_Service')) {
            try {
                Tenant_Service::setSetting('spa.default', $spas[0]);
                foreach ($spas as $spa) {
                    Spa_Service::installFromRepository($spa);
                }
            } catch (Throwable $e) {
                throw new Pluf_Exception("Impossible to install spas from market.", 5000, $e, 500);
            }
        }

        return $tenant;
    }
}