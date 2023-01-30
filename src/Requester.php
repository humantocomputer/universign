<?php

namespace HumanToComputer\Universign;

use HumanToComputer\Universign\Request\TransactionRequest;
use HumanToComputer\Universign\Response\TransactionInfo;
use HumanToComputer\Universign\Response\TransactionResponse;
use HumanToComputer\Universign\Response\TransactionDocument;
use UnexpectedValueException;

require_once dirname(__DIR__) . '/lib/xmlrpc/xmlrpc.inc';
require_once dirname(__DIR__) . '/lib/xmlrpc/xmlrpcs.inc';
require_once dirname(__DIR__) . '/lib/xmlrpc/xmlrpc_wrappers.inc';

class Requester
{
    private $userMail;
    private $userPassword;
    private $isTest;
    private $debug;

    function __construct($userMail, $userPassword,$isTest,$debug=false)
    {
        $this->userMail = $userMail;
        $this->userPassword = $userPassword;
        $this->isTest = $isTest;
        $this->debug = $debug;
    }

    /** 
     * Send documents + signers to Universign and return the URL + the ID of the document
     * 
     * @param   \HumanToComputer\Universign\Request\TransactionRequest $transactionRequest
     * @return  \HumanToComputer\Universign\Response\TransactionResponse
     */
    public function requestTransaction(TransactionRequest $transactionRequest)
    {
        $client = $this->getClient();
        $request = new \xmlrpcmsg('requester.requestTransaction', [$transactionRequest->buildRpcValues()]);
        $response = &$client->send($request);

        if (!$response->faultCode()) {
            return new TransactionResponse($response->value());
        }

        if($this->debug)
        {
            dump($response);
        }

        throw new \Exception($response->errstr, $response->errno);
    }
    
    /** 
     * Get documents for customId
     * 
     * @param   string $customId
     * @return  \HumanToComputer\Universign\Response\TransactionDocument[]
     */
    public function getDocumentsByCustomId($customId)
    {
        $client = $this->getClient();
        $request = new \xmlrpcmsg('requester.getDocumentsByCustomId', [new \xmlrpcval($customId, 'string')]);
        $response = &$client->send($request);

        if (!$response->faultCode()) {
            $nbDocuments = $response->value()->arraysize();

            for($i = 0; $i < $nbDocuments; $i++){
                $data[] = new TransactionDocument($response->value()->arraymem($i));
            }

            return $data;
        } 

        throw new UnexpectedValueException($response);
    }

    /** 
     * Get documents for transactionId
     * 
     * @param   string $transactionId
     * @return  \HumanToComputer\Universign\Response\TransactionDocument[]
     */
    public function getDocuments($transactionId)
    {
        $client = $this->getClient();
        $request = new \xmlrpcmsg('requester.getDocuments', [new \xmlrpcval($transactionId, 'string')]);
        $response = &$client->send($request);

        if (!$response->faultCode()) {
            $nbDocuments = $response->value()->arraysize();

            for($i = 0; $i < $nbDocuments; $i++){
                $data[] = new TransactionDocument($response->value()->arraymem($i));
            }

            return $data;
        } 

        throw new UnexpectedValueException($response);
    }


    /**
     * Get transaction info for transactionId
     *
     * @param   string $transactionId
     * @return  \HumanToComputer\Universign\Response\TransactionInfo[]
     */
    public function getTransactionInfo($transactionId)
    {
        $client = $this->getClient();
        $request = new \xmlrpcmsg('requester.getTransactionInfo', [new \xmlrpcval($transactionId, 'string')]);
        $response = &$client->send($request);

        if (!$response->faultCode()) {
            return new TransactionInfo($response->value());
        }

        if($this->debug)
        {
            dump($response);
        }

        throw new UnexpectedValueException($response);

    }

    /**
     * Cancel transaction for transactionId
     *
     * @param   string $transactionId
     * @return  \HumanToComputer\Universign\Response\TransactionInfo[]
     */
    public function cancelTransaction($transactionId)
    {
        $client = $this->getClient();
        $request = new \xmlrpcmsg('requester.cancelTransaction', [new \xmlrpcval($transactionId, 'string')]);
        $response = &$client->send($request);

        if (!$response->faultCode()) {
            return true;
        }

        if($this->debug)
        {
            dump($response);
        }

        throw new \Exception($response->errstr, $response->errno);
    }

    private function getURLRequest() 
    {
        if($this->isTest) {
            return 'https://' . $this->userMail . ':'. $this->userPassword  . '@ws.test.universign.eu/sign/rpc/';
        }
        return 'https://' . $this->userMail . ':'. $this->userPassword  . '@ws.universign.eu/sign/rpc/';
    }
    
    private function getClient() {
        $client = new \xmlrpc_client($this->getURLRequest());
        $client->setSSLVerifyHost(1);
        $client->setSSLVerifyPeer(1);
        $client->setDebug(0);
        return $client;
    }
}
