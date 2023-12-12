<?php

use PHPUnit\Framework\TestCase;
use Application\Lib\MailParameters;

class MailParametersTest extends TestCase
{
    private $mailParameters;

    protected function setUp(): void
    {
        $this->mailParameters = new MailParameters();
    }

    public function testConstruction()
    {
        $this->assertInstanceOf(MailParameters::class, $this->mailParameters);
    }

    public function testUserName()
    {
        $this->assertIsString($this->mailParameters->getUserName());
    }

    public function testPassword()
    {
        $this->assertIsString($this->mailParameters->getPassword());
    }

    public function testHost()
    {
        $this->assertIsString($this->mailParameters->getHost());
    }

    public function testMailTo()
    {
        $this->assertIsString($this->mailParameters->getMailTo());
    }

    public function testPort()
    {
        $this->assertIsString($this->mailParameters->getPort());
    }

    public function testSecureMode()
    {
        $this->assertIsString($this->mailParameters->getSecureMode());
    }
}