<?php
/**
 * 
 * @author Proto <live.proto@hotmail.com>
 * @description Codigo de caca palavras sem interface
 * 
 */
	 
function hunter($y,$x,$matrix,$cmatrix,$palavra,$mAlt,$mComp){
	
	//formato de busca: 8 direcoes
	//7 0 1
	//6 x 2	
	//5 4 3
		
	$pTam = strlen($palavra)-1;//tamanho da palavra
	$z = $pTam; //tamanho temporario
	$mTemp = $cmatrix; //matrix temporaria
	$i = 0;
	$chave = $palavra[$z];//letra da palavra
	while( $i < 8){	
		$match = false;
		switch($i){
			case 0://decrementar y
				if(!($pTam > $y) && ($chave == $matrix[$a = $y-$z][$b = $x])) $match = true;
				break;
			case 1://decrementar y incrementar x
				if(!($pTam > $y || $pTam >= $mComp-$x) && ($chave == $matrix[$a = $y-$z][$b = $x+$z])) $match = true;
				break;
			case 2://incrementar x
				if(!($pTam >= $mComp-$x) && ($chave == $matrix[$a = $y][$b = $x+$z])) $match = true;
				break;
			case 3://incrementar x e y
				if(!($pTam >= $mAlt-$y || $pTam >= $mComp-$x) && ($chave == $matrix[$a = $y+$z][$b = $x+$z])) $match = true;
				break;
			case 4://incrementar y
				if(!($pTam >= $mAlt-$y) && ($chave == $matrix[$a = $y+$z][$b = $x])) $match = true;
				break;
			case 5://incrementar y e decrementar x
				if(!($pTam >= $mAlt-$y || $pTam > $x) && ($chave == $matrix[$a = $y+$z][$b = $x-$z])) $match = true;
				break;
			case 6://decrementar x
				if(!($pTam >= $x) && ($chave == $matrix[$a = $y][$b = $x-$z])) $match = true;
				break;
			case 7://decrementar y e x
				if(!($pTam > $y || $pTam > $x) && ($chave == $matrix[$a = $y-$z][$b = $x-$z])) $match = true;
				break;
			}
		if($match){
			$retorno = true;
			$direcao = $i;
			$z--;
			$mTemp[$a][$b] = $chave;
			if($z > 0){
				$chave = $palavra[$z];
			}else{
				$i = 8;//finalizar hunter()
				}
		}else{
			$mTemp = $cmatrix;
			$retorno = false;
			$z = $pTam;
			$chave = $palavra[$z];
			$i++;
			}
		}
	if($retorno){
		unset($retorno);
		$retorno = $mTemp;
		}
	return $retorno;
	}

function achaPalavras($matrix, $palavras){
	$mSize = sizeof($matrix);
	$mLeng = is_array($matrix[0])? sizeof($matrix[0]) : strlen($matrix[0]);
	$cmatrix = $matrix;
	for($i = 0; $i < $mSize; $i++){//gerar matrix temporaria em asteristicos
		for($j = 0; $j < $mLeng; $j++){
			$cmatrix[$i][$j] = '*';
			}
		}
	foreach($palavras as $palavra){
		$achou = false;
		$i = 0; 
		while($i < $mSize && !$achou){
			$j = 0;
			while( $j < $mLeng  && !$achou){
				if($palavra[0]==$matrix[$i][$j] && (($w = hunter($i,$j,$matrix,$cmatrix,$palavra,$mSize,$mLeng))!=false)){
					$cmatrix = $w;
					$cmatrix[$i][$j] = $palavra[0];
					$achou = true;
					}
				$j++;	
				}
			$i++;
			}
		}
	return $cmatrix;
	}
	
$matrix[0]	=  'xbmgxlvfmgc';
$matrix[1]	=  'etetvbarapj';
$matrix[2]	=  'itlgwjnzrxp';
$matrix[3]	=  'juacnpaoafv';
$matrix[4]	=  'lgocbjnqcwo';
$matrix[5]	=  'wxgpatahuhj';
$matrix[6]	=  'qonmxbbojkn';
$matrix[7]	=  'weajtdanapo';
$matrix[8]	=  'tqtangerina';
$matrix[9]	=  'wjmorangook';
$matrix[10]	=  'fkrudybmopb';

$palavras = array('banana','morango','abacate','maracuja','melao','tangerina');

$matrix = achaPalavras($matrix,$palavras);

echo "<pre>\n";
for($i = 0; $i < sizeof($matrix); $i++){
	if(is_array($matrix[$i])){
		for($j = 0; $j < sizeof($matrix[0]); $j++){
			echo $matrix[$i][$j];
			}	
	}else{
		echo $matrix[$i];	
		}
	echo "\n";
	}
echo "</pre>";
?>