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
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SuperTenant_REST_TicketCommentAnonymouseTest extends AbstractBasicTest
{

    private static $client = null;

    private static $ownerClient = null;

    /**
     *
     * @beforeClass
     */
    public static function installApps()
    {
        parent::installApps();
        // Anonymouse client
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
        // logout (to ensure user is anonymouse
        $response = self::$client->post('/api/v2/user/logout');
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to logout');
    }

    /**
     * Getting comments of ticket
     *
     * Call tenant to get list of comments of ticket
     *
     * @test
     * @expectedException Pluf_Exception_Unauthorized
     */
    public function testListEmptyTicketComments()
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

        // Anonymouse access (Expect to exception)
        self::$client->get('/api/v2/super-tenant/tickets/' . $t->id . '/comments');
        
        // delete
        $t->delete();
    }
    
    /**
     * Getting tenant tickets (when list is not empty)
     *
     * Call tenant to get list of tickets
     *
     * @test
     * @expectedException Pluf_Exception_Unauthorized
     */
    public function testListNotEmptyTicketComments()
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
        
        // Anonymouse access (Expect to exception)
        self::$client->get('/api/v2/super-tenant/tickets/' . $t->id . '/comments');
        
        // delete
        $c->delete();
        $t->delete();
    }

    /**
     * Getting comment
     *
     * @test
     * @expectedException Pluf_Exception_Unauthorized
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

        // Anonymouse access (Expect to exception)
        self::$client->get('/api/v2/super-tenant/tickets/' . $t->id . '/comments/' . $c->id);

        // delete
        $c->delete();
        $t->delete();
    }

    /**
     * Creating comment
     *
     * @test
     * @expectedException Pluf_Exception_Unauthorized
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

        // Anonymouse access (Expect to exception)
        self::$client->post('/api/v2/super-tenant/tickets/' . $t->id . '/comments', array(
            'title' => 'test',
            'description' => 'test'
        ));

        $t->delete();
    }

    /**
     * Creating comment
     *
     * @test
     * @expectedException Pluf_Exception_Unauthorized
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

        $c = new Tenant_Comment();
        $c->title = 'test';
        $c->description = 'test';
        $c->author_id = $user;
        $c->ticket_id = $t;
        $c->create();
        
        // Anonymouse access (Expect to exception)
        self::$client->post('/api/v2/super-tenant/tickets/' . $t->id . '/comments/'. $c->id, array(
            'title' => 'test new title',
            'description' => 'test'
        ));

        // delete
        $c->delete();
        $t->delete();
    }

    /**
     * Creating comment
     *
     * @test
     * @expectedException Pluf_Exception_Unauthorized
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

        $c = new Tenant_Comment();
        $c->title = 'test';
        $c->description = 'test';
        $c->author_id = $user;
        $c->ticket_id = $t;
        $c->create();
        
        // delete
        // Anonymouse access (Expect to exception)
        self::$client->delete('/api/v2/super-tenant/tickets/' . $t->id . '/comments/' . $c->id);

        // delete
        $c->delete();
        $t->delete();
    }
}

