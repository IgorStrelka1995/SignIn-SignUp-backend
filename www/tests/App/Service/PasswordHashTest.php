<?php

namespace tests\App\Service;

use App\Service\PasswordHash;

class PasswordHashTest extends \PHPUnit\Framework\TestCase
{
    public function testHashPassword()
    {
        $passwordHash = new PasswordHash();
        $password = $passwordHash->hashPassword('Temp1234#');

        $this->assertEquals(gettype($password), 'string');
    }
}