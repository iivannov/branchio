# Branch IO HTTP API Client

A simple client for the Branch.io HTTP API
----------

## Install

Via Composer

``` bash
$ composer require iivannov/branchio
```

## Future Features and Contribution

- Support for more Analytics labels as 'feature', 'stage', 'tags'
- Retrieve data from existing links


## Usage

### Initialization
``` php
$client = new \Iivannov\Branchio(KEY, SECRET);
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