# TwitterPHP

## Info

TwitterPHP is a Object-Oriented PHP library to the Twitter API.

Twitter will stop supporting HTTP Basic Authentication, when this happens (somewhere around June 2010) TwitterPHP will no longer work. I'm working on a version to support OAuth. Make sure to checkout the `oauth` branch.

## How to use

1. Extract the content of the zip file to a directory
of you choice. If you're placing TwitterPHP on a 
webserver, it should be placed in a directory that the
webserver can access.

2. Now start coding!

## Code samples

### Post an update

	require_once('TwitterPHP.class.php');
	
	$twitter = new TwitterPHP("username", "password");
	$twitter->sendUpdate("I'm posting this using TwitterPHP!");

### Follow another twitter user

	require_once('TwitterPHP.class.php');
	
	$twitter = new TwitterPHP("username", "password");
	$twitter->addUser("rogeriopvl");

## Documentation

You can check the
documentation at <http://rogeriopvl.com/twitterphp>
or at the zip file in the "manual" folder.
