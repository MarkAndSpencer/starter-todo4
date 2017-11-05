<?php

/**
* Model for Tasks on the todo list
*/
class Task extends CI_Model
{
    /**
    * ctor
    */
    public function __construct()
    {
    }

    //provide this for now
    //creates a new task
    public function create()
    {
        return new Task;
    }

    // If this class has a setProp method, use it, else modify the property directly
    public function __set($key, $value) {
        // if a set* method exists for this key,
        // use that method to insert this value.
        // For instance, setName(...) will be invoked by $object->name = ...
        // and setLastName(...) for $object->last_name =
        $method = 'set' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));
        if (method_exists($this, $method))
        {
                $this->$method($value);
                return $this;
        }

        //otherwise validate the property
        if (!$this->validate($key, $value)) {
            throw new Exception('Property does not validate: ' . $key . ' => ' . $value);
        }
        $this->$key = $value;
        return $this;
    }

    private function validate($key, $value)
    {
        $rules = $this->rules();
        return call_user_func($rules[$key], $value);
    }

    // provide entity validation rules
    public function rules()
    {
        $config = array(
            'task' => function($value) { return is_string($value) && strlen($value) < 64; },
            'priority' => function($value) { return is_int($value) && $value < 4; },
            'size' => function($value) { return is_int($value) && $value < 4; },
            'group' => function($value) { return is_int($value) && $value < 4; },
        );
        return $config;
    }
}

