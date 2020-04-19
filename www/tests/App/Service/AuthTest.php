<?php

namespace tests\App\Service;

use App\Model\Token;
use App\Service\Auth;
use App\Model\User;
use libs\Request\Request;
use libs\Request\RequestException;

class AuthTest extends \PHPUnit\Framework\TestCase
{
    private $userMock;
    private $tokenMock;
    private $requestMock;

    public function setUp()
    {
        $this->userMock = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $this->tokenMock = $this->getMockBuilder(Token::class)->disableOriginalConstructor()->getMock();
        $this->requestMock = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
    }

    public function testLogin()
    {
        $this->userMock->expects($this->once())
            ->method('getUserByEmail')
            ->with($this->equalTo('temp@test.com'))
            ->will($this->returnValue([
                'id' => 1, 
                'name' => 'Some name', 
                'surname' => 'Some surname', 
                'password' => 'Temp1234',
                'email' => 'temp@test.com',
                'gender' => 'M'
            ]))
        ;
        $auth = new Auth($this->userMock, $this->tokenMock, $this->requestMock);
        $this->expectException(RequestException::class);
        $result = $auth->login('temp@test.com', 'Temp1234');

        $this->assertInstanceOf(RequestException::class, $result);
        $this->assertEquals(["response" => "Authorization token is invalid"], $result);
    }

    public function testLogout()
    {
        $this->tokenMock->expects($this->once())
            ->method('getTokenInfoByUserId')
            ->with($this->equalTo('1'))
            ->will($this->returnValue([
                'id' => 1, 
                'user_id' => '1', 
                'token' => 'somehash', 
                'expire' => '1586955807',
            ]))
        ;

        $auth = new Auth($this->userMock, $this->tokenMock, $this->requestMock);
        $result = $auth->logout('1');

        $this->assertEquals(['status' => 200, 'content' => 'User logout'], $result);
    }
}