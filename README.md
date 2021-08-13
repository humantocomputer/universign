# Universign PHP Library



This library is unofficial, not developed by Universign.

This library is based on https://github.com/pierrebelin/universign but seems abonned. So we forked the project to make our own modification.

-------

## How to use

You can copy-paste the following code and it will automatically connect you to your universign account.

### Fill constants

```php
$ACCOUNT_USER_MAIL = 'email@domain.fr';
$ACCOUNT_USER_PASSWORD = 'xxxxxxxxx';
$UNIVERSIGN_PROFILE = 'default'; // you don't have to change this one, if you want to, read the manual
```



### Create signature fields

```php
$signatureField1 = new DocSignatureField();
$signatureField1->setPage(1)
                ->setX(10)
                ->setY(230)
                ->setSignerIndex(0);
```

### Get your document

```php
$document = new TransactionDocument();
$document
    ->setContent(file_get_contents('path/to/file/file.pdf'))
    ->addSignatureField($signatureField1) // you can add multiples times ->addSignatureField($signatureField2) etc...
    ->setName('Document name');
```

### Create signers

```php
$signer = new TransactionSigner();
$signer
    ->setFirstname('Doe')
    ->setLastname('John')
    ->setEmailAddress('johndoe@gmail.com')
    ->setPhoneNum('0606060606')
    ->setBirthDate('19900131T00:00:00') // This format is needed yyyymmddT00:00:00 as string for 31/01/1990
    ->setSuccessURL('https://www.universign.eu/fr/sign/success/')
    ->setCancelURL('https://www.universign.eu/fr/sign/cancel/')
    ->setFailURL('https://www.universign.eu/fr/sign/failed/');
    
```

### Create transaction

```php
$request = new TransactionRequest();
$request
    ->addSigner($signer) // you can add multiples times ->addSignatureField($signer2) etc...
    ->addDocument($document) // you can add multiples times ->addDocument($document2) etc...
    ->setProfile($UNIVERSIGN_PROFILE)
    ->setCustomId(uniqid()) // create your own ID to make easier to get later
    ->setMustContactFirstSigner(false)
    ->setFinalDocSent(true)
    ->setDescription("This is my description")
    ->setCertificateType(TransactionRequestCertificate::CERTIFICATE_CERTIFIED)
    ->setLanguage(TransactionRequestLanguage::FRENCH)
    ->setHandwrittenSignature(true)
    ->setChainingMode(TransactionRequestChainingMode::CHAINING_MODE_EMAIL);
```

### Transaction request

```php
$requester = new Requester($ACCOUNT_USER_MAIL, $ACCOUNT_USER_PASSWORD, false);
$requester->requestTransaction($request);
```

### Transaction response

```php
$transactionId = $response->getId();
$transactionUrl = $response->getUrl();
```

### Get documents

```php
$response = $requester->getDocuments('TRANSACTIONID');
$response = $requester->getDocumentsByCustomId('CUSTOMID');
```

### Get Transaction info

```php
$response = $requester->getTransactionInfo('TRANSACTIONID');
```

### Send SEPA

Replace your document by :

```php
$sepaFrom = new SEPAThirdParty();
$sepaFrom
    ->setName('from')
    ->setAddress('this address')
    ->setPostalCode('69001')
    ->setCity('Lyon')
    ->setCountry('France');

$sepaTo = new SEPAThirdParty();
$sepaTo
    ->setName('to')
    ->setAddress('to address')
    ->setPostalCode('69002')
    ->setCity('Lyon')
    ->setCountry('France');

$sepa = new SEPAData();
$sepa
    ->setIcs('XXXXXXXXXXXXX')
    ->setIban('FR7616798000010000191892XXXX')
    ->setBic('TRZOFR21XXX')
    ->setDebtor($sepaFrom)
    ->setCreditor($sepaTo);

$document = new TransactionDocument();
$document
    ->setDocumentType(TransactionDocumentType::SEPA)
    ->setName('SEPA')
    ->setSEPAData($sepa);
```

## Issues

### Classes not found

Do not forget to include your autoload and all classes

```php
require __DIR__ . '/vendor/autoload.php';

use HumanToComputer\Universign\{
    Request\TransactionDocument, 
    Request\TransactionSigner, 
    ...
};
```

