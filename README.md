# cda

Create HL7-CDA (tm) documents in PHP.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/php-health/cda/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/php-health/cda/?branch=master)

## Usage

Currently, see the usage in tests. The class [`ClinicalDocumentTest` is a good starting point](tests/ClinicalDocumentTest.php).

### Manage references

Each `ClinicalDocument` has its own `ReferenceManager`, which help to manage references across documents.

`ReferenceType` may be added on some elements to create a reference :

```
$doc = new ClinicalDocument();

$refManager = $doc->getReferenceManager();

// create an element 'element' which may have a reference

$element->setReference($refManager->getReferenceType('my_reference'));
// will create <element ID="my_reference">blabla</element>

// add the reference in a text

$text->setText($refManager->getReferenceElement('my_reference'));
// will create <text><reference value="my_reference" /></text>

```

## Run tests

1. Run composer to load phpunit and build autoload :

```
composer install
```

2. Run tests

```
php vendor/bin/phpunit
```
