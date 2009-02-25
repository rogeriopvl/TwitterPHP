<?php
/**
 * TODOs:
 * - check for api errors and give some output
 *
 */

/*************************************************************************
*   TwitterPHP is a PHP library to interact with the Twitter API
*
*   @author Rogerio Vicente <http://rogeriopvl.com>
*   @version 0.2
*
*	Changelog:
*	v0.2 - Added a check to see if cURL is loaded, and some http error
* 	code detection on the connect() method.
*
*   ************************* LICENSE ************************************
*
*   This file is part of TwitterPHP.
*
*   TwitterPHP is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   TwitterPHP is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with TwitterPHP.  If not, see <http://www.gnu.org/licenses/>.
*************************************************************************/

require_once ('defines.inc.php');

class TwitterPHP
{   
    /**
     * Constructor
     * @throws Exception if curl is not loaded
     */
	function __construct ()
    {
    	if (!extension_loaded ('curl'))
    	{
    		throw new Exception ('Error: cURL not loaded. TwitterPHP needs cURL to work correctly.');
    	}
    }
    
    /**
     * Connects to the API and returns an simpleXML object with content.
     * 
     * @param string $host the host to connect to. It should be a twitter api url
     * @param string $conntype GET or POST (read twitter api for details)
     * @return mixed simpleXML object on success, FALSE otherwise
     */
    private function connect ($host, $conntype, $auth)
    {   
        $sess = curl_init();
        
        curl_setopt($sess, CURLOPT_URL, $host);
		curl_setopt($sess, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($sess, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($sess, CURLOPT_REFERER, REFERER);
        
        //check if authentication is needed
        if ($auth == True) {
			require_once ('config.inc.php'); // to get user login and pass	
            curl_setopt($sess, CURLOPT_USERPWD, "$twitterusername:$twitterpassword");
		}
        
        //check if its POST, otherwise its GET
        if ($conntype == 'post') {
            curl_setopt($sess, CURLOPT_POST, 1);
			curl_setopt($sess, CURLOPT_HTTPHEADER, array('Expect:')); //this is for a weird requirement
																	  //by the twitter API
		}
        
        $result = curl_exec($sess);
        $resHeaders = curl_getinfo($sess);
        
        curl_close ($sess); //turn off the water while you're brushing your teeth :P
        
        if ($resHeaders['http_code'] == 200)
	        return simplexml_load_string($result);
	    else
	    	return False;
    }
    
    /**
     * Get the 20 most recent posts in public timeline. Twitter caches this for 60secs.
     * @return object the public timeline in a simpleXML object
     */
    public function getPublicTimeLine ()
    {   
        $host = PUBLICTM.$count;
        return $this->connect ($host, GET, False);
    }
    
    /**
     * Get the updates of a given user. Only works for non blocked updates.
     * @param string $userid the id of the target user
     * @param integer $count the number of update to return. Maximum is 200.
     * @return object a simpleXML object containing the user updates
     */
    public function getUserUpdates ($userid, $count)
    {
        if ($count > 200)
            $count = 200;
        
        $host = USERTM.$userid.".xml?count=".$count;
        return $this->connect ($host, GET, False);
    }
    
    /**
     * Get the 20 most recent direct messages of authenticated user
     * @return object a simpleXML object containing 20 direct messages
     */
    public function getDirectMsgs ()
    {
        $host = DIRECTMSGS;
        return $this->connect ($host, GET, True);
    }
    
    /**
     * Gets the timeline of the authenticated user
	 * @param integer $count the number of posts to display
     * @return object a simpleXML object containing the posts from user and friends
     */
    public function getOwnTimeline ($count)
    {
        if ($count > 200)
            $count = 200;
        
        $host = FRIENDSTM.$count;
        return $this->connect ($host, GET, True);
    }
    
    /**
     * Get the 20 most recent replies for authenticated user
     * @param date $since optional select only replies after this date
     * @return object a simpleXML object containing 20 most recent replies
     */
    public function getReplies ($since = false)
    {
        $since ? $host = REPLIES."?since=".$since : $host = REPLIES;
        return $this->connect ($host, GET, True);
    }
    
	/**
	 * Posts a new status update to twitter.
	 * @param string $message the message to post. Should not be more than 140 chars.
	 * @return abject simpleXML object with status element on success, false otherwise
	 */
    public function sendUpdate ($message)
    {
        $host = UPDATESTATUS.urlencode(stripslashes(urldecode($message)));
		return $this->connect ($host, POST, True);
    }
    
	/**
	 * Posts a reply to a given userid.
	 * @param string $message the text content of the reply
	 * @param string $userid the user to whom the reply is
	 * @return object a simpleXML object with status element on success, false otherwise
	 */
    public function sendReply ($message, $userid)
    {
        $reply = "@".$userid." ".$message;
		return $this->sendUpdate ($reply);
    }
    
	/**
	 * Send a direct message to a given user. You must be friends with the user.
	 * @param string $message the text content of the direct message
	 * @param string $userid the user for whom the direct message is
	 */
    public function sendDirectMessage ($message, $userid)
    {
        $host = SENDDIRECTMSG.$userid."&text=".urlencode(stripslashes(urldecode($message)));
		return $this->connect ($host, POST, True);
    }

	/**
	 * Adds a user as a friend and starts following him.
	 * @param string $userid the id (username) of the target user
	 * @return object a simpleXML object with the target user basic information on success. False otherwise
	 */
	public function addUser ($userid)
	{
		$host = FOLLOW.$userid.".xml?follow=true";
		return $this->connect ($host, POST, True);
	}
	
	/**
	 * Unfriends the given user, also stops following it.
	 * @param string $userid the id of the user to unfollow
	 * @return object a simpleXML object with the target user basic information on sucess, False otherwise
	 */
	public function removeUser ($userid)
	{
		$host = UNFOLLOW.$userid.".xml";
		return $this->connect ($host, POST, True);
	}
	
	/**
     * Get the friends (following) of authtenticated user or target user.
     * Currently only returns 100 users maximum, problem will be solved
     * in future releases.
     * @param string $userid optional the username of the user to get following
     * @return object a simpleXML with the users being followed by the auth user
     */
    public function getFollowing ($userid=null)
    {
        if ($userid == null)
        	$host = FRIENDS.".xml";
        else
        	$host = FRIENDS."/".$userid.".xml";
        
        return $this->connect ($host, GET, True);
    }
    
    /**
     * Get the followers of authenticated user or given user.
     * Currently only returns 100 users maximum, problem will be solved
     * in future releases.
     * @param string $userid optional the username of the user to get followers
     * @return object a simpleXML object with the followers
     */
    public function getFollowers ($userid=null)
    {
        if ($userid == null)
        	$host = FOLLOWERS.".xml";
        else
        	$host = FOLLOWERS."/".$userid.".xml";
        	
        return $this->connect ($host, GET, True);
    }
}
?>
