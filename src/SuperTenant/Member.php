<?php
Pluf::loadFunction('SuperTenant_Shortcuts_GetTenantFeildProperties');
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
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 */
class SuperTenant_Member extends User_Account
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see User_Account::init()
     */
    function init()
    {
        parent::init();
        $this->_a['multitenant'] = false;
        $tenatFeild = SuperTenant_Shortcuts_GetTenantFeildProperties();
        $this->_a['cols'] = array_merge($this->_a['cols'], $tenatFeild);
    }

    /**
     * Extract information of user and returns it.
     *
     * @param string $login
     * @return SuperTenant_Member user information
     */
    public static function getUser($login)
    {
        $model = new SuperTenant_Member();
        $where = 'login = ' . $model->_toDb($login, 'login');
        $users = $model->getList(array(
            'filter' => $where
        ));
        if ($users === false or count($users) !== 1) {
            return false;
        }
        return $users[0];
    }
    
}
