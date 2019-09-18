#Zoho creator API
Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require shohag/zoho-creator-api
```

or add

```
"shohag/zoho-creator-api": "*"
```

to the require section of your `composer.json` file.


Usage
-----

```
require_once 'vendor/autoload.php';
use shohag\CreatorAPI\CreatorAPI;

$conf = array(
            'applicationOwner' => 'your application owner',
            'applicationName' => 'your application Name',
            'authToken' => 'your auth Token',
        );

$creator = new CreatorAPI($conf);
echo $creator->allRecords('view_name');
```
