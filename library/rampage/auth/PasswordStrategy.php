<?php
/**
 * Copyright (c) 2015 Axel Helmert
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Axel Helmert
 * @copyright Copyright (c) 2015 Axel Helmert
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License
 */

namespace rampage\auth;

use RuntimeException;
use InvalidArgumentException;


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
    public function __construct(crypt\PasswordCryptInterface $crypt = null)
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
