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
        // Validate subdomain if requester is not owner of tenant
        if (! User_Precondition::isOwner($request)) {
            $this->validateSubdomain($request->REQUEST['subdomain']);
        }
        $tenant = Tenant_Service::createNewTenant($request->REQUEST);
        return $tenant;
    }

    /**
     * Checks if given subdomain is valid.
     *
     * A name for subdomain is valid if its lenght is at least `subdomain_min_length` characters and is not equal with reserved subdomains.
     * The value `subdomain_min_length` is set from config.php.
     * Also, the array of reserved subdomains is set from config.php by a key named 'reserved_subdomains'.
     */
    public function validateSubdomain($subdomain)
    {
        $minLength = Pluf::f('subdomain_min_length', 1);
        if (strlen($subdomain) < $minLength) {
            throw new Pluf_Exception_BadRequest('Invalid subdomain. Subdomain should be at least ' . $minLength . ' character.');
        }
        $reservedSubdomains = Pluf::f('reserved_subdomains', array());
        if (in_array($subdomain, $reservedSubdomains, TRUE)) {
            throw new Pluf_Exception_BadRequest('Subdomain is reserved.');
        }
    }
}


