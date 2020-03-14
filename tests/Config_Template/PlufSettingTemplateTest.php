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
use Pluf\Test\Client;
use Pluf\Test\TestCase;

class PlufConfigTemplateTest extends TestCase
{

    private static $client = null;

    /**
     *
     * @beforeClass
     */
    public static function createDataBase()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
        $m = new Pluf_Migration();
        $m->install();

        $view = new SuperTenant_Views();
        $request = new Pluf_HTTP_Request('/');
        $request->tenant = new Pluf_Tenant(1);
        $request->REQUEST = array(
            'title' => 'Main tenant',
            'description' => 'Description of the main tenant',
            'domain' => 'localhost',
            'subdomain' => 'www',
            'validate' => true
        );
        $view->create($request, array());

        self::$client = new Client();
    }

    /**
     *
     * @afterClass
     */
    public static function removeDatabses()
    {
        $m = new Pluf_Migration();
        $m->unInstall();
    }

    /**
     *
     * @test
     */
    public function testSetting1()
    {
        $request = new Pluf_HTTP_Request('/');
        $request->tenant = new Pluf_Tenant(1);
        $GLOBALS['_PX_request'] = $request;
        $folders = array(
            __DIR__ . '/../templates'
        );
        $tmpl = new Pluf_Template('tpl-config1.html', $folders);
        $this->assertEquals(SuperTenant_ConfigService::get('config1', 'default value'), $tmpl->render());
    }

    /**
     *
     * @test
     */
    public function testSetting2()
    {
        $request = new Pluf_HTTP_Request('/');
        $request->tenant = new Pluf_Tenant(1);
        $GLOBALS['_PX_request'] = $request;
        $tenant = Pluf_Tenant::current();
        $this->assertFalse($tenant->isAnonymous());

        $folders = array(
            __DIR__ . '/../templates'
        );
        $value = 'Random val:' . rand();
        $key = 'config2';

        // create config
        $conf = new SuperTenant_Configuration();
        $conf->key = $key;
        $conf->value = $value;
        $conf->tenant = $tenant;
        $this->assertTrue($conf->create());

        $this->assertEquals($value, SuperTenant_ConfigService::get($key, null));

        // Load tempalate
        $tmpl = new Pluf_Template('tpl-config2.html', $folders);
        $this->assertEquals($value, $tmpl->render());
    }
}

