<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Method libpmquery\\\\PMQuery\\:\\:query\\(\\) should return array\\{GameName\\: string\\|null, HostName\\: string\\|null, Protocol\\: string\\|null, Version\\: string\\|null, Players\\: int, MaxPlayers\\: int, ServerId\\: string\\|null, Map\\: string\\|null, \\.\\.\\.\\} but returns array\\{GameName\\: string\\|null, HostName\\: string\\|null, Protocol\\: string\\|null, Version\\: string\\|null, Players\\: int, MaxPlayers\\: int, ServerId\\: string\\|null, Map\\: string\\|null, \\.\\.\\.\\}\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/libpmquery/PMQuery.php',
];
$ignoreErrors[] = [
	'message' => '#^Strict comparison using \\!\\=\\= between int and null will always evaluate to true\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/libpmquery/PMQuery.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
