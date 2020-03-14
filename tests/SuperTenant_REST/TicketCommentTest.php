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

require_once 'Pluf.php';

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../Base/');

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SuperTenant_REST_TicketCommentsTest extends AbstractBasicTest
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
        self::$ownerClient = new Client();
        // login
        $response = self::$ownerClient->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        self::assertResponseStatusCode($response, 200, 'Fail to login');
    }

    /**
     * Getting tenant tickets
     *
     * Call tenant to get list of tickets
     *
     * @test
     */
    public function testFindTicketComments()
    {
        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        $response = self::$ownerClient->get('/supertenant/tickets/' . $t->id . '/comments');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');

        // delete
        $t->delete();
    }

    /**
     * Getting not empty comments
     *
     * @test
     */
    public function testFindTicketCommentsNotEmpty()
    {
        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        $c = new Tenant_Comment();
        $c->title = 'test';
        $c->description = 'test';
        $c->author_id = $user;
        $c->ticket_id = $t;
        $c->create();

        $response = self::$ownerClient->get('/supertenant/tickets/' . $t->id . '/comments');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response);
        $this->assertResponseNonEmptyPaginateList($response);

        // delete
        $c->delete();
        $t->delete();
    }

    /**
     * Getting comment
     *
     * @test
     */
    public function testGetTicketComment()
    {
        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        $c = new Tenant_Comment();
        $c->title = 'test';
        $c->description = 'test';
        $c->author_id = $user;
        $c->ticket_id = $t;
        $c->create();

        $response = self::$ownerClient->get('/supertenant/tickets/' . $t->id . '/comments/' . $c->id);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponseAsModel($response);
        $this->assertResponseNotAnonymousModel($response);

        // delete
        $c->delete();
        $t->delete();
    }

    /**
     * Creating comment
     *
     * @test
     */
    public function testCreateTicketComment()
    {
        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        $response = self::$ownerClient->post('/supertenant/tickets/' . $t->id . '/comments', array(
            'title' => 'test',
            'description' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200);
        $tc = json_decode($response->content, true);

        // find comments
        $response = self::$ownerClient->get('/supertenant/tickets/' . $t->id . '/comments');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response);
        $this->assertResponseNonEmptyPaginateList($response);

        // delete
        $c = new Tenant_Comment($tc['id']);
        $c->delete();
        $t->delete();
    }

    /**
     * Creating comment
     *
     * @test
     */
    public function testUpdateTicketComment()
    {
        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        // Create
        $response = self::$ownerClient->post('/supertenant/tickets/' . $t->id . '/comments', array(
            'title' => 'test',
            'description' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200);
        $tc = json_decode($response->content, true);

        // update
        $response = self::$ownerClient->post('/supertenant/tickets/' . $t->id . '/comments/' . $tc['id'], array(
            'title' => 'test new title',
            'description' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200);

        // find comments
        $response = self::$ownerClient->get('/supertenant/tickets/' . $t->id . '/comments');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response);
        $this->assertResponseNonEmptyPaginateList($response);

        // delete
        $c = new Tenant_Comment($tc['id']);
        $c->delete();
        $t->delete();
    }

    /**
     * Creating comment
     *
     * @test
     */
    public function testDeleteTicketComment()
    {
        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        // create
        $response = self::$ownerClient->post('/supertenant/tickets/' . $t->id . '/comments', array(
            'title' => 'test',
            'description' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200);

        $tc = json_decode($response->content, true);
        // delete
        $response = self::$ownerClient->delete('/supertenant/tickets/' . $t->id . '/comments/' . $tc['id']);
        $this->assertResponseStatusCode($response, 200);

        //
        $response = self::$ownerClient->get('/supertenant/tickets/' . $t->id . '/comments');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response);
        $this->assertResponseEmptyPaginateList($response);

        // delete
        $t->delete();
    }
}

