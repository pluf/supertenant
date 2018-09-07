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
require_once 'Pluf.php';

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Invoices_REST_OtherTest extends TestCase
{

    private static $client = null;
    
    /**
     *
     * @beforeClass
     */
    public static function installApps()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->install();

        // Main tenant
        $view = new SuperTenant_Views();
        $request = new Pluf_HTTP_Request('/');
        $request->tenant = new Pluf_Tenant(1);
        $request->REQUEST = array(
            'title' => 'Main tenant',
            'description' => 'Description of the main tenant',
            'domain' => 'pluf.ir',
            'subdomain' => 'www',
            'validate' => true
        );
        $view->create($request, array());

        // Customer
        $view = new SuperTenant_Views();
        $request = new Pluf_HTTP_Request('/');
        $request->tenant = new Pluf_Tenant(1);
        $request->REQUEST = array(
            'title' => 'Customer tenant',
            'description' => 'Description of the customer tenant',
            'domain' => 'cu.pluf.ir',
            'subdomain' => 'cu',
            'validate' => true
        );
        $view->create($request, array());

        self::$client = new Test_Client(array(
            array(
                'app' => 'SuperTenant',
                'regex' => '#^/api/v2/super-tenant#',
                'base' => '',
                'sub' => include 'SuperTenant/urls-v2.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/api/v2/user#',
                'base' => '',
                'sub' => include 'User/urls-v2.php'
            )
        ));
        // login
        $response = self::$client->post('/api/v2/user/login', array(
            'login' => 'admin',
            'password' => 'admin'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
    }

    /**
     *
     * @afterClass
     */
    public static function uninstallApps()
    {
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
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
        $response = self::$client->get('/api/v2/user/accounts/current');
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
        Test_Assert::assertResponseNotAnonymousModel($response, 'Current user is anonymous');

        // find
        $tenant = new Pluf_Tenant();
        $list = $tenant->getList();
        foreach ($list as $item) {
            $response = self::$client->get('/api/v2/super-tenant/tenants/' . $item->id . '/invoices');
            Test_Assert::assertResponseNotNull($response, 'Find result is empty');
            Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
            Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
        }
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
        $response = self::$client->get('/api/v2/user/accounts/current');
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
        Test_Assert::assertResponseNotAnonymousModel($response, 'Current user is anonymous');

        $tenant = new Pluf_Tenant();
        $tlist = $tenant->getList();
        foreach ($tlist as $tenant) {
            $i = new SuperTenant_Invoice();
            $i->title = 'test';
            $i->descscription = 'test';
            $i->amount = 1000;
            $i->tenant = $tenant;
            $i->due_dtime = gmdate('Y-m-d H:i:s');
            $i->create();

            // find
            $response = self::$client->get('/api/v2/super-tenant/tenants/' . $tenant->id . '/invoices');
            Test_Assert::assertResponseNotNull($response, 'Find result is empty');
            Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
            Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
            Test_Assert::assertResponseNonEmptyPaginateList($response, 'No object is in list');

            // delete
            $i->delete();
        }
    }

    /**
     * Getting invoice
     *
     * @test
     */
    public function testGetInvoice()
    {
        // Current user is valid
        $response = self::$client->get('/api/v2/user/accounts/current');
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
        Test_Assert::assertResponseNotAnonymousModel($response, 'Current user is anonymous');

        $tenant = new Pluf_Tenant();
        $tlist = $tenant->getList();
        foreach ($tlist as $tenant) {
            $i = new SuperTenant_Invoice();
            $i->title = 'test';
            $i->descscription = 'test';
            $i->amount = 1000;
            $i->tenant = $tenant;
            $i->due_dtime = gmdate('Y-m-d H:i:s');
            $i->create();

            // get
            $response = self::$client->get('/api/v2/super-tenant/tenants/' . $tenant->id . '/invoices');
            Test_Assert::assertResponseNotNull($response);
            Test_Assert::assertResponseStatusCode($response, 200);
            Test_Assert::assertResponseNonEmptyPaginateList($response);

            // get
            $response = self::$client->get('/api/v2/super-tenant/invoices/' . $i->id);
            Test_Assert::assertResponseNotNull($response);
            Test_Assert::assertResponseStatusCode($response, 200);
            Test_Assert::assertResponseNotAnonymousModel($response, 'Invoice not foudn');

            // delete
            $i->delete();
        }
    }
}

