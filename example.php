<?php
/**
 * @author Saleh Bin Homoud | Twitter (iPain7)
 * @version 1.0
 * @copyright 2015
 */
require 'ScrapeTwitter.php';

$Scrape = new ScrapeTwitter();

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

?>
