# unicheck-corp-php-sdk

> **DEPRECATION NOTICE**: The Unicheck service was officially discontinued on **January 1, 2025**, after being acquired by Turnitin. The Unicheck Corporate API is permanently offline, and this SDK is no longer functional. The original repository is now archived and no longer maintained. This repository is kept for historical purposes only. If you need plagiarism checking services, please migrate to Turnitin or another active provider.

[![Packagist](https://img.shields.io/packagist/v/unicheck/unicheck-corp-php-sdk.svg?style=flat-square)](https://packagist.org/packages/unicheck/unicheck-corp-php-sdk)

PHP SDK for Unicheck.com corporate API 2.0+.  
SDK implements API methods in PHP OOP way.

## Installation
#### Using composer
```bash
#Require Unicheck sdk
php composer.phar require unicheck/unicheck-corp-php-sdk
```

## Usage
```php
//create Unicheck client
$unicheck = new Unicheck('YOUR-API-KEY', 'YOUR-API-SECRET');

//upload file
$file = $unicheck->fileUpload(PayloadFile::bin($testText), 'txt');

//start check
$checkParam = new CheckParam($file['id']);
$checkParam->setType(CheckParam::TYPE_WEB);

$check = $unicheck->checkCreate($checkParam);

echo 'Check started!' . PHP_EOL;
var_dump($check);
```

## Help and docs

- [Unicheck API Documentation](https://corpapi.unicheck.com/api/doc)