<?php
class Requests
{
	public $handle;
	public $result = [];

	public function __construct()
	{
		$this->handle = curl_multi_init();
	}

	public function process($urls)
	{
		foreach($urls as $key=>$url)
		{
			$ch = curl_init($url);
			curl_setopt_array($ch, array(
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_CONNECTTIMEOUT => 10,
			));
			curl_multi_add_handle($this->handle, $ch);
		}

		do {
			$mrc = curl_multi_exec($this->handle, $active);

			if ($state = curl_multi_info_read($this->handle))
			{
				$info = curl_getinfo($state['handle']);
				$this->result[array_search($info['url'], $urls)] = curl_multi_getcontent($state['handle']);
				curl_multi_remove_handle($this->handle, $state['handle']);
			}

			usleep(10000); // stop wasting CPU cycles and rest for a couple ms

		} while ($mrc == CURLM_CALL_MULTI_PERFORM || $active);

	}

	public function __destruct()
	{
		curl_multi_close($this->handle);
	}
}