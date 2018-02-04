# newman-php-coverager
PHP-Library for newman test phpcode coverage

## Installation
```
composer require wallend/newman-php-coverager: "dev-master"
```

or
```
"require": {
   "wallend/newman-php-coverager": "dev-master"
  }
```

##How to use?
###FPM/Apache

For listen code coverage you should add Coverage class into top of your application index file.

```
<?php
require(__DIR__ . '/../../vendor/autoload.php');
 
use newmanPhpCoverager\Coverage;
 
Environment::init();
 
$coverage = new Coverage();

``` 
###CLi
```
#Generate HTML reports

php ./vendor/wallend/newman-php-coverager/phpnewman --collect-reports merge ../phpnewman --html /path/to/html/report
 
#Generate clover reports
php ./vendor/wallend/newman-php-coverager/phpnewman --collect-reports merge /tmp/coverage --clover /tmp/clover.xml


```
##Versioning

There are no production-ready version now, but i'm working for... =) Sorry.

***Use latest release, if you want...***