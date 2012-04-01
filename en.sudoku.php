<?php //run with php5.3.8 or compatible
/**
 * 
 * @author Proto <live.proto@hotmail.com>
 * @description Code to check a sudoku's table without interface
 * 
 */
 
$sudoku = <<<EOF
835416927
296857431
417293658
569134782
123678549
748529163
652781394
981345276
374962815
EOF;

final class Checker
{
	private $total = 45;//sum(1..9)
	private $repository = array(
							0 => false,
							1 => false,
							2 => false,
							3 => false,
							4 => false,
							5 => false,
							6 => false,
							7 => false,
							8 => false,
							9 => false
							);
							
	public function Add($value)
	{
		if (!$this->repository[$value])
		{
			$this->repository[$value] = true;
			$this->total -= $value;
		}
	}
	
	public function isValid()
	{
		if ($this->total == 0)
			return true;
		return false;
	}
}
		
function CreateMatrix(&$sudokuText)
{
	$sudokuMatrix = explode("\n",$sudokuText);//explode em arrays com linhas de strings
	
	foreach($sudokuMatrix as $line_num => $line)
		$sudokuMatrix[$line_num] = str_split(trim($line));//converte a linha de string para array de chars
	
	$sudokuText = $sudokuMatrix;
}

function SudokuLine($line)
{
	global $sudoku;
	
	if($line < 1 || $line > 9)
		return false;
		
	$line--;//decremento para não ser um offset invalido
	
	$checker = new Checker();
	
	for($i = 0; $i < sizeof($sudoku[$line]); $i++)
		$checker->Add($sudoku[$line][$i]);
	
	return $checker->isValid();
}

function SudokuColumn($col)
{
	global $sudoku;
	
	if($col < 1 || $col > 9)
		return false;
	
	$col--;
	
	$checker = new Checker();
	
	for($i = 0; $i < sizeof($sudoku); $i++)
		$checker->Add($sudoku[$i][$col]);
	
	return $checker->isValid();
}

function SudokuRegion($region)
{
	global $sudoku;
	
	
	$map[0] = array(0,0);
	$map[1] = array(0,3);
	$map[2] = array(0,6);
	
	$map[3] = array(3,0);
	$map[4] = array(3,3);
	$map[5] = array(3,6);
	
	$map[6] = array(6,0);
	$map[7] = array(6,3);
	$map[8] = array(6,6);
		
		
	if($region < 1 || $region > 9)
		return false;
	
	$region--;
	
	$checker = new Checker();

	$line = $map[$region][0];
	$col = $map[$region][1];
	
	for($i = $line; $i < $line + 3; $i++)
		for($j = $col; $j < $col + 3; $j++)
			$checker->Add($sudoku[$i][$j]);
	
	return $checker->isValid();
}

function SudokuFullCheck()
{
	global $sudoku;
	
	$valid = true;
	
	function CheckLine($line,&$valid)
	{
		if(!SudokuLine($line))
			$valid = false;
	}
	function CheckColumn($col,&$valid)
	{
		if(!SudokuColumn($col))
			$valid = false;
	}
	function CheckRegion($region,&$valid)
	{
		if(!SudokuRegion($region))
			$valid = false;
	}
	
	for($i = 0; $i < sizeof($sudoku); $i++)
	{
		if($i == 0)
			for($j = 0; $j < sizeof($sudoku[$i]); $j++)
				CheckColumn($j+1,$valid);

		CheckLine($i+1,$valid);
		CheckRegion($i+1,$valid);
	}
	
	return $valid;
}

CreateMatrix($sudoku);
var_dump($sudoku);
var_dump(SudokuLine(1));
var_dump(SudokuColumn(9));
var_dump(SudokuRegion(8));
var_dump(SudokuFullCheck());