<?php
namespace SocketException

class SocketException extends Exception {
	//extende pra retornar no serviço.
    public function  __construct($message, $code = 0, $previous = null) {
        $this->message = $message;
        $this->code = $code;
        $this->message = $previous;
    }

}

?>