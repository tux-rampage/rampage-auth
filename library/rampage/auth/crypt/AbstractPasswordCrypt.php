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

abstract class AbstractPasswordCrypt implements PasswordCryptInterface
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param int $cost
     * @return self
     */
    public function setCost($cost = null)
    {
        if ($cost === null) {
            unset($this->options['cost']);
        } else {
            $this->options['cost'] = (int)$cost;
        }

        return $this;
    }

    /**
     * @param string $salt
     * @return self
     */
    public function setSalt($salt = null)
    {
        if ($salt === null) {
            unset($this->options['salt']);
        } else {
            $this->options['salt'] = (string)$salt;
        }

        return $this;
    }

}
