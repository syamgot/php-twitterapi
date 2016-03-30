<?php

namespace api\twitter;

class TwitterApiException extends \RuntimeException {

	private $messages = [];

	public function __construct($messages, $message = null, $code = 0, Exception $previous = null) {
		$this->setMessages($messages);
		parent::__construct($message, $code, $previous);
	}

	/**
	 * @param array $msgs
	 */
	public function setMessages($messages) {
		if (!is_array($messages)) {
			throw new InvalidArgumentException('1st arg must be array.');
		}
		$this->messages = $messages;
	}

	/**
	 * @return array
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * @return string
	 */
	public function getMessagesByStr() {
		$strs = [];
		foreach($this->messages as $errMsg) {
			$code = $errMsg['code'];
			$msg = $errMsg['message'];
			array_push($strs, "($code) $msg");
		}
		return implode($strs,"\n");
	}

}

