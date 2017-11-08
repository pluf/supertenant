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
        $user = new Pluf_User();
        $user->login = 'test';
        $user->first_name = 'test';
        $user->last_name = 'test';
        $user->email = 'toto@example.com';
        $user->setPassword('test');
        $user->active = true;
        $user->administrator = true;
        if (true !== $user->create()) {
            throw new Exception();
        }
        
        // Test tenant
        $tenant = new Pluf_Tenant();
        $tenant->domain = 'localhost';
        $tenant->subdomain = 'test';
        $tenant->validate = true;
        if (true !== $tenant->create()) {
            throw new Pluf_Exception('Faile to create new tenant');
        }
        
        $client = new Test_Client(array());
        $GLOBALS['_PX_request']->tenant = $tenant;
        
        $per = new Pluf_RowPermission();
        $per->version = 1;
        $per->model_id = $tenant->id;
        $per->model_class = 'Pluf_Tenant';
        $per->owner_id = $user->id;
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
     * Getting tenant tickets
     *
     * Call tenant to get list of tickets
     *
     * @test
     */
    public function testFindTenants()
    {
        $client = new Test_Client(array(
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
        
        // find comments
        $response = $client->get('/api/tenant/find');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Getting not empety comments
     *
     * @test
     */
    public function testFindTenantNotEmpty()
    {
        $client = new Test_Client(array(
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
        // login
        $response = $client->post('/api/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
        
        // Create ticket
        $user = new Pluf_User();
        $user = $user->getUser('test');
        
        $t = new Pluf_Tenant();
        $t->title = 'test';
        $t->description = 'test';
        $t->domain = 'test' . rand();
        $t->subdomain = 'test' . rand() . '.localhost';
        $t->validate = true;
        $t->create();
        
        // find comments
        $response = $client->get('/api/tenant/find');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response);
        Test_Assert::assertResponseNonEmptyPaginateList($response);
        
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
        $client = new Test_Client(array(
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
        
        $tenant = Pluf_Tenant::bySubDomain('test');
        
        // login
        $response = $client->post('/api/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
        
        $response = $client->get('/api/tenant/' . $tenant->id);
        Test_Assert::assertResponseNotNull($response);
        Test_Assert::assertResponseStatusCode($response, 200);
    }

    /**
     * Creating tenant
     *
     * @test
     */
    public function testCreateTinantComment()
    {
        $client = new Test_Client(array(
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
        // login
        $response = $client->post('/api/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
        
        $testSubdomain = 'test' . rand();
        // find comments
        $response = $client->post('/api/tenant/new', array(
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
}

