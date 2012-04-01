<?php
/**
 * 
 * @author Proto <live.proto@hotmail.com>
 * @description Codigo de calendario com sessao com interface
 * 
 */
 
class html{//classe que monta html
	private $mes;
	private $ano;
	public $result;
	
	public function __construct(opTime $obj){//recebe somente a entrada de um objeto 'opTime'
		$this->ano = $obj->ano;
		$this->setMes($obj->mes);
		$this->generateTable($obj->dias);
		}
	private function setMes($int){//setar nome do mês
		$meses = array(	'janeiro',
						'fevereiro',
						'março',
						'abril',
						'maio',
						'junho',
						'julho',
						'agosto',
						'setembro',
						'outubro',
						'novembro',
						'dezembro');

		$this->mes = $meses[$int-1];
		}
	private function generateTable($array){//gerador de celulas da tabela html
		$out = true;
		$start = $array['antes'][0];
		$stop = $array['antes'][1];
		$total = $array['total'];
		$hoje =  $array['hoje'];
		
		$v = $start;
		$l = $stop;
		
		$days_grid = '';
		$i = 0;
		for($y = 0; $y < 6; $y++ ){//gerador das células com os dias
			$days_grid .= "\t\t\t\t<tr>\n";
			for($x = 0; $x < 7; $x++){
				if($v > $l){
					$out = !$out;
					$v = 1;
					$l = $total;
					}
				$dom = ($x == 0) ? 'dom' : '';
				$out = ($out) ? 'out' : '';
				$class = (!empty($dom) && !empty($out)) ? $dom.' '.$out : $dom.$out;
				$id = ($i == $hoje) ? ' id="hoje"' : '';
				$class = (!empty($class)) ? " class=\"{$class}\"" : '';
				$days_grid .= "\t\t\t\t\t<td{$id}{$class}>{$v}</td>\n";
				$v++;
				$i++;
				}
			$days_grid .= "\t\t\t\t</tr>\n";
			}
		$header  = "\t\t\t\t<tr>\n";//células com a label dos dias da semana
		$header .= "\t\t\t\t\t<th class=\"dom\">d</th>\n";
		$header .= "\t\t\t\t\t<th>s</th>\n";
		$header .= "\t\t\t\t\t<th>t</th>\n";
		$header .= "\t\t\t\t\t<th>q</th>\n";
		$header .= "\t\t\t\t\t<th>q</th>\n";
		$header .= "\t\t\t\t\t<th>s</th>\n";
		$header .= "\t\t\t\t\t<th>s</th>\n";		
		$header .= "\t\t\t\t</tr>\n";
		$nav = '';
		for($i = 0; $i < 2; $i++){//gerador dos menus de ano e mês
			$tp = !(isset($tp)) ? 'ano' : $tp;
			$nav .= "\t\t\t\t<tr id=\"{$tp}\">\n";
			$nav .= "\t\t\t\t\t<td><button name=\"{$tp}\" value=\"-\">&lt;</button></td>\n";
			$nav .= "\t\t\t\t\t<td colspan=\"5\">".$this->{$tp}."</td>\n";
			$nav .= "\t\t\t\t\t<td><button name=\"{$tp}\" value=\"+\">&gt;</button></td>\n";
			$nav .= "\t\t\t\t</tr>\n";
			$tp = 'mes';
			}
		$footer  = "\t\t\t\t<tr id=\"atual\">\n";//botão do dia atual
		$footer .= "\t\t\t\t\t<td colspan=\"7\"><button name=\"dia\" value=\"hoje\">data atual</button></td>\n";
		$footer .= "\t\t\t\t</tr>\n";
		$content = $nav;
		$content .= $header;
		$content .= $days_grid;
		$content .= $footer;
		$this->result = $content;
		}
	}
class opTime{//classe que opera dados de data
	public $dias;//array com todas as informações sobre os dias a serem processados pela classe html
	public $mes;//mes de exibição
	public $ano;//ano de exibição

	private $dia_atual;
	private $mes_atual;
	private $ano_atual;

	public function __construct(){//seta a data atual para calculos
		$this->mes_atual = (int)date('n');
		$this->ano_atual = (int)date('Y');
		$this->dia_atual = (int)date('d');
		$this->sessionCheck();
		$this->verify();
		$this->generateDays();
		}
	private function sessionCheck(){//verifica sessão salva
		session_start();
		if(isset($_SESSION['mes']) && isset($_SESSION['ano'])){
			$this->mes = $_SESSION['mes'];
			$this->ano = $_SESSION['ano'];
		}else{
			$this->mes = $this->mes_atual;
			$this->ano = $this->ano_atual;
			$_SESSION['mes'] = $this->mes_atual;
			$_SESSION['ano'] = $this->ano_atual;
			}
		}
	private function sessionSave(){//salva sessão com o ano e o mês
		$_SESSION['mes'] = $this->mes;
		$_SESSION['ano'] = $this->ano;
		}
	private function verify(){//verifica a dados válidos de data
		$params = array('dia','mes','ano');
		if($_POST){
			$key = key($_POST);
			if(in_array($key,$params)){
				switch($_POST[$key]){
					case '+':
						$this->{$key}++;
						break;
					case '-':
						$this->{$key}--;
						break;	
					case 'hoje':
						$this->mes = $this->mes_atual;
						$this->ano = $this->ano_atual;
						break;	
					}
					if($this->mes > 12){
						$this->ano++;
						$this->mes -= 12;
						}
					if($this->mes < 1){
						$this->ano--;
						$this->mes += 12;
						}
					if($this->ano < 1950){
						$this->ano = 1950;
						}
					$this->sessionSave();
				}
			}
		}
	private function generateDays(){//gera dados finais e precisos para a exibição
		$total_dias = cal_days_in_month(CAL_GREGORIAN, $this->mes, $this->ano);
		$inicio = date("w", mktime(0,0,0,$this->mes,1,$this->ano));
		$inicio =  $inicio > 0 ? $inicio : 7;
		$fim =  date("w", mktime(0,0,0,$this->mes,$total_dias,$this->ano));
		$dias = array();
		
		$dias['depois'] = 0;
		if($inicio > 0){
			if($this->mes-1 < 1){
				$t_mes = 12;
				$t_ano = $this->ano-1;
			}else{
				$t_mes = $this->mes-1;
				$t_ano = $this->ano;
				}
			$total_dias_ex = cal_days_in_month(CAL_GREGORIAN, $t_mes, $t_ano);
			$dias['antes'][0] = $total_dias_ex - $inicio+1;//sequencia de numeros no mes de antes, pedaco final
			$dias['antes'][1] = $total_dias_ex;
			$dias['depois'] = $dias['antes'][1] - $dias['antes'][0]+1;
			}
		$dias['total'] = $total_dias;
		$dias['depois'] = 42 - ($dias['depois'] + $dias['total']);//sequencia de numeros no mes de antes, pedaco final 
		
		if($this->ano_atual == $this->ano){
			switch($this->mes_atual){
				case $this->mes:
					$hoje = (isset($dias['antes']))? ($dias['antes'][1] - $dias['antes'][0]) + $this->dia_atual : $this->dia_atual;
					break;
				case $this->mes+1:
					if($dias['depois'] > 0 && $this->dia_atual <= $dias['depois']){
						$hoje = (isset($dias['antes'])) ? ($dias['antes'][1] - $dias['antes'][0])+ $dias['total'] + $this->dia_atual : $dias['total'] + $this->dia_atual;
						}
					break;
				case $this->mes-1:
					if(isset($dias['antes']) && ($this->dia_atual >= $dias['antes'][0] && $this->dia_atual <= $dias['antes'][1])){
						$hoje = $this->dia_atual - $dias['antes'][0];
						}
					break;
				}
			}
		$dias['hoje'] = (isset($hoje) ? $hoje+1 : 0)-1;//indice do vetor
		$this->dias = $dias;
		}
	}
$t = new opTime();
$table = new html($t);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Calendário</title>
		<style type="text/css">
			*, html, body {
				margin:0;
				padding:0;
				}
			body{
				font-family:Arial, Helvetica, sans-serif;
				background-color:#555;
				color:#FFF;
				}
			form{
				display:block;
				margin: 0 auto 0 auto;
				width:566px;
				}
			table{
				width:566px;
				padding:0;
				border-collapse: separate;
				border-spacing:1px;
				}
			td,th{
				height:60px;
				text-align:center;
				vertical-align:middle;
				text-transform:uppercase;
				font-size:3.1em;
				padding:0;
				margin:0;
				}
			td{
				background-color:#29ABE2
				}
			th{
				background-color:#CCCCCC;
				width:80px;
				}
			button{
				display:block;
				border: 0 none;
				height:60px;
				width:100%;
				text-transform:uppercase;
				font-size:1em;
				color:#FFF;
				cursor:pointer;
				}
			
			#ano td{
				background-color:#FACB20;
				}
			#ano button{
				background-color:#F8B622;
				}
			#mes td{
				background-color:#F7931E;
				}
			#mes button{
				background-color:#F15A24;
				}
			#atual button{
				background-color:#F15A24;
				}
			#ano button:hover, #mes button:hover, #atual button:hover{
				background-color:#FF0;
				color:#F60;
				}
			.dom{
				color:#FBB03B;
				}
			th.dom{
				background-color:#B3B3B3;
				}
			td.dom{
				background-color:#0071BC;
				}
			td.out {
				background-color:#E6E6E6;
				}
			#hoje{
				background-color:#000;
				color:#FF0;
				}
			
		</style>
	</head>
	<body>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
			<table>
<?php echo $table->result;?>
			</table>
		</form>
	</body>
</html>