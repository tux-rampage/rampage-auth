<?php
/**
 * @author    Axel Helmert <ah@luka.de>
 * @copyright Copyright (c) 2014 LUKA netconsult GmbH (www.luka.de)
 */

namespace rampagetest\auth\crypt;

use rampage\auth\crypt\McryptPasswordCrypt;
use PHPUnit_Framework_TestCase as TestCase;


class McryptPasswordCryptTest extends TestCase
{
    /**
     * @var McryptPasswordCrypt
     */
    private $passwordCrypt = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->passwordCrypt = new McryptPasswordCrypt();
        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->passwordCrypt = null;
    }

    /**
     * @return bool
     */
    protected function assertPasswordApi()
    {
        if (version_compare(PHP_VERSION, '5.5', '<')) {
            $this->markTestSkipped(sprintf('This test requires php >= 5.5, current version is %s', PHP_VERSION));
        }
    }

    /**
     * Provide some password strings
     *
     * @return string[]
     */
    public function passwordsDataProvider()
    {
        $params = array();
        $count = 5;

        for ($i = 0; $i < $count; $i++) {
            $params[] = array(uniqid());
        }

        return $params;
    }

    /**
     * @dataProvider passwordsDataProvider
     * @covers McryptPasswordCrypt::passwordHash
     */
    public function testCreateHashIsNativeCompatible($password)
    {
        $this->assertPasswordApi();

        $hash = $this->passwordCrypt->passwordHash($password);
        $this->assertTrue(password_verify($password, $hash));
    }

    /**
     * @dataProvider passwordsDataProvider
     * @covers McryptPasswordCrypt::passwordVerify
     */
    public function testVerifyPasswordIsNativeCompatible($password)
    {
        $this->assertPasswordApi();

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->assertTrue($this->passwordCrypt->passwordVerify($password, $hash));
    }
}
