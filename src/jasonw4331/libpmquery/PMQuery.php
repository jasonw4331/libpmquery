<?php

declare(strict_types=1);

namespace jasonw4331\libpmquery;

use function explode;
use function fclose;
use function fread;
use function fsockopen;
use function fwrite;
use function pack;
use function str_starts_with;
use function stream_set_blocking;
use function stream_set_timeout;
use function strlen;
use function substr;
use function time;
use const E_WARNING;

class PMQuery{

	/**
	 * @param string $host    Ip/dns address being queried
	 * @param int    $port    Port on the ip being queried
	 * @param int    $timeout Seconds before socket times out
	 *
	 * @return string[]|int[]
	 * @phpstan-return array{
	 *     GameName: string|null,
	 *     HostName: string|null,
	 *     Protocol: string|null,
	 *     Version: string|null,
	 *     Players: int,
	 *     MaxPlayers: int,
	 *     ServerId: string|null,
	 *     Map: string|null,
	 *     GameMode: string|null,
	 *     NintendoLimited: string|null,
	 *     IPv4Port: int,
	 *     IPv6Port: int,
	 *     Extra: string|null,
	 * }
	 * @throws PmQueryException
	 */
	public static function query(string $host, int $port, int $timeout = 4) : array{
		$socket = @fsockopen('udp://' . $host, $port, $errno, $errstr, $timeout);

		if($errno !== 0 && $socket !== false){
			fclose($socket);
			throw new PmQueryException($errstr, $errno);
		}elseif($socket === false){
			throw new PmQueryException($errstr, $errno);
		}

		stream_set_timeout($socket, $timeout);
		stream_set_blocking($socket, true);

		// hardcoded magic https://github.com/facebookarchive/RakNet/blob/1a169895a900c9fc4841c556e16514182b75faf8/Source/RakPeer.cpp#L135
		$OFFLINE_MESSAGE_DATA_ID = pack('c*', 0x00, 0xFF, 0xFF, 0x00, 0xFE, 0xFE, 0xFE, 0xFE, 0xFD, 0xFD, 0xFD, 0xFD, 0x12, 0x34, 0x56, 0x78);
		$command = pack('cQ', 0x01, time()); // DefaultMessageIDTypes::ID_UNCONNECTED_PING + 64bit current time
		$command .= $OFFLINE_MESSAGE_DATA_ID;
		$command .= pack('Q', 2); // 64bit guid
		$length = strlen($command);

		if($length !== fwrite($socket, $command, $length)){
			throw new PmQueryException("Failed to write on socket.", E_WARNING);
		}

		$data = fread($socket, 4096);

		fclose($socket);

		if($data === false || $data === ''){
			throw new PmQueryException("Server failed to respond", E_WARNING);
		}
		if(!str_starts_with($data, "\x1C")){
			throw new PmQueryException("First byte is not ID_UNCONNECTED_PONG.", E_WARNING);
		}
		if(substr($data, 17, 16) !== $OFFLINE_MESSAGE_DATA_ID){
			throw new PmQueryException("Magic bytes do not match.");
		}

		// TODO: What are the 2 bytes after the magic?
		$data = substr($data, 35);

		// TODO: If server-name contains a ';' it is not escaped, and will break this parsing
		$data = explode(';', $data);

		return [
			'GameName' => $data[0] ?? null,
			'HostName' => $data[1] ?? null,
			'Protocol' => $data[2] ?? null,
			'Version' => $data[3] ?? null,
			'Players' => isset($data[4]) ? (int) $data[4] : 0,
			'MaxPlayers' => isset($data[5]) ? (int) $data[5] : 0,
			'ServerId' => $data[6] ?? null,
			'Map' => $data[7] ?? null,
			'GameMode' => $data[8] ?? null,
			'NintendoLimited' => $data[9] ?? null,
			'IPv4Port' => isset($data[10]) ? (int) $data[10] : 0,
			'IPv6Port' => isset($data[11]) ? (int) $data[11] : 0,
			'Extra' => $data[12] ?? null, // TODO: What's in this?
		];
	}
}
