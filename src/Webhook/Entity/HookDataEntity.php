<?php

namespace Farzai\ThaiPost\Webhook\Entity;

use Farzai\ThaiPost\Entity\AbstractEntity;

/**
 * @property-read ItemEntity[] items
 * @property-read string track_datetime // Example: "10/09/2562 10:17+07:00"
 */
class HookDataEntity extends AbstractEntity
{
    /**
     * @return bool
     */
    public function isValid()
    {
        $keys = array_keys($this->data);

        foreach (['status', 'track_datetime'] as $name) {
            if (! in_array($name, $keys)) {
                return false;
            }
        }

        return true;
    }
}