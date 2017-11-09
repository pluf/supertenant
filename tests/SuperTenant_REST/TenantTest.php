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
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Tenant_REST_TenantTest extends TestCase
{

    private static $client = null;

    private static $tenant = null;

    private static $user = null;

    /**
     * @beforeClass
     */
    public static function installApps()
    {
        Pluf::start(dirname(__FILE__) . '/../conf/config.rest.php');
        $m = new Pluf_Migration(array(
            'Pluf',
            'Tenant',
            'SuperTenant'
        ));
        $m->install();
        // Test user
        self::$user = new Pluf_User();
        self::$user->login = 'test';
        self::$user->first_name = 'test';
        self::$user->last_name = 'test';
        self::$user->email = 'toto@example.com';
        self::$user->setPassword('test');
        self::$user->active = true;
        self::$user->administrator = true;
        if (true !== self::$user->create()) {
            throw new Exception();
        }
        
        // Test tenant
        self::$tenant = new Pluf_Tenant();
        self::$tenant->domain = 'localhost';
        self::$tenant->subdomain = 'test';
        self::$tenant->validate = true;
        if (true !== self::$tenant->create()) {
            throw new Pluf_Exception('Faile to create new tenant');
        }
        
        self::$client = new Test_Client(array(
            array(
                'app' => 'SuperTenant',
                'regex' => '#^/api/tenant#',
                'base' => '',
                'sub' => include 'SuperTenant/urls.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/api/user#',
                'base' => '',
                'sub' => include 'User/urls.php'
            )
        ));
        $GLOBALS['_PX_request']->tenant = self::$tenant;
        
        $per = new Pluf_RowPermission();
        $per->version = 1;
        $per->model_id = self::$tenant->id;
        $per->model_class = 'Pluf_Tenant';
        $per->owner_id = self::$user->id;
        $per->owner_class = 'Pluf_User';
        $per->create();
    }

    /**
     * @afterClass
     */
    public static function uninstallApps()
    {
        $m = new Pluf_Migration(array(
            'Pluf',
            'Tenant',
            'SuperTenant'
        ));
        $m->unInstall();
    }

    /**
     * login
     *
     * @before
     */
    public function loginWithTestUser()
    {
        $response = self::$client->post('/api/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
    }

    /**
     * Logout
     *
     * @after
     */
    public function logoutUser()
    {
        $response = self::$client->post('/api/user/logout');
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
    }

    /**
     * Getting tenant tickets
     *
     * Call tenant to get list of tickets
     *
     * @test
     */
    public function testFindTenants()
    {
        // find comments
        $flag = true;
        while ($flag) {
            $response = self::$client->get('/api/tenant/tenant/find');
            Test_Assert::assertResponseNotNull($response, 'Find result is empty');
            Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
            Test_Assert::assertResponsePaginateList($response);
            $this->logoutUser();
            $flag = false;
        }
    }

    /**
     * Getting not empety comments
     *
     * @test
     */
    public function testFindTenantNotEmpty()
    {
        // Create ticket
        $t = new Pluf_Tenant();
        $t->title = 'test';
        $t->description = 'test';
        $t->domain = 'test' . rand();
        $t->subdomain = 'test' . rand() . '.localhost';
        $t->validate = true;
        $t->create();
        
        // find comments
        $flag = true;
        while ($flag) {
            $response = self::$client->get('/api/tenant/tenant/find');
            Test_Assert::assertResponseNotNull($response, 'Find result is empty');
            Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
            Test_Assert::assertResponsePaginateList($response);
            Test_Assert::assertResponseNonEmptyPaginateList($response);
            $this->logoutUser();
            $flag = false;
        }
        
        // delete
        $t->delete();
    }

    /**
     * Getting tenant info
     *
     * Call tenant to get current tenant information.
     *
     * @test
     */
    public function testGetTenant()
    {
        $response = self::$client->get('/api/tenant/tenant/' . self::$tenant->id);
        Test_Assert::assertResponseNotNull($response);
        Test_Assert::assertResponseStatusCode($response, 200);
    }

    /**
     * Creating tenant
     *
     * @test
     */
    public function testCreateTinant()
    {
        $testSubdomain = 'test' . rand();
        // find comments
        $response = self::$client->post('/api/tenant/tenant/new', array(
            'title' => 'test',
            'description' => 'test',
            'domain' => $testSubdomain . '.local',
            'subdomain' => $testSubdomain
        ));
        Test_Assert::assertResponseStatusCode($response, 200);
        Test_Assert::assertResponseNotAnonymousModel($response);
        
        $tenant = Pluf_Tenant::bySubDomain($testSubdomain);
        $tenant->delete();
    }

    /**
     * Getting tenant ticket
     *
     * @test
     */
    public function testGetTenantTickets()
    {
        $response = self::$client->get('/api/tenant/tenant/' . self::$tenant->id . '/ticket/find');
        Test_Assert::assertResponseNotNull($response);
        Test_Assert::assertResponseStatusCode($response, 200);
        Test_Assert::assertResponsePaginateList($response);
    }

    /**
     * Creates tenant ticket
     *
     * @test
     */
    public function testCreateTenantTickets()
    {
        $response = self::$client->post('/api/tenant/tenant/' . self::$tenant->id . '/ticket/new', array(
            'type' => 'bug',
            'subject' => 'test ticket',
            'description' => 'it is not possible to test'
        ));
        Test_Assert::assertResponseNotNull($response);
        Test_Assert::assertResponseStatusCode($response, 200);
        Test_Assert::assertResponseNotAnonymousModel($response);
        $t = json_decode($response->content, true);
        
        $response = self::$client->get('/api/tenant/tenant/' . self::$tenant->id . '/ticket/find');
        Test_Assert::assertResponseNotNull($response);
        Test_Assert::assertResponseStatusCode($response, 200);
        Test_Assert::assertResponsePaginateList($response);
        Test_Assert::assertResponseNonEmptyPaginateList($response);
        
        $obj = new SuperTenant_Ticket($t['id']);
        Test_Assert::assertFalse($obj->isAnonymous());
        $obj->delete();
    }

    /**
     * Getting tenant invoice
     *
     * @test
     */
    public function testGetTenantiInvoice()
    {
        $response = self::$client->get('/api/tenant/tenant/' . self::$tenant->id . '/invoice/find');
        Test_Assert::assertResponseNotNull($response);
        Test_Assert::assertResponseStatusCode($response, 200);
        Test_Assert::assertResponsePaginateList($response);
    }
    
    
    /**
     * Creates tenant invoice
     *
     * @test
     */
    public function testCreateTenantInvoce()
    {
        $response = self::$client->post('/api/tenant/tenant/' . self::$tenant->id . '/invoice/new', array(
            'amount' => 1000,
            'title' => 'test ticket',
            'description' => 'it is not possible to test',
            'due_dtiem' => gmdate('Y-m-d H:i:s')
        ));
        Test_Assert::assertResponseNotNull($response);
        Test_Assert::assertResponseStatusCode($response, 200);
        Test_Assert::assertResponseNotAnonymousModel($response);
        $t = json_decode($response->content, true);
        
        $response = self::$client->get('/api/tenant/tenant/' . self::$tenant->id . '/invoice/find');
        Test_Assert::assertResponseNotNull($response);
        Test_Assert::assertResponseStatusCode($response, 200);
        Test_Assert::assertResponsePaginateList($response);
        Test_Assert::assertResponseNonEmptyPaginateList($response);
        
        $obj = new SuperTenant_Invoice($t['id']);
        Test_Assert::assertFalse($obj->isAnonymous());
        $obj->delete();
    }
}

