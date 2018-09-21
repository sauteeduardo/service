<?php


namespace ServiceModel{

	Class ServiceModel{
		const localizacao = "localhost";
        const bancoDeDados = "service";
        const usuario = "service";
        const senha = "service";
        public function __construct(){
        	//CONEXÃO COM O BANCO
            mysql_connect(self::localizacao, self::usuario, self::senha) or die("N&atilde;o foi poss&iacute;vel conectar com o banco de dados<br />Entre em contato com seu administrador ou, se voc&ecirc; &eacute; o administrador, confira os dados de configura&ccedil;&atilde;o");
            mysql_select_db(self::bancoDeDados) or die("N&atilde;o foi poss&iacute;vel selecionar o banco de dados<br />Entre em contato com seu administrador ou, se voc&ecirc; &eacute; o administrador, confira os dados de configura&ccedil;&atilde;o");

        }
        public function select_all(){
            //BUSCO A SAFRA CORRENTE
            
            $sqlSafra = "select * from service";
            $qrySafra = mysql_query($sqlSafra);
            $rowSafra = mysql_fetch_array($qrySafra);
            return $rowSafra;
            //desalocando as variáveis da memória por questão de boas práticas
            unset($sqlSafra);
            unset($qrySafra);
            unset($rowSafra);
        }
        public function select_id($id = 0){
            $sqlLevante = "select * from service where id = ".$id;
            $qryLevante = mysql_query($sqlLevante);
            $rowLevante = mysql_fetch_array($qryLevante);
            return $rowLevante;
            unset($sqlLevante);
            unset($qryLevante);
            unset($rowLevante);
        }
        //função auxiliar pra uso de data
        public function aaaammdd_ddmmaaaa($aaaa_mm_dd) {
            $axdia = substr($aaaa_mm_dd, 8, 2);
            $axmes = substr($aaaa_mm_dd, 5, 2);
            $axano = substr($aaaa_mm_dd, 0, 4);
            $dd_mm_aaaa = $axdia . "/" . $axmes . "/" . $axano;
            return $dd_mm_aaaa;
        }

	}

}

?>