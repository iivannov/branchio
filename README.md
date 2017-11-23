# Branch Metrics (Branch.io) HTTP API Client

This is just a simple HTTP client for the Branch Metrics API (Branch.io). 
At the moment it supports creating, updating and configuring Branch links. A future version will include retrieving data from existing links.
 
<p>
<a href="https://www.codacy.com/app/iivannov/branchio"><img src="https://img.shields.io/codacy/grade/881f4cf300834a89bc6eba1eb51d93f3.svg" alt="Codacy"></a> <a href="https://codeclimate.com/github/iivannov/branchio/maintainability"><img src="https://api.codeclimate.com/v1/badges/942e4b5eb5d37f9bd061/maintainability" alt="Maintainability"></a> <a href="https://packagist.org/packages/iivannov/branchio"><img src="https://img.shields.io/packagist/dt/iivannov/branchio.svg" alt="Packagist"></a> <a href="license.md"><img src="https://poser.pugx.org/iivannov/branchio/license" alt="License"></a> <a href="https://packagist.org/packages/iivannov/branchio"><img src="https://poser.pugx.org/iivannov/branchio/v/stable" alt="Version"></a>
</p>


----------

 
***Table of Contents***
 
- [Important Notice](#important-notice)
- [Installation](#installation)
- [Basic usage](#basic-usage)
  * [Get link data](#get-link-data)
  * [Create new link](#create-new-link)
- [Advanced Usage](#advanced-usage)
  * [Configure link instance](#configure-link-instance)
  * [Analytical Data](#analytical-data)
  * [Configure Link type](#configure-link-type)
  * [Configure custom data](#configure-custom-data)
  * [Create link](#create-link)
  * [Update link](#update-link)
- [Usage with Laravel](#usage-with-laravel)
- [License](#license)
  
  
## Important Notice

The latest versions after `1.1.x` are not backwards compatible with the old versions `1.0.x`.

Be careful when updating! If you want to upgrade to versions `1.1.x`, please first follow the documentation to make the needed changes in your code


## Installation

The package can be installed with Composer. Just run this command:

``` bash
$ composer require iivannov/branchio
```



## Basic usage 

### Get link data

``` php
$client = new \Iivannov\Branchio\Client(KEY, SECRET);
$link = $client->getLink($url);

```
### Create new link
```

$link = new new \Iivannov\Branchio\Link();
 
$link->setChannel('foo')
    ->setAlias('foobar123')
    ->setData([
        '$always_deeplink' => 'true',
        '$deeplink_path' => 'go-to-user-123'
        'user_id' => 123
    ]);
    
$client = new \Iivannov\Branchio\Client(KEY, SECRET);
$client->createLink($link);

```

## Advanced Usage

### Configure link instance

Link instance contains all the configurable options for a Branch Metrics link and provides method to set them.

```
$link = new new \Iivannov\Branchio\Link();
 
// Set parameters separately
$link->setChannel('Channel name');
$link->setAlias('foobar123);
$link->setTags(['foo', 'bar']);

 
// It's possible to chain the set methods
$link->setChannel('Channel name');
    ->setAlias('foobar123');
    ->setTags(['foo', 'bar']);
    
```

### Analytical Data 

For full reference about the analytical options available please see:
https://docs.branch.io/pages/links/integrate/#analytical-labels

| Key           | Usage         |
| ------------- |:-------------:|
| channel       | Use channel to tag the route that your link reaches users. For example, tag links with 'Facebook' or 'LinkedIn' to help track clicks and installs through those paths separately | 
| feature       | This is the feature of your app that the link might be associated with. For example, if you had built a referral program, you would label links with the feature 'referral' |  
| campaign      | Use this field to organize the links by actual campaign. For example, if you launched a new feature or product and want to run a campaign around that | 
| stage         | Use this to categorize the progress or category of a user when the link was generated. For example, if you had an invite system accessible on level 1, level 3 and 5, you could differentiate links generated at each level with this parameter |
| tags          | This is a free form entry with unlimited values ['string']. Use it to organize your link data with labels that don't fit within the bounds of the above |
| alias         | Specify a link alias to replace of the standard encoded short URL. Link aliases must be unique (a 409 error will occur if you create an alias already taken). Appending a / will break the alias. bnc.lt link domain alias links are incompatible with Universal Links and Spotlight. |
| type          | Must be an int. Set to 1 to limit deep link to a single use. Set to 2 to make the link show up under Quick Links while adding $marketing_title to data. Does not work with the Native SDKs |

```
$link->setChannel('Channel name');
 
$link->setFeature('Feature name');
 
$link->setCampaign('Campaign name');
 
$link->setStage('Stage name');
 
$link->setTags(['foo', 'bar']);
 
$link->setAlias('foobar123);
 
$link->setType(UrlType::ONCE);    
```


### Configure Link type

You can easily set the deep linking behaviour by passing the type parameter. You have the following options: 
 
`UrlType::DEFAULT_TYPE` - default value,

`UrlType::ONCE` -  to limit deep linking behavior of the generated link to a single use, 

`UrlType::MARKETING_TYPE` -  to make the link show up under Marketing page in the dashboard

```
$link->setType(UrlType::MARKETING);
```

### Configure custom data

For full description of possible `$data` options, please see: 
https://docs.branch.io/pages/links/integrate/#redirections

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
 
$link->setData($data);
```


### Create link

To create the configured link, just call the `createLink` method and pass the `Link'` instance

``` php
$client = new \Iivannov\Branchio\Client(KEY, SECRET);
$client->createLink($link);
```


### Update link

To update an already existing link you need to pass the url of the link and the updated `Link` instance to the  `updateLink` method

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
Branchio::getLink($url)
Branchio::createLink($link')
Branchio::updateLink($url, $link)
```

## License

The MIT License (MIT). Please see [License File](license.md) for more information.
