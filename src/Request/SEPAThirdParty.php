<?php

namespace HumanToComputer\Universign\Request;

class SEPAThirdParty extends Base
{
    protected $attributesTypes = [
        'name' => 'string',
        'address' => 'string',
        'postalCode' => 'string',
        'city' => 'string',
        'country' => 'string',
    ];
}