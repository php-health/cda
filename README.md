# cda

Create HL7-CDA (tm) documents in PHP.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/php-health/cda/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/php-health/cda/?branch=master)

## Usage

```
$doc = new ClinicalDocument();
$doc->setTitle("Good Health Clinic Consultation Note");

// TO BE DONE : add other elements to header

$nonXMLBody = new NonXMLBodyComponent();
$string = new CharacterString();
$string->setContent("This is a narrative text");
$nonXMLBody->setContent($string);
$doc->getRootComponent()->addComponent($nonXMLBody);

// save to XML
$xmlDOM = $doc->toDOMDocument()->saveXML();
$string = $xmlDOM->saveXML();

// $string is...
/*
<?xml version="1.0" encoding="UTF-8"?>
<ClinicalDocument xmlns="urn:hl7-org:v3" templateId="2.16.840.1.113883.3.27.1776">
	<title>Good Health Clinic Consultation Note</title>
        <component>
            <nonXMLBody>
                <text mediaType="text/plain"><![CDATA[
This is a narrative text
]]></text>
            </nonXMLBody>
        </component>
</ClinicalDocument>
*/
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
