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
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\IncompleteTestError;
require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufConfigTemplateTest extends TestCase
{

    /**
     * @beforeClass
     */
    public static function createDataBase()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->install();
    }

    /**
     * @afterClass
     */
    public static function removeDatabses()
    {
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->unInstall();
    }

    /**
     * @test
     */
    public function testSetting1()
    {
        $folders = array(
            __DIR__ . '/../templates'
        );
        $tmpl = new Pluf_Template('tpl-config1.html', $folders);
        $this->assertEquals(SuperTenant_ConfigService::get('config1', 'default value'), $tmpl->render());
    }

    /**
     * @test
     */
    public function testSetting2()
    {
        $folders = array(
            __DIR__ . '/../templates'
        );
        $value = 'Random val:' . rand();
        
        // create config
        $conf = new SuperTenant_Configuration();
        $conf->key = 'config2';
        $conf->value = $value;
        $conf->mod = SuperTenant_Configuration::MOD_PUBLIC;
        $conf->create();
        
        // Load tempalate
        $tmpl = new Pluf_Template('tpl-config2.html', $folders);
        $this->assertEquals($value, $tmpl->render());
    }
}

