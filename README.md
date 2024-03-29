# libPMQuery

[![Poggit-Ci](https://poggit.pmmp.io/ci.shield/jasonw4331/libpmquery/libpmquery)](https://poggit.pmmp.io/ci/jasonw4331/libpmquery/libpmquery)

A PocketMine Virion which allows plugins to query other servers for a selection of information

## Basic Usage
This virion was made for developers to query Pocketmine-MP servers with ease. Here is some basic functionality:

### Required imports
The following imports are necessary to use the virion library:
```php
use jasonw4331\libpmquery\PMQuery;
use jasonw4331\libpmquery\PmQueryException;
```

### API
The querying API is a single function which grabs the data from whatever server you input. Usage is as follows:
```php
$query = PMQuery::query("my.server.net", 19132);
```
The values returned will follow these values/types:
```php
$query['GameName'];         // Returns the server software being used
$query['HostName'];         // Returns the server host name
$query['Protocol'];         // Returns the protocol version allowed to connect
$query['Version'];          // Returns the client version allowed to connect
$query['Players'];          // Returns the number of players on the server currently
$query['MaxPlayers'];       // Returns the maximum player count of the server
$query['ServerId'];         // Returns the raknet server id
$query['Map'];              // Returns the default world name
$query['GameMode'];         // Returns the default gamemode
$query['NintendoLimited'];  // Returns the status of Nintendo's limitation to join
$query['IPv4Port'];         // Returns the ipv4 port number
$query['IPv6Port'];         // Returns the ipv6 port number
$query['Extra'];            // I still don't know what this info is
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
