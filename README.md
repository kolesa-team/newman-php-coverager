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

For listen code coverage you should add Coverage class into top of your application index file.

```
<?php
require(__DIR__ . '/../../vendor/autoload.php');
 
use newmanPhpCoverager\Coverage;
 
Environment::init();
 
$coverage = new Coverage();

``` 

##Versioning

There are no production-ready version now, but i'm working for... =) Sorry.

***Use dev-master, if you want...***