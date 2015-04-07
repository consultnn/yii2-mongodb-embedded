<?php

namespace consultnn\embedded;

use yii\base\Event;

/**
 * Interface StorageInterface
 * @package consultnn\embedded
 */
interface StorageInterface extends \ArrayAccess
{

    /**
     * Triggers an event
     * @param string $name the event name
     * @param Event $event the event parameter. If not set, a default [[Event]] object will be created.
     */
    public function trigger($name, Event $event = null);

    /**
     * Validate storage
     * @return bool
     */
    public function validate();

    /**
     * Return all attributes in storage
     * @return array
     */
    public function getAttributes();
    
    /**
     * Set scenario
     * @return array
     */
    public function setScenario($scenario);

}