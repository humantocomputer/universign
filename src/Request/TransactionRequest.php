<?php

namespace HumanToComputer\Universign\Request;
// require_once dirname(__DIR__) . '/Base.php';

abstract class TransactionRequestChainingMode
{
    const CHAINING_MODE_NONE = 'none';
    const CHAINING_MODE_EMAIL = 'email';
    const CHAINING_MODE_WEB = 'web';
}

abstract class TransactionRequestCertificate
{
    const CERTIFICATE_SIMPLE = 'simple';
    const CERTIFICATE_CERTIFIED = 'certified';
    const CERTIFICATE_ADVANCED = 'advanced';
}

class TransactionRequest extends Base
{
    protected $attributesTypes = [
        'profile' => 'string',
        'customId' => 'string',
        'signers' => 'array',
        'documents' => 'array',
        'mustContactFirstSigner' => 'bool',
        'finalDocSent' => 'bool',
        'description' => 'string',
        'certificateType' => 'string',
        'language' => 'string',
        'handwrittenSignature' => 'bool',
        'chainingMode' => 'string',
        'finalDocRequesterSent' => 'bool',
        'finalDocCCeMails' => 'array',
        'registrationCallbackURL' => 'string',
    ];

    public function addSigner(TransactionSigner $signer)
    {
        $this->attributes['signers'][] = $signer;
        return $this;
    }

    public function addDocument(TransactionDocument $document)
    {
        $this->attributes['documents'][] = $document;
        return $this;
    }
}
