<?php

namespace desher\expertsender\mappers;

class Property
{
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_DOUBLE = 'double';
    const TYPE_DATE = 'date';
    const TYPE_DATE_TIME = 'dateTime';

    public $id;
    public $type;
    public $value;

    public function __construct($data)
    {
        if (isset($data['Id'])) {
            $this->setId($data['Id']);
        }
        if (isset($data['Type'])) {
            $this->setType($data['Type']);
        }
        if (isset($data['Value'])) {
            $this->setValue($data['Value']);
        }
        if (isset($data['IntValue'])) {
            $this->setIntValue($data['IntValue']);
        }
        if (isset($data['StringValue'])) {
            $this->setValue($data['StringValue']);
        }
        if (isset($data['DecimalValue'])) {
            $this->setDecimalValue($data['DecimalValue']);
        }
        if (isset($data['DateTimeValue'])) {
            $this->setValue($data['DateTimeValue']);
        }
    }

    public function setId($value)
    {
        $this->id = (int) $value;
    }

    public function setType($value)
    {
        if ($value == 'Boolean') {
            $this->type = self::TYPE_BOOLEAN;
        }
        if ($value == 'Text') {
            $this->type = self::TYPE_STRING;
        }
        if ($value == 'Number') {
            $this->type = self::TYPE_DOUBLE;
        }
        if ($value == 'Money') {
            $this->type = self::TYPE_DOUBLE;
        }
        if ($value == 'Url') {
            $this->type = self::TYPE_STRING;
        }
        if ($value == 'Date') {
            $this->type = self::TYPE_DATE;
        }
        if ($value == 'Datetime') {
            $this->type = self::TYPE_DATE_TIME;
        }
        if ($value == 'SingleSelect') {
            $this->type = self::TYPE_STRING;
        }
        $this->type = $value;
    }

    public function setValue($value)
    {
        if ($this->type == self::TYPE_BOOLEAN) {
            $this->value = $value ? 'true' : 'false';
        } else {
            $this->value = $value;
        }
    }

    public function setIntValue($value)
    {
        $this->value = (int) $value;
    }

    public function setDecimalValue($value)
    {
        $this->value = floatval($value);
    }
}