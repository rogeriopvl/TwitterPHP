<?php
    /**
	*   TwitterPHP is a PHP library to interact with the Twitter API
	*
	*   @author Rogerio Vicente <http://rogeriopvl.com>
	*   @version 0.9
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
	*/
	define ("VERSION", "0.9");
	define ("USER_AGENT", "TwitterPHP v".VERSION);
	define ("APP_URL", "http://rogeriopvl.com/twitterphp");
        
    define ("GET", "get");
    define ("POST", "post");
	
	//URL's for some twitter api methods
	define ("API_LIMIT", "http://twitter.com/account/rate_limit_status");
	define ("PUBLICTM", "http://twitter.com/statuses/public_timeline");
    define ("USERTM", "http://twitter.com/statuses/user_timeline/");
    define ("FRIENDSTM", "http://twitter.com/statuses/friends_timeline");
	define ("FOLLOWERS", "http://twitter.com/statuses/followers");
	define ("REPLIES", "http://twitter.com/statuses/replies");
	define ("DIRECTMSGS", "http://twitter.com/direct_messages");
	define ("FRIENDS", "http://twitter.com/statuses/friends");
	define ("LEAVE", "http://twitter.com/notifications/leave/");
	define ("FOLLOW", "http://twitter.com/friendships/create/");
	define ("UNFOLLOW", "http://twitter.com/friendships/destroy/");
	define ("UPDATESTATUS", "http://twitter.com/statuses/update");
	define ("SENDDIRECTMSG", "http://twitter.com/direct_messages/new");
?>
