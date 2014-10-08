<?php
/**
 * LICENSE: $license_text$
 *
 * @author    Axel Helmert <ah@luka.de>
 * @copyright Copyright (c) 2013 LUKA netconsult GmbH (www.luka.de)
 * @license   $license$
 */

namespace rampage\auth;

use RuntimeException;
use InvalidArgumentException;
use rampage\auth\crypt\PasswordCryptInterface;

class PasswordStrategy implements PasswordStrategyInterface
{
    /**
     * @var crypt\PasswordCryptInterface
     */
    protected $crypt = null;

    /**
     * @param int $cost The crypt expensiveness (rounds)
     * @param string $salt The salt to use when crypting passwords
     */
    public function __construct(crypt\PasswordCryptInterface $crypt)
    {
        if (!$crypt) {
            $crypt = (version_compare(PHP_VERSION, '5.5', '>='))? new crypt\PhpPasswordCrypt() : new crypt\McryptPasswordCrypt();
        }

        $this->crypt = $crypt;
    }

    /**
     * @param crypt\PasswordCryptInterface $crypt
     * @return self
     */
    public function setPasswordCrypt(crypt\PasswordCryptInterface $crypt)
    {
        $this->crypt = $crypt;
        return $this;
    }

    /**
     * {@inheritdoc}
     * @see \rampage\auth\PasswordStrategyInterface::createPasswordHash()
     */
    public function createPasswordHash($password)
    {
        return $this->crypt->passwordHash($password);
    }

    /**
     * {@inheritdoc}
     * @see \rampage\auth\PasswordStrategyInterface::isRehashRequired()
     */
    public function isRehashRequired($hash)
    {
        return $this->crypt->passwordNeedsRehash($hash);
    }

    /**
     * {@inheritdoc}
     * @see \rampage\auth\PasswordStrategyInterface::verify()
     */
    public function verify($password, $passwordHash)
    {
        return $this->crypt->passwordVerify($password, $passwordHash);
    }
}
