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

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class IdentityPluginFactory implements FactoryInterface
{
    const AUTH_SERVICE = 'Zend\Authentication\AuthenticationService';

    /**
     * @var string
     */
    protected $realm = null;

    /**
     * @var string
     */
    protected $class = 'Zend\Mvc\Controller\Plugin\Identity';

    /**
     * @param string $realm
     */
    protected function __construct($realm)
    {
        $this->realm = $realm;
    }

    /**
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $plugin \Zend\Mvc\Controller\Plugin\Identity */
        $class = $this->class;
        $plugin = new $class();
        $serviceManager = $serviceLocator->getServiceLocator();
        $authService = self::AUTH_SERVICE;

        if (is_string($this->realm)) {
            $authService .= '.' . $this->realm;
        }

        if ($serviceManager->has($authService)) {
            $plugin->setAuthenticationService($serviceManager->get($authService));
        }

        return $plugin;
    }
}
