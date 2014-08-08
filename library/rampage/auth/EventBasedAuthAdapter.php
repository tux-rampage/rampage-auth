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

use Zend\EventManager\EventManagerAwareInterface;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\EventManager\EventManagerInterface;
use Zend\Authentication\Result;
use Zend\EventManager\EventManager;


class EventBasedAuthAdapter extends AbstractAdapter implements EventManagerAwareInterface
{
    /**
     * @var AuthEvent
     */
    protected $event = null;

    /**
     * @var EventManagerInterface
     */
    private $eventManager = null;

    /**
     * @param AuthEvent $event
     */
    public function __construct(AuthEvent $event = null)
    {
        $this->setEvent($event? : new AuthEvent());
    }

    /**
     * @param AuthEvent $event
     * @return self
     */
    public function setEvent(AuthEvent $event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return AuthEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @see \Zend\Authentication\Adapter\AdapterInterface::authenticate()
     */
    public function authenticate()
    {
        $this->getEvent()
            ->setTarget($this)
            ->setParam('identity', $this->getIdentity())
            ->setParam('credential', $this->getCredential());

        $stopTriggerCallback = function($result) {
            if (($result instanceof Result) && $result->isValid()) {
                return true;
            }

            return (bool)$result;
        };

        $results = $this->getEventManager()->triggerUntil(AuthEvent::EVENT_AUTHENTICATE, $this->event, $stopTriggerCallback);
        $result = $results->last();

        if (!$result) {
            $result = new Result(Result::FAILURE, null, 'Authentication failed');
        } else if (!$result instanceof Result) {
            $result = new Result(Result::SUCCESS, $result);
        }

        $this->event->setResult($result);
        $this->getEventManager()->trigger(AuthEvent::EVENT_POST_AUTHENTICATE, $this->event);

        return $result;
    }

    /**
     * @see \Zend\EventManager\EventManagerAwareInterface::setEventManager()
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * @see \Zend\EventManager\EventsCapableInterface::getEventManager()
     */
    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }
}
