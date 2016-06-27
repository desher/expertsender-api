<?php

namespace desher\expertsender;

class ExpertSenderApi
{
    protected $connection;
    protected $sections = [];

    public function __construct($apiKey, $apiUrl = null)
    {
        $this->connection = new ExpertSenderApiConnection($apiKey, $apiUrl);
    }

    /**
     * @return \desher\expertsender\methods\Subscribers
     */
    public function getSubscribers()
    {
        return $this->getApiSection('subscribers');
    }

    /**
     * @return \desher\expertsender\methods\Tables
     */
    public function getTables()
    {
        return $this->getApiSection('tables');
    }

    /**
     * @return \desher\expertsender\methods\Lists
     */
    public function getLists()
    {
        return $this->getApiSection('lists');
    }

    /**
     * @param $name
     * @return mixed instance of specified section
     */
    protected function getApiSection($name)
    {
        $className = ucfirst($name);
        if (!isset($this->sections[$name])) {
            $classFullName = 'desher\\expertsender\\methods\\' . $className;
            return $this->sections[$name] = new $classFullName($this->connection);
        }
        return $this->sections[$name];
    }
}