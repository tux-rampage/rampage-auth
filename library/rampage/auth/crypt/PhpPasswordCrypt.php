<?php
/**
 * Copyright (c) 2014 Axel Helmert
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
 * @copyright Copyright (c) 2014 Axel Helmert
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License
 */

namespace rampage\auth\crypt;

class PhpPasswordCrypt extends AbstractPasswordCrypt implements PasswordCryptInterface
{
    /**
     * @var int
     */
    protected $algorithm = null;

    /**
     * @param string $algorithm
     */
    public function __construct($algorithm = null)
    {
        if (($algorithm) && !is_int($algorithm)) {
            throw new InvalidArgumentException(__METHOD__ . '() expects parameter 2 to be long, ' . gettype($algo) . ' given');
        }

        $this->algorithm = ($algorithm !== null)? $algorithm : PASSWORD_DEFAULT;
    }

    /**
     * {@inheritdoc}
     * @see \rampage\auth\crypt\PasswordCryptInterface::passwordHash()
     */
    public function passwordHash($password)
    {
        return password_hash($password, $this->algorithm, $this->options);

    }

    /**
     * {@inheritdoc}
     * @see \rampage\auth\crypt\PasswordCryptInterface::passwordNeedsRehash()
     */
    public function passwordNeedsRehash($hash)
    {
        return password_needs_rehash($hash, $this->algorithm, $this->options);

    }

    /**
     * {@inheritdoc}
     * @see \rampage\auth\crypt\PasswordCryptInterface::passwordVerify()
     */
    public function passwordVerify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
