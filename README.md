# ScrapeTwitter
Get the latest tweets every 1 sec 'Unlimited' .

ScrapeTwitter takes data publicly available on Twitter.com

### Some reasons:
  - You don't like authenticating.
  - You don't like rate limits <- :)
  - You don't like restrictive display guidelines.

## Example usage
```php
require 'ScrapeTwitter.php';

$Scrape = new ScrapeTwitter();

//Example get get Tweets
$getTweets = $Scrape->getTweets("iPain7");
echo $getTweets;

//Example get User ID
$getUserID = $Scrape->UserID("iPain7");
echo $getUserID;

//Example get User Info
$getUserInfo = $Scrape->UserInfo("iPain7");
print_r($getUserInfo);

//Example HashTags Used in tweets
$Hashtags = $Scrape->Hashtags("iPain7");
print_r($Hashtags);

//Example Search | Default = top | Type live,top,images,videos
$SearchDefault = $Scrape->Search('ios8','images');
print_r($SearchDefault);
```

## Class Function List

* UserID($User);
* UserInfo($User);
* getTweets($User);
* Hashtags($User);
* Search($String,$Type);
