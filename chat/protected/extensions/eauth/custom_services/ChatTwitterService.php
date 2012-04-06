<?php
require_once dirname(dirname(__FILE__)).'/services/TwitterOAuthService.php';

class ChatTwitterService extends TwitterOAuthService {
	
	protected function fetchAttributes() {
		$info = $this->makeSignedRequest('https://api.twitter.com/1/account/verify_credentials.json');

		$this->attributes['id'] = $info->id;
		$this->attributes['name'] = $info->screen_name;

        $photo_info = $this->makeRequest('https://api.twitter.com/1/users/profile_image?screen_name=' . $info->screen_name . '&size=original', array(), false);
        $matches = array();
        if (preg_match('/https.+\.jpg/', $photo_info, $matches))
		    $this->attributes['photo'] = $matches[0];
	}
}