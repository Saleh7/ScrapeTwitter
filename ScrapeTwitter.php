<?php
ini_set('default_charset', 'utf-8');
/**
 * @author Saleh Bin Homoud | Twitter (iPain7)
 * @version 1.0
 * @copyright 2015
 */
class ScrapeTwitter
{	
    /**
     * @param String | $User -> Account Twitter 
     * @example https://twitter.com/(iPain7)
     */
	public function UserInfo($User)
	{
        $Html = $this->Url("https://twitter.com/$User");
        $Data = array();
        $Data['id'] = $this->MatchOne('/'.$User.'" data-user-id="(.*?)"/', $Html, 1);
        $Data['screen_name'] = $User;
        $Data['name'] = $this->MatchOne('/" data-name="(.*?)"/', $Html, 1);
        $Data['tweets_count'] = $this->MatchOne('/title="(.*?) Tweets"/', $Html, 1);
        $Data['friends_count'] = $this->MatchOne('/title="(.*?) Following"/', $Html, 1);
        $Data['followers_count'] = $this->MatchOne('/title="(.*?) Followers"/', $Html, 1);
        $Data['last_tweets'] = $this->MatchOne('/data-max-position="([0-9-_]*)"/', $Html, 1);
        $Data['id_tweets'] = $this->MatchAll('/status\/(.*?)" class="tweet/', $Html, 1);
        $Data['profile_image_url'] = $this->MatchOne('/data-resolved-url-large="(.*?)"/', $Html, 1);
        $Data['bio'] = $this->MatchOne('/>(.*?)<\/p>/', $Html, 1);
        $Data['location'] = $this->MatchOne('/js-trend-location">(.*?)<\/span>/', $Html, 1);
		return $Data;
	}
    /**
     * @param String | $User
     * @return Result id tweets | The expected number (24)
     */
	public function getTweets($User)
	{
        $Html = $this->Url("https://twitter.com/$User");
        $Data = array();
        $Data = $this->MatchAll('/status\/(.*?)" class="tweet/', $Html, 1);
		return $Data;
	}
    /**
     * @param String | $User
     * @return Result HashTags Used in tweets
     */
   	public function Hashtags($User)
	{
        $Html = $this->Url("https://mobile.twitter.com/$User");
        $Hashtags= FALSE;  
        preg_match_all("/(#\w+)/u", $Html, $Matches);  
        if ($Matches) {
            $HashtagsArray = array_count_values($Matches[0]); //Non-repetition
            $Hashtags = array_keys($HashtagsArray);
        }
        return $Hashtags;
	}
    /**
     * @param String | $Search
     * @param String | $Type  -> String Type of Response | Default=top
     * @example Search('ios') or Search('ios','videos')
     */
   	public function Search($Search,$Type = 'top')
	{
	   switch($Type){
           case "live":
           $Html = $this->Url("https://twitter.com/search?q=".urlencode($Search)."&f=tweets");
           $Text = $this->MatchAll('/<p class="(.*?)" lang="(.*?)" data-aria-label-part="0">(.*?)<\/p>/', $Html, 3);
           $Data = $this->Filters($Text);
           return $Data;
           // Result id tweets
           break;
           
           case "top":
           $Html = $this->Url("https://twitter.com/search?q=".urlencode($Search));
           $Text = $this->MatchAll('/<p class="(.*?)" lang="(.*?)" data-aria-label-part="0">(.*?)<\/p>/', $Html, 3); // supports all languages
           $Data = $this->Filters($Text); // Remove coding
           return $Data;
           // Result id tweets
           break;
           
           case "images":
           $Html = $this->Url("https://twitter.com/search?q=".urlencode($Search)."&f=images");
           $Data = $this->MatchAll('/data-url="(.*?)"/', $Html , 1);
           return $Data;
           // Result url img
           break;
           
           case "videos":
           $Html = $this->Url("https://twitter.com/search?q=".urlencode($Search)."&f=videos");
           $Data = $this->MatchAll('/data-tweet-id="(.*?)"/', $Html , 1);
           return $Data;
           // Result id tweets
           break;
	   }
	}
    /**
     * @param String | $User
     * @return Result id user
     */
	public function UserID($user){
        $Html = $this->Url("https://twitter.com/$user");
		$ID = $this->MatchOne('/'.$user.'" data-user-id="(.*?)"/', $Html, 1);
		return $ID;
	}
    /**
     * @param Url | Contact
     * @return Result html
     */
	private function Url($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$ip = rand(1,5).'.'.rand(50,255).'.'.rand(1,255).'.'.rand(10,255);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
        $agents = array(
        	'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; AS; rv:11.0) like Gecko',
            'Mozilla/5.0 (Windows NT 6.1; rv:31.0) Gecko/20100101 Firefox/39.0'
        );
        curl_setopt($ch,CURLOPT_USERAGENT,$agents[array_rand($agents)]);
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
	}
    /**
     * @param String | $Regex
     * @param String | $Html
     * @param integer | $KeyIndex | Default 1
     */
	private function MatchAllKey($Regex, $Html, $KeyIndex = 1){
	   $arr = array();
		preg_match_all($Regex, $Html, $Matches, PREG_SET_ORDER);
		foreach($Matches as $m){
			$arr[] = $m[$KeyIndex];
		}
		return $arr;
	}
    /**
     * @param String | $Regex
     * @param String | $Html
     * @param integer | $KeyIndex | Default 0
     */
	private function MatchAll($Regex, $Html, $KeyIndex = 0){
		if(preg_match_all($Regex, $Html, $Matches) === false)
			return false;
		else
			return $Matches[$KeyIndex];
	}
    /**
     * @param String | $Regex
     * @param String | $Html
     * @param integer | $KeyIndex | Default 0
     */
	private function MatchOne($Regex, $Html, $KeyIndex = 0){
		if(preg_match($Regex, $Html, $Match) == 1)
			return $Match[$KeyIndex];
		else
			return false;
	}
    /**
     * @link More Details | http://stackoverflow.com/questions/657643/how-to-remove-html-special-chars
     */
    private function StripOnly($str, $tags, $stripContent = false) {
        $content = '';
        if(!is_array($tags)) {
            $tags = (@strpos($str, '>') !== false
                     ? explode('>', str_replace('<', '', $tags))
                     : array($tags));
            if(end($tags) == '') array_pop($tags);
        }
        foreach($tags as $tag) {
            if ($stripContent)
                 $content = '(.+</'.$tag.'[^>]*>|)';
             $str = preg_replace('#</?'.$tag.'[^>]*>'.$content.'#is', '', $str);
        }
        return $str;
    }
    /**
     * @param String | $Data
     */
    private function Filters($Data) {
        $tags = array('a','strong','b','span','s','img'); //Remove from the text
        $Data = $this->StripOnly($Data,$tags);
        $Data = preg_replace("/&#?[a-z0-9]+;/i","",$Data);
        return $Data;
    }
    
}
?>
