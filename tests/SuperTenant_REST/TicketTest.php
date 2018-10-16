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
require_once 'Pluf.php';

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../Base/');

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SuperTenant_REST_TicketsTest extends AbstractBasicTest
{

    private static $ownerClient = null;
    
    /**
     *
     * @beforeClass
     */
    public static function installApps()
    {
        parent::installApps();
        // Owner client
        self::$ownerClient = new Test_Client(array(
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
        $response = self::$ownerClient->post('/api/v2/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
    }
    
    /**
     * Getting tenant tickets
     *
     * Call tenant to get list of tickets
     *
     * @test
     */
    public function testFindTickets()
    {
        $tenant = Pluf_Tenant::bySubDomain('www');
        
        $response = self::$ownerClient->get('/api/v2/super-tenant/tenants/' . $tenant->id . '/tickets');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Create a ticket
     *
     * @test
     */
    public function testFindTicketsNotEmpty()
    {
        $tenant = Pluf_Tenant::bySubDomain('www');
        
        // create tecket
        $response = self::$ownerClient->post('/api/v2/super-tenant/tenants/' . $tenant->id . '/tickets', array(
            'type' => 'bug',
            'subject' => 'test ticket',
            'description' => 'it is not possible to test'
        ));
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponseNotAnonymousModel($response, 'Ticket is not created');
        $t = json_decode($response->content, true);
        
        // find teckets
        $response = self::$ownerClient->get('/api/v2/super-tenant/tenants/' . $tenant->id . '/tickets');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
        Test_Assert::assertResponseNonEmptyPaginateList($response, 'No ticket is created');
        
        // delete ticket
        $response = self::$ownerClient->delete('/api/v2/super-tenant/tickets/' . $t['id']);
        Test_Assert::assertResponseStatusCode($response, 200, 'Ticket is removed');
    }

    /**
     * Create a ticket
     *
     * @test
     */
    public function testCreateTicket()
    {
        $tenant = Pluf_Tenant::bySubDomain('www');
        
        // create
        $response = self::$ownerClient->post('/api/v2/super-tenant/tenants/' . $tenant->id . '/tickets', array(
            'type' => 'bug',
            'subject' => 'test ticket',
            'description' => 'it is not possible to test'
        ));
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponseNotAnonymousModel($response, 'Ticket is not created');
        $t = json_decode($response->content, true);
        
        // delete
        $response = self::$ownerClient->delete('/api/v2/super-tenant/tickets/' . $t['id']);
        Test_Assert::assertResponseStatusCode($response, 200, 'Ticket is removed');
    }

    /**
     * Get a ticket
     *
     * @test
     */
    public function testGetTicket()
    {
        $tenant = Pluf_Tenant::bySubDomain('www');
        
        // create
        $response = self::$ownerClient->post('/api/v2/super-tenant/tenants/' . $tenant->id . '/tickets', array(
            'type' => 'bug',
            'subject' => 'test ticket',
            'description' => 'it is not possible to test'
        ));
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponseNotAnonymousModel($response, 'Ticket is not created');
        
        // Get
        $t = json_decode($response->content, true);
        $response = self::$ownerClient->get('/api/v2/super-tenant/tickets/' . $t['id']);
        Test_Assert::assertResponseNotAnonymousModel($response, 'Ticket is not find');
        
        // delete
        $response = self::$ownerClient->delete('/api/v2/super-tenant/tickets/' . $t['id']);
        Test_Assert::assertResponseStatusCode($response, 200, 'Ticket is removed');
    }
}

