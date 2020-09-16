# libpmquery
A PocketMine Virion which allows plugins to query other servers for a selection of information
## Basic Usage
This viron was made for developers to query Pocketmine-MP servers with ease. Here is some basic functionality:

### Required imports
The following imports are necessary to use the virion library:
```php
use libpmquery\PMQuery;
use libpmquery\PmQueryException;
```

### API
The querying API is a single function which grabs the data from whatever server you input. Usage is as follows:
```php
$query = PMQuery::query("my.server.net", 19132);
```
The values returned will follow these values/types:
```php
$query['GameName'];   // Returns the server software being used
$query['HostName'];   // Retruns the server host name
$query['Protocol'];   // Returns the protocol version allwoed to connect
$query['Version'];    // Returns the client version allowed to connect
$query['Players'];    // Returns the number of players on the server currently
$query['MaxPlayers']; // Returns the laximum player count of the server
$query['ServerId'];   // Returns the server id
$query['map'];        // Returns the default world name
$query['GameMode'];   // Returns the default gamemode
$query['Unknown3'];   // hmm yea still dunno what this is
```

### Offline Queries
Queries sent to offline servers always throw a `PmQueryException`. Exceptions can be caught in a try/catch statement to log their offline status.
```php
try{
    $query = PMQuery::query("my.server.net", 19133);
    $players = (int) $query['Players'];
    Server::getInstance()->getLogger()->info("There are ".$players." on the queried server right now!");
}catch(PmQueryException $e){
    //you can choose to log this if you want
    Server::getInstance()->getLogger()->info("The queried server is offline right now!");
}
```
