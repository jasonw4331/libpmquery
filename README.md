# libpmquery
A PocketMine Virion which allows plugins to query other servers for information made by jasonwynn
## Basic Usage
This viron was made to query Pocketmine-MP servers with ease. Here is some basic usage you can definently use:

You can import this into your own class, making sure you use the following:
```php
use libpmquery\PMQuery;
use libpmquery\PmQueryException;
```
After you have done that, let's say you want to make your plugin query the amount of servers on your KitPvP server,
you would want to call the function `query` in PMQuery statically like so:
```php
$query = PMQuery::query("ip/domain", port (int));
```
Now, if you look into `PMQuery`, there is a selection of what you can query the selected server:
```php
return [
			'GameName' => $data[0],
			'HostName' => $data[1],
			'Protocol' => $data[2],
			'Version' => $data[3],
			'Players' => $data[4],
			'MaxPlayers' => $data[5],
			'Unknown2' => $data[6], // TODO: What is this?
			'Map' => $data[7],
			'GameMode' => $data[8],
			'Unknown3' => $data[9], // TODO: What is this?
		];
```
Let's say we wanted to query the amount of players online, we would go ahead and do something like this:
```php
$players = (int) $query['Players']; /*we want the amount of players as an integer*/
```
Now, let's say you want to send a message to a player saying how much players there are in the server you
put in when they joined, on your `PlayerJoinEvent` function, you want to see if the server is online first,
so we have to try and send the query:
```php
try{
			$query = PMQuery::query("ip", 19132);
			$players = (int) $query['Players'];
			$event->getPlayer()->sendMessage(TextFormat::BOLD . TextFormat::AQUA . "KitPvP currently has " . $players . " players!");
		}
```
Now, we want to catch any exceptions that might be thrown, such as if the server is offline and we can't query it:
```php
catch(PmQueryException $e){
  //you can choose to log this if you want
  $event->getPlayer()->sendMessage(TextFormat::BOLD . TextFormat::RED . "KitPvP is OFFLINE");
}
```

Kind of basic (and probably bland) documentation, but it's what I got :p
