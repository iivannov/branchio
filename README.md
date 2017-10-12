#Branch IO HTTP API Client

This is just a simple HTTP client for the Branch Metrics API (Branch.io). 
At the moment it supports creating, updating and configuring Branch links. A future version will include retrieving data from existing links.

<p>

<a href="https://www.codacy.com/app/iivannov/branchio"><img src="https://img.shields.io/codacy/grade/881f4cf300834a89bc6eba1eb51d93f3.svg" alt="Codacy"></a>

<a href="https://codeclimate.com/github/iivannov/branchio/maintainability"><img src="https://api.codeclimate.com/v1/badges/942e4b5eb5d37f9bd061/maintainability" alt="Maintainability"></a>

<a href="https://packagist.org/packages/iivannov/branchio"><img src="https://img.shields.io/packagist/dt/iivannov/branchio.svg" alt="Packagist"></a> 

<a href="license.md"><img src="https://poser.pugx.org/iivannov/branchio/license" alt="License"></a>

<a href="https://packagist.org/packages/iivannov/branchio"><img src="https://poser.pugx.org/iivannov/branchio/v/stable" alt="Version"></a>

</p>


----------


## Table of Contents
 1. [Installation](#installation)
 2. [Usage](#usage)
		2.1 [Initialization](#usage-create-link)	
        2.2 [Campaign and channel](#usage-campaign-channel)	
    	2.3 [Create Link](#usage-create-link)	
    	2.4 [Update Link](#usage-update-link)
 3. [Laravel](#laravel)
 4. [License](#license)

## Installation

The package can be installed with Composer. Just run this command:

``` bash
$ composer require iivannov/branchio
```


## Usage

### Initialization
``` php
$client = new \Iivannov\Branchio\Client(KEY, SECRET);
```

### Set campaign/channel
```
$client->setCampaign('Campaign name')
  
$client->setChannel('Channel name')
```

### Create link

For full description of possible `$data` options, please see: // @see https://dev.branch.io/getting-started/configuring-links/guide/#redirect-customization

```
$data = [
     '$always_deeplink' => true,
     '$deeplink_path' => 'open?action_id=1234',
     
     '$ios_url' => 'http://MyAppURL.com/ios',
     '$ipad_url' => 'http://MyAppURL.com/ipad',
     '$android_url' => 'http://MyAppURL.com/android',
     
     '$og_app_id' => '1234',
     '$og_title' => 'My App',
     '$og_description' => 'My app\'s description.',
     '$og_image_url' => 'http://MyAppURL.com/image.png',
     
     'mydata' => 'something',
     'foo' => 'bar',
];
 
$client->createLink($data, 'MyAlias');
```


### Create different type of links

You can easily set the deep linking behaviour by passing the type parameter. You have the following options: 
 
`UrlType::DEFAULT_TYPE` - default value,

`UrlType::ONCE` -  to limit deep linking behavior of the generated link to a single use, 

`UrlType::MARKETING_TYPE` -  to make the link show up under Marketing page in the dashboard

```
$client->createLink($data, 'MyAlias', \Iivannov\Branchio\Support\UrlType::MARKETING);
```

### Update link
```
$client->updateLink($url, $data, $type);
```


## Usage with Laravel

If you are using Laravel, the package contains a Service Provider and a Facade for you.

1. First you need to add the ServiceProvider and Facade classes in your `config\app.php`

```
'providers' => [
    ...
    Iivannov\Branchio\Integration\Laravel\BranchioServiceProvider::class,
];

'aliases' => [
    ...
    'Branchio' => Iivannov\Branchio\Integration\Laravel\Facade\Branchio::class
];
```

2. Then you need to add your username and password in `config\services.php`

```
'branchio' => [
    'key' => YOUR_BRANCHIO_KEY,
    'secret' => YOUR_BRANCHIO_SECRET
]
```

3.  You are ready to go, just use the facade:

```
Branchio::setCampaign('Campaign name')
Branchio::setChannel('Channel name')
Branchio::createLink($data, 'MyAlias');
```

## License

The MIT License (MIT). Please see [License File](license.md) for more information.