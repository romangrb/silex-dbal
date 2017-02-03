<?php
namespace Hash;

use PHPUnit\Framework\TestCase;

class HashingPassword extends TestCase
{
    protected static $passwordHash;

    /**
     * @beforeClass
     */
    public static function init() {
        self::$passwordHash = password_hash('mon mot de passe', PASSWORD_DEFAULT);
    }

    /**
     * @test
     */
    public function shouldValidPassword() {
        $this->assertTrue(password_verify('mon mot de passe', self::$passwordHash));
    }

    /**
     * @test
     */
    public function shouldNotValidPassword() {
        $this->assertFalse(password_verify('un autre mot de passe', self::$passwordHash));
    }
}