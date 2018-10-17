<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('Pluf_Shortcuts_GetListCount');
Pluf::loadFunction('SuperTenant_Shortcuts_CheckTenant');

/**
 * Manage members of tenants (CRUD on members of tenants)
 */
class SuperTenant_Views_Member
{

    /**
     * Returns information of specified user by id.
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public static function get($request, $match)
    {
        $user = Pluf_Shortcuts_GetObjectOr404('SuperTenant_Member', $match['userId']);
        // Checks if user is a member of given tenant
        SuperTenant_Shortcuts_CheckTenant($request, $match, $user);
        return $user;
    }

    /**
     * Updates information of specified user (by id)
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return SuperTenant_Member
     */
    public static function update($request, $match)
    {
        $model = Pluf_Shortcuts_GetObjectOr404('SuperTenant_Member', $match['userId']);
        SuperTenant_Shortcuts_CheckTenant($request, $match, $model);
        $form = Pluf_Shortcuts_GetFormForUpdateModel($model, $request->REQUEST, array());
        $request->user->setMessage(sprintf(__('Member data has been updated.'), (string) $model));
        return $form->save();
    }

    /**
     * Delete specified user (by id)
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public static function delete($request, $match)
    {
        $usr = new SuperTenant_Member($match['userId']);
        $isBlong = SuperTenant_Shortcuts_CheckTenant($request, $match, $usr);
        // $usr->delete();
        if($isBlong){
            $usr->setDeleted(true);
            // TODO: Hadi, 1397-05-26: delete credentials and profile
            return $usr;
        }
    }
}
