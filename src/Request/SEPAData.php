<?php

namespace HumanToComputer\Universign\Request;

class SEPAData extends Base
{
    protected $attributesTypes = [
        'rum' => 'string',
        'ics' => 'string',
        'iban' => 'string',
        'bic' => 'string',
        'recurring' => 'bool',
        'debtor' => 'HumanToComputer\Universign\Request\SEPAThirdParty',
        'creditor' => 'HumanToComputer\Universign\Request\SEPAThirdParty',
    ];
}