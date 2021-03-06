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

class Config_REST_BasicTest extends TestCase
{

    private static $client = null;

    private static $user = null;

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
    public function listSystemConfiguration()
    {
        // login
        $response = self::$client->post('/user/login', array(
            'login' => 'admin',
            'password' => 'admin'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        $response = self::$client->get('/supertenant/configurations');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }
}