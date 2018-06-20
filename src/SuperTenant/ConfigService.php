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
 * System level configuration service
 *
 * Configuration is system level key-value which cloud owners are able to
 * edit. All configurations are readable by owners but not editable.
 *
 * @author maso
 *        
 */
class SuperTenant_ConfigService
{

    public static $inMemory = array(
        // example entry
        'key' => array(
            'value' => 'value',
            'derty' => false,
            'tenant' => 0
        )
    );

    /**
     *
     * @param string $key
     * @param object $defValue
     * @return boolean|object|string
     */
    public static function get($key, $defValue)
    {
        $myTenant = Pluf_Tenant::current();
        $memKey = $key;
        if (array_key_exists($memKey, self::$inMemory)) {
            $entary = self::$inMemory[$memKey];
        } else {
            $entary = array(
                'value' => $defValue,
                'derty' => false,
                'tenant' => $myTenant->id
            );
            // TODO: maso, 2017: load value
            $sql = new Pluf_SQL('`key`=%s AND `tenant`=%s', array(
                $key,
                $myTenant->id
            ));
            $config = new SuperTenant_Configuration();
            $config = $config->getOne(array(
                'filter' => $sql->gen()
            ));
            if (isset($config)) {
                $entary['value'] = $config->value;
            } else {
                $entary['derty'] = true;
            }
        }
        self::$inMemory[$memKey] = $entary;
        return $entary['value'];
    }
    
    
    /**
     */
    public static function flush()
    {
        foreach (self::$inMemory as $key => $val) {
            if ($val['derty']) {
                $myTenant = Pluf_Tenant::current();
                // TODO: maso, 2017: load value
                $sql = new Pluf_SQL('`key`=%s AND `tenant`=%s', array(
                    $key,
                    $myTenant->id
                ));
                $config = new SuperTenant_Configuration();
                $config = $config->getOne(array(
                    'filter' => $sql->gen()
                ));
                if (isset($config)) {
                    $config->value = $val['value'];
                    $config->update();
                } else {
                    $config = new SuperTenant_Configuration();
                    $config->value = $val['value'];
                    $config->key = $key;
                    $config->mod = 0;
                    $config->tenant = $myTenant;
                    $config->create();
                }
            }
        }
    }
}
