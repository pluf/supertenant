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
require_once 'Pluf.php';

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Invoices_REST_SelfTest extends TestCase
{

    private static $client = null;

    /**
     *
     * @beforeClass
     */
    public static function installApps()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
        $m = new Pluf_Migration();
        $m->install();

        // Main tenant
        $view = new SuperTenant_Views();
        $request = new Pluf_HTTP_Request('/');
        $request->tenant = new Pluf_Tenant(1);
        $request->REQUEST = array(
            'title' => 'Main tenant',
            'description' => 'Description of the main tenant',
            'domain' => 'localhost',
            'subdomain' => 'localhost',
            'validate' => true
        );
        $view->create($request, array());

        self::$client = new Client();
        self::$client->post('/user/login', array(
            'login' => 'admin',
            'password' => 'admin'
        ));
    }

    /**
     *
     * @afterClass
     */
    public static function uninstallApps()
    {
        $m = new Pluf_Migration();
        $m->unInstall();
    }

    /**
     * Getting invoice list
     *
     * @test
     */
    public function testFindInvoices()
    {
        // Current user is valid
        $response = self::$client->get('/user/accounts/current');
        $this->assertResponseStatusCode($response, 200, 'Fail to login');
        $this->assertResponseNotAnonymousModel($response, 'Current user is anonymous');

        // find
        $response = self::$client->get('/supertenant/invoices');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Getting invoice list
     *
     * Check non-empty list
     *
     * @test
     */
    public function testFindInvoicesNonEmpty()
    {
        // Current user is valid
        $response = self::$client->get('/user/accounts/current');
        $this->assertResponseStatusCode($response, 200, 'Fail to login');
        $this->assertResponseNotAnonymousModel($response, 'Current user is anonymous');

        $i = new Tenant_Invoice();
        $i->title = 'test';
        $i->descscription = 'test';
        $i->amount = 1000;
        $i->due_dtime = gmdate('Y-m-d H:i:s');
        $i->create();

        // find
        $response = self::$client->get('/supertenant/invoices');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
        $this->assertResponseNonEmptyPaginateList($response, 'No object is in list');

        // delete
        $i->delete();
    }

    /**
     * Getting invoice
     *
     * @test
     */
    public function testGetInvoice()
    {
        // Current user is valid
        $response = self::$client->get('/user/accounts/current');
        $this->assertResponseStatusCode($response, 200, 'Fail to login');
        $this->assertResponseNotAnonymousModel($response, 'Current user is anonymous');

        $i = new Tenant_Invoice();
        $i->title = 'test';
        $i->descscription = 'test';
        $i->amount = 1000;
        $i->due_dtime = gmdate('Y-m-d H:i:s');
        $i->create();

        // find
        $response = self::$client->get('/supertenant/invoices/' . $i->id);
        $this->assertResponseNotNull($response);
        $this->assertResponseStatusCode($response, 200);
        $this->assertResponseNotAnonymousModel($response, 'Invoice not foudn');

        // delete
        $i->delete();
    }
}

