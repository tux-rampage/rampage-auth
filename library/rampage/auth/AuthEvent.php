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

namespace rampage\auth;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;

use Zend\EventManager\Event;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;


class AuthEvent extends Event implements ServiceLocatorAwareInterface
{
    const EVENT_AUTHENTICATE = 'authenticate';
    const EVENT_POST_AUTHENTICATE = 'authenticate.post';
    const EVENT_CLEAR_AUTH = 'clearAuthentication';

    /**
     * @var AuthenticationService
     */
    protected $authService = null;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * @var Result
     */
    protected $result = null;

    /**
     * @return \Zend\Authentication\Result
     */
    public function getResult()
    {
        if (!$this->result) {
            $this->result = new Result(Result::FAILURE, null);
        }

        return $this->result;
    }

    /**
     * @param \Zend\Authentication\Result $result
     */
    public function setResult(Result $result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @param mixed
     */
    public function getCredential()
    {
        return $this->getParam('credential');
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->getParam('identity');
    }

    /**
     * @return mixed
     */
    public function getRealm()
    {
        return $this->getParam('realm');
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param \Zend\Authentication\AuthenticationService $authService
     */
    public function setAuthService(AuthenticationServiceInterface $authService)
    {
        $this->authService = $authService;
        return $this;
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceManager)
    {
        $this->serviceLocator = $serviceManager;
        return $this;
    }
}
