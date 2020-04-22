<?php namespace Todaymade\Daux;

use ArrayObject;

class BaseConfig extends ArrayObject
{
    /**
     * Merge an array into the object.
     *
     * @param array $newValues
     * @param bool $override
     */
    public function merge($newValues, $override = true): void
    {
        foreach ($newValues as $key => $value) {
            // If the key doesn't exist yet,
            // we can simply set it.
            if (!array_key_exists($key, (array) $this)) {
                $this[$key] = $value;

                continue;
            }

            // We already know this value exists
            // so if we're in conservative mode
            // we can skip this key
            if ($override === false) {
                continue;
            }

            // Merge the values only if
            // both values are arrays
            if (is_array($this[$key]) && is_array($value)) {
                $this[$key] = array_replace_recursive($this[$key], $value);
            } else {
                $this[$key] = $value;
            }
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasValue(string $key)
    {
        return array_key_exists($key, (array) $this);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getValue(string $key)
    {
        return $this[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setValue(string $key, $value)
    {
        $this[$key] = $value;
    }
}
