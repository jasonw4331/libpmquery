<?php
namespace libpmquery;

class PMQuery {
	/**
	 * @param string $host
	 * @param int $port
	 * @param int $timeout
	 *
	 * @return array
	 * @throws PmQueryException
	 */
	public static function query(string $host, int $port, int $timeout = 3) {
		$socket = @fsockopen('udp://' . $host, $port, $errno, $errstr, $timeout);

		if($errno or $socket === false) {
			throw new PmQueryException($errstr, $errno);
		}

		stream_Set_Timeout($socket, $timeout);
		stream_Set_Blocking($socket, true);

		$randInt = mt_rand(1, 999999999);
		$reqPacket = "\x01";
		$reqPacket .= pack('Q*', $randInt);
		$reqPacket .= "\x00\xff\xff\x00\xfe\xfe\xfe\xfe\xfd\xfd\xfd\xfd\x12\x34\x56\x78";
		$reqPacket .= pack('Q*', 0);

		fwrite($socket, $reqPacket, strlen($reqPacket));

		$response = fread($socket, 4096);

		fclose($socket);

		if (empty($response) or $response === false) {
			throw new PmQueryException("Server failed to respond");
		}
		if (substr($response, 0, 1) !== "\x1C") {
			throw new PmQueryException("Unknown Error");
		}

		$serverInfo = substr($response, 35);
		$serverInfo = preg_replace("#§.#", "", $serverInfo);
		$serverInfo = explode(';', $serverInfo);

		return [
			'motd' => $serverInfo[1],
			'num' => $serverInfo[4],
			'max' => $serverInfo[5],
			'version' =>  $serverInfo[3],
			'platform' => 'PE'
		];
	}
}