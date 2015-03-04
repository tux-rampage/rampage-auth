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

namespace rampage\auth\plugins;

use rampage\auth\IdentityInterface;
use rampage\auth\UserRepositoryAwareInterface;
use rampage\auth\UserRepositoryAwareTrait;
use rampage\auth\UserRepositoryInterface;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

use Zend\View\Helper\HelperInterface;
use Zend\View\Renderer\RendererInterface;


/**
 * Controller and view helper plugin for retrieving the current user entity
 *
 * This can be registered via DIPluginServiceVactory() if the UserRepositoryInterface service is defined correctly.
 */
class UserPlugin extends AbstractPlugin implements HelperInterface, UserRepositoryAwareInterface
{
    use UserRepositoryAwareTrait;

    /**
     * @var RendererInterface
     */
    protected $view = null;

    /**
     * @var IdentityInterface
     */
    protected $entity = null;

    /**
     * {@inheritdoc}
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * {@inheritdoc}
     */
    public function setView(RendererInterface $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return string|null
     */
    protected function identity()
    {
        if ($this->controller) {
            return $this->controller->identity();
        }

        if ($this->view) {
            return $this->view->identity();
        }

        return null;
    }

    /**
     * @return IdentityInterface the current user or null

     */
    public function __invoke()
    {
        if (!$this->entity) {
            $identity = $this->identity();

            if ($identity) {
                $this->entity = $this->userRepository->findOneByIdentity($identity);
            }
        }

        return $this->entity;
    }
}
