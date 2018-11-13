<?php 
//classe do serviço
namespace SocketServ{
    //include do model(caso use)
    include_once('serviceModel.php');

    class SocketService{
        
        public function action_start(){
            
            
            $conexao = new \ServiceModel\ServiceModel();

            //REDUZIR ERROS
            error_reporting(~E_WARNING);
            //mensagens que vão para LOGS
            $mensagens_log = "";
            //CRIA O SOCKET UDP
            //conexões 
            try{
                if(!($sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP))){
                    //pega erros
                    $errorcode = socket_last_error();
                    $errormsg = socket_strerror($errorcode);
                    echo "Couldn't create socket: [$errorcode] $errormsg \n". PHP_EOL."\r\n";
                    $mensagens_log.=  "Couldn't create socket: [$errorcode] $errormsg \n". PHP_EOL."\r\n";
                    throw new \SocketException("$errormsg", 1);
                }
                echo "Socket created \n";
                $mensagens_log.= "Socket created ". PHP_EOL."\r\n";
            }catch(Exception $exeption){
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                echo "erro: $errormsg | $exeption";
                $mensagens_log.= "erro: $errormsg | $exeption ". PHP_EOL."\r\n";
            }
            //SERVIDOR do host
            $host = "192.168.1.1" ;

            //CONECTA O SOCKET AO SERVIDOR NA PORTA 8080
            try{
                if(!socket_bind($sock, $host, 8080)){
                    $errorcode = socket_last_error();
                    $errormsg = socket_strerror($errorcode);
                    echo "Could not bind socket : [$errorcode] $errormsg \n". PHP_EOL."\r\n";
                    throw new \SocketException("$errormsg", 1);
                    
                    $mensagens_log.= "Could not bind socket : [$errorcode] $errormsg \n". PHP_EOL."\r\n";
                }
            }catch(Exception $exeption){
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                echo "erro: $errormsg | $exeption";
                $mensagens_log.= "\nerro: $errormsg | $exeption ". PHP_EOL."\r\n";
            }
            //saída no serviço
            echo "Socket bind OK \n";
            $mensagens_log.= "\nSocket bind OK \n". PHP_EOL."\r\n";

            //COLOCA O SOCKET PARA ESCUTAR E ACEITAR
            socket_listen($sock);
            socket_accept($sock);
            
            while(1){
                //CRIA LOOP PARA RECEBER INFORMACOES
                date_default_timezone_set('America/Sao_Paulo');
                $horaInteira = date('H:i');
                $hora = explode(":",$horaInteira);
                if(!file_exists("/service/Logs/".date('d-m-Y'))){
                    mkdir("/service/Logs/".date('d-m-Y'), 0777,true);
                    if($mensagens_log != ""){
                        $meuArquivo = fopen("/service/Logs/".date('d-m-Y')."/".date('d-m-Y')."-".$hora[0]."-00.txt", "w");
                        chmod("/service/Logs/".date('d-m-Y')."/".date('d-m-Y')."-".$hora[0]."-00.txt", 0777);
                        fwrite($meuArquivo, $mensagens_log."\r\n ---------------");   
                    }
                }else{
                    $meuArquivo = fopen("/service/Logs/".date('d-m-Y')."/".date('d-m-Y')."-".$hora[0]."-00.txt", "a");
                    chmod("/service/Logs/".date('d-m-Y')."/".date('d-m-Y')."-".$hora[0]."-00.txt",0777);
                    fwrite($meuArquivo, $mensagens_log."\r\n ---------------");
                }
                //saída dos serviços
                echo "\nWaiting for data ... \n";
                $mensagens_log.= "\nSocket bind OK \n". PHP_EOL."\r\n";

                //INICIA MENSAGEM DE RETORNO
                $msg = "";
                
                //RECEBE AS INFORMACOES
                try{
                    //socket_recvfrom(socket, buf, len, flags, name)
                    $r = socket_recvfrom($sock, $buf, 65535, 0, $remote_ip, $remote_port);
                }catch(Exception $e){
                    echo $e;
                    $mensagens_log.= "\n".$e."\n\r\n". PHP_EOL."\r\n";
                }
                //ESCREVE NA TELA OS DADOS RECEBIDOS
                echo "$remote_ip : $remote_port -- " . $buf;
                $mensagens_log.= "\n$remote_ip : $remote_port -- " . $buf. PHP_EOL."\r\n";

                /// processamento do buffer e recebimento do formato de dados

                /**/
                //responde conforme o processamento
                if($msg != ""){
                    socket_sendto($sock, $msg, 65535 , 0 , $remote_ip , $remote_port);
                }

                $mensagens_log.="\r\n ---------------";
                //stream pra logs diários e por hora
                if(file_exists("/service/Logs/".date('d-m-Y'))){
                    date_default_timezone_set('America/Sao_Paulo');
                    $horaInteira = date('H:i');
                    $hora = explode(":",$horaInteira);
                    $meuArquivo = fopen("/service/Logs/".date('d-m-Y')."/".date('d-m-Y')."-".$hora[0]."-00.txt", "a");
                    chmod("/service/Logs/".date('d-m-Y')."/".date('d-m-Y')."-".$hora[0]."-00.txt",0777);
                    fwrite($meuArquivo, $mensagens_log."\r\n ---------------");          
                }
                fclose($meuArquivo); 

            } 
        }
    }
}
