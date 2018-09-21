<?php
//Starta o serviço
namespace Start {
	//imports
	include_once('socketService.php');

	class StartSocket{
		//iniciar
		public function init(){
			$socket = new SocketServ\SocketService();

			$socket->action_start();
		}
		//classe construtora
		public function __construct(){
			self::init();
		}
		//classe para fechar a conexão
		public function __destruct() {
	       	mysql_close();
	   	}
		
	}
	//auto-init
	$StartSocket = new StartSocket();
}

