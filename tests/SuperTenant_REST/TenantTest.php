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
use function GuzzleHttp\json_decode;
use Pluf\Test\Client;
use Pluf\Test\TestCase;
require_once 'Pluf.php';

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SuperTenant_REST_TenantTest extends TestCase
{

    private static $client = null;

    private static $tenant = null;

    private static $user = null;

    /**
     *
     * @beforeClass
     */
    public static function installApps()
    {
        $config = include (__DIR__ . '/../conf/config.php');
        $config['subdomain_min_length'] = 5;
        $config['reserved_subdomains'] = array(
            'reserved'
        );
        Pluf::start($config);
        $m = new Pluf_Migration();
        $m->install();

        // Test tenant
        $tenant = new Pluf_Tenant();
        $tenant->domain = 'localhost';
        $tenant->subdomain = 'testtenant';
        $tenant->validate = true;
        if (true !== $tenant->create()) {
            throw new \Pluf\Exception('Faile to create new tenant');
        }

        $m->init($tenant);
        self::$tenant = $tenant;
        
        Pluf_HTTP_Request::setCurrent(new Pluf_HTTP_Request('/'));
        Pluf_Tenant::setCurrent($tenant);

        $user = new User_Account();
        $user->login = 'test';
        $user->is_active = true;
        if (true !== $user->create()) {
            throw new Exception();
        }
        // Credential of user
        $credit = new User_Credential();
        $credit->setFromFormData(array(
            'account_id' => $user->id
        ));
        $credit->setPassword('test');
        if (true !== $credit->create()) {
            throw new Exception();
        }

        $per = User_Role::getFromString('tenant.owner');
        $user->setAssoc($per);
        self::$user = $user;

        self::$client = new Client();
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
     * login
     *
     * @before
     */
    public function loginWithTestUser()
    {
        $response = self::$client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');
    }

    /**
     * Logout
     *
     * @after
     */
    public function logoutUser()
    {
        $response = self::$client->post('/user/logout');
        $this->assertResponseStatusCode($response, 200, 'Fail to login');
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
            $response = self::$client->get('/supertenant/tenants');
            $this->assertResponseNotNull($response, 'Find result is empty');
            $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
            $this->assertResponsePaginateList($response);
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
            $response = self::$client->get('/supertenant/tenants');
            $this->assertResponseNotNull($response, 'Find result is empty');
            $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
            $this->assertResponsePaginateList($response);
            $this->assertResponseNonEmptyPaginateList($response);
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
        $response = self::$client->get('/supertenant/tenants/' . self::$tenant->id);
        $this->assertResponseNotNull($response);
        $this->assertResponseStatusCode($response, 200);
    }

    /**
     * Creating tenant
     *
     * @test
     */
    public function testCreateTenant()
    {
        $testSubdomain = 'test' . rand();
        // find comments
        $response = self::$client->post('/supertenant/tenants', array(
            'title' => 'test',
            'description' => 'test',
            'domain' => $testSubdomain . '.local',
            'subdomain' => $testSubdomain
        ));
        $this->assertResponseStatusCode($response, 200);
        $this->assertResponseNotAnonymousModel($response);

        $tenant = Pluf_Tenant::bySubDomain($testSubdomain);
        $tenant->delete();
    }

    /**
     * Creating tenant
     * Owner could create tenant with too short subdomain
     *
     * @test
     */
    public function testTooShortSubdomainByOwner()
    {
        $testSubdomain = 'abc';
        // new tenant with too short subdomain
        $response = self::$client->post('/supertenant/tenants', array(
            'title' => 'test',
            'description' => 'test',
            'domain' => $testSubdomain . '.local',
            'subdomain' => $testSubdomain
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

    /**
     * Creating tenant
     * Owner could create tenant with reserved subdomain
     *
     * @test
     */
    public function testReservedSubdomainByOwner()
    {
        $testSubdomain = 'reserved';
        // new tenant with reserved subdomain
        $response = self::$client->post('/supertenant/tenants', array(
            'title' => 'test',
            'description' => 'test',
            'domain' => $testSubdomain . '.local',
            'subdomain' => $testSubdomain
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

    /**
     * Getting tenant ticket
     *
     * @test
     */
    public function testGetTenantTickets()
    {
        $response = self::$client->get('/supertenant/tenants/' . self::$tenant->id . '/tickets');
        $this->assertResponseNotNull($response);
        $this->assertResponseStatusCode($response, 200);
        $this->assertResponsePaginateList($response);
    }

    /**
     * Creates tenant ticket
     *
     * @test
     */
    public function testCreateTenantTickets()
    {
        $response = self::$client->post('/supertenant/tenants/' . self::$tenant->id . '/tickets', array(
            'type' => 'bug',
            'subject' => 'test ticket',
            'description' => 'it is not possible to test'
        ));
        $this->assertResponseNotNull($response);
        $this->assertResponseStatusCode($response, 200);
        $this->assertResponseNotAnonymousModel($response);
        $t = json_decode($response->content, true);

        $response = self::$client->get('/supertenant/tenants/' . self::$tenant->id . '/tickets');
        $this->assertResponseNotNull($response);
        $this->assertResponseStatusCode($response, 200);
        $this->assertResponsePaginateList($response);
        $this->assertResponseNonEmptyPaginateList($response);

        $obj = new SuperTenant_Ticket($t['id']);
        $this->assertFalse($obj->isAnonymous());
        $obj->delete();
    }

    /**
     * Getting tenant invoice
     *
     * @test
     */
    public function testGetTenantiInvoice()
    {
        $response = self::$client->get('/supertenant/tenants/' . self::$tenant->id . '/invoices');
        $this->assertResponseNotNull($response);
        $this->assertResponseStatusCode($response, 200);
        $this->assertResponsePaginateList($response);
    }

    /**
     * Creates tenant invoice
     *
     * @test
     */
    public function testCreateTenantInvoce()
    {
        $response = self::$client->post('/supertenant/tenants/' . self::$tenant->id . '/invoices', array(
            'amount' => 1000,
            'title' => 'test ticket',
            'description' => 'it is not possible to test',
            'due_dtime' => gmdate('Y-m-d H:i:s')
        ));
        $this->assertResponseNotNull($response);
        $this->assertResponseStatusCode($response, 200);
        $this->assertResponseNotAnonymousModel($response);
        $t = json_decode($response->content, true);

        $response = self::$client->get('/supertenant/tenants/' . self::$tenant->id . '/invoices');
        $this->assertResponseNotNull($response);
        $this->assertResponseStatusCode($response, 200);
        $this->assertResponsePaginateList($response);
        $this->assertResponseNonEmptyPaginateList($response);

        $obj = new SuperTenant_Invoice($t['id']);
        $this->assertFalse($obj->isAnonymous());
        $obj->delete();
    }
}

