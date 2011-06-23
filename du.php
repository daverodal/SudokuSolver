<?php
$unity = array(1,2,3,4,5,6,7,8,9);
$x = 1;
function topSolve(board $b){
	while($b->depth < 10){
		solveIt($b);
		if($b->getCount() == 0)
			return;
		$b->depth++;
	}
}
function solveIt(board $b){
$spinning = 0;
$tryHard = false;

	while($b->getCount() > 0){
	for($XXX = 1; $XXX <= 9;$XXX++){
		if(solveCol($XXX,$b)){
			$spinning = 0;
			$tryHard = false;
		}
		if($b->invalid === true){
			return;
		}
	}
	for($YYY = 1; $YYY <= 9;$YYY++){
		if(solveRow($YYY,$b)){
			$spinning = 0;
			$tryHard = false;
		}
		if($b->invalid === true){
			return;
		}
	}
	if($spinning++ > 0){
		return;
	}
	}
}
function solveCol($XXX,$b){
	global $unity;
	// First try to see if a number can only be on one place.
	$filled = $b->getFilledCol($XXX);
	$eaddrs = $b->getEmptyColAddrs($XXX);
	$testvals = array_diff($unity, $filled);
	foreach($testvals as $testval){
	$thistry = 0;
	$tryaddr = array();
		foreach($eaddrs as $eaddr){
			$testrow = $b->getFilledRow($eaddr);
			$testsect = $b->getFilledSect($XXX, $eaddr);
			//print "Fun X $XXX tv $testval ea $eaddr ".print_r($testrow,true)." ".print_r($testsect,true)." \n";
			if(count(array_intersect($testrow, array($testval)) ) == 0){
			if(count(array_intersect($testsect, array($testval)) ) == 0){
				//print "ThisTry $eaddr ".print_r($testrow,true)." $testval\n";
				$thistry++;
				$tryaddr[] = $eaddr;
			}
			}
		}
		if($thistry == 0){
			$b->invalid = true;
			return false;
		}
		if($thistry == 1){
			$b->setCell($XXX,$tryaddr[0],$testval);
			//print "SetColtop $XXX, $tryaddr, $testval\n";
			return true;
		}else if($b->depth > 0 && $thistry > 1){
			//print "1This is Hard $XXX $testval $thistry ".$b->depth."<br>";
			flush();
			ob_flush();
			foreach($tryaddr as $try){
				if($b->depth == 0){
					break;
				}
				$newBoard = new board();
				$newBoard->depth = $b->depth-1;
				$newBoard->setBoard($b);
//print "ColTop Trying $testval at $XXX $try<br>";
				$newBoard->setCell($XXX,$try,$testval);
//displayBoard($newBoard->getBoard());
				solveIt($newBoard);
				//print "Tryed one ".$newBoard->getCount()." ".$newBoard->invalid."<br>";
//displayBoard($newBoard->getBoard());
				if($newBoard->getCount() == 0){
					$newBoard->depth = $b->depth;
					$b->setBoard($newBoard);
					return true;
				}
			}
		}
	}
	// Next see if only this number can be here
	$filled = $b->getFilledCol($XXX);
	$eaddrs = $b->getEmptyColAddrs($XXX);
	$testvals = array_diff($unity, $filled);
	foreach($eaddrs as $eaddr){
	$tryval = array();
	$thistry = 0;
		foreach($testvals as $testval){
			$testrow = $b->getFilledRow($eaddr);
			$testsect = $b->getFilledSect($XXX, $eaddr);
			//print "Fun $testval $XXX $eaddr ".print_r($testrow,true)." \n";
			if(count(array_intersect($testrow, array($testval)) ) == 0){
			if(count(array_intersect($testsect, array($testval)) ) == 0){
				//print "ThisTry $eaddr ".print_r($testrow,true)." $testval\n";
				$thistry++;
				$tryval[] = $testval;
			}
			}
		}
		if($thistry == 0){
			$b->invalid = true;
			return false;
		}
		if($thistry == 1){
			$b->setCell($XXX,$eaddr,$tryval[0]);
			//print "SetColbot $XXX, $eaddr, $tryval\n";
			return true;
		}else if($b->depth > 0 && $thistry > 1){
			//print "2This is Hard ".$b->depth."<br>";
			foreach($tryval as $val){
				if($b->depth == 0){
					break;
				}
				$newBoard = new board();
				$newBoard->depth = $b->depth-1;
				$newBoard->setBoard($b);
//print "ColBot Trying $val at $XXX $eaddr<br>";
				$newBoard->setCell($XXX,$eaddr,$val);
				solveIt($newBoard);
				//print "Tryed one ".$newBoard->getCount()." ".$newBoard->invalid."<br>";
				if($newBoard->getCount() == 0){
					$newBoard->depth = $b->depth;
					$b->setBoard($newBoard);
					return true;
				}
			}

		}
	}
	return false;
}
function solveRow($YYY,$b){
//print "Solverow $YYY\n";
ob_flush();
	global $unity;
	$changed = false;
	$filled = $b->getFilledRow($YYY);
	$eaddrs = $b->getEmptyRowAddrs($YYY);
	$testvals = array_diff($unity, $filled);
	foreach($testvals as $testval){
	$thistry = 0;
	$tryaddr = array();
		foreach($eaddrs as $eaddr){
			$testcol = $b->getFilledCol($eaddr);
			$testsect = $b->getFilledSect($eaddr,$YYY);
			//print "Fun Y $YYY tv $testval ea $eaddr ".print_r($testrow,true)." ".print_r($testsect,true)." \n";
ob_flush();
			if(count(array_intersect($testcol, array($testval)) ) == 0){
			if(count(array_intersect($testsect, array($testval)) ) == 0){
				//print "ThisTry $eaddr ".print_r($testcol,true)." $testval\n";
ob_flush();
				$thistry++;
				$tryaddr[] = $eaddr;
			}
			}
		}
		if($thistry == 0){
			$b->invalid = true;
			return false;
		}
		if($thistry == 1){
			$b->setCell($tryaddr[0],$YYY,$testval);
			//print "SetRowtop $tryaddr, $YYY $testval\n";
			$changed = true;
			return $changed;
		}else if($b->depth > 0 && $thistry > 1){
			//print "3This is Hard ".$b->depth."<br>";
			flush();
			ob_flush();
			foreach($tryaddr as $try){
				if($b->depth == 0){
					break;
				}
				$newBoard = new board();
				$newBoard->depth = $b->depth-1;
				$newBoard->setBoard($b);
//print "Trying $testval at $try $YYY<br>";
				$newBoard->setCell($try,$YYY,$testval);
				solveIt($newBoard);
				print "Tryed one $try $YYY $testval ".$newBoard->getCount()." ".$newBoard->invalid."<br>";
				if($newBoard->getCount() == 0){
					$newBoard->depth = $b->depth;
					$b->setBoard($newBoard);
					return true;
				}
			}
		}
	}
	$filled = $b->getFilledRow($YYY);
	$eaddrs = $b->getEmptyRowAddrs($YYY);
	$testvals = array_diff($unity, $filled);
	foreach($eaddrs as $eaddr){
	$thistry = 0;
	$tryval = array();
		foreach($testvals as $testval){
			$testcol = $b->getFilledCol($eaddr);
			$testsect = $b->getFilledSect($eaddr,$YYY);
			//print "Fun $eaddr ".print_r($testcol,true)." \n";
			if(count(array_intersect($testcol, array($testval)) ) == 0){
			if(count(array_intersect($testsect, array($testval)) ) == 0){
				//print "ThisTry $eaddr ".print_r($testcol,true)." $testval\n";
				$tryval[] = $testval;
				$thistry++;
			}
			}
		}
		if($thistry == 0){
			$b->invalid = true;
			return false;
		}
		if($thistry == 1){
			$b->setCell($eaddr,$YYY,$tryval[0]);
			//print "SetRowbot $eaddr, $YYY $tryval\n";
			$changed = true;
			return $changed;
		}else if($b->depth > 0 && $thistry > 1){
			//print "4This is Hard $YYY $eaddr ".$b->depth."<br>";
			foreach($tryval as $val){
				if($b->depth == 0){
					break;
				}
				$newBoard = new board();
				$newBoard->depth = $b->depth-1;
				$newBoard->setBoard($b);
//print "Trying $val at $eaddr $YYY<br>";
				$newBoard->setCell($eaddr,$YYY,$val);
				solveIt($newBoard);
				print "Tryed one ".$newBoard->getCount()." ".$newBoard->invalid."<br>";
				if($newBoard->getCount() == 0){
					$newBoard->depth = $b->depth;
					$b->setBoard($newBoard);
					return true;
				}
			}
		}
	}
	return $changed;
}
			

function displayBoard($board){
	for($i = 1;$i <= 9;$i++){
		for($j = 1;$j <= 9;$j++){
			if($board[$j][$i] == '')
				print " x ";
			else
				print " ".$board[$j][$i]." ";
		}
		print "<br>";
	}
		
}

class board{
	public $invalid = false;
	public $depth = 0;
	private $myBoard;
	private $X = 9;
	private $Y = 9;
	private $myCount = 81;

	public function setBoard(board $board){
		$this->myBoard = $board->myBoard;
		$this->myCount = $board->myCount;
	}
	function __construct(){
	for($i = 1;$i <= $this->X;$i++)
	for($j = 1;$j <= $this->Y;$j++)
		$this->myBoard[$i][$j] = '';
	}
	private function validate($x,$y){
		if($x <= 0 || $x > $this->X || $y <=0 || $y > $this->Y){
			throw new Exception("Invalid GetCell $x $y");
		}
	}
	public function getBoard(){
		return $this->myBoard;
	}
	public function getCell($x,$y){
		$this->validate($x,$y);
		return $this->myBoard[$x][$y];
	}
	public function setCell($x,$y,$val){
		if($this->myBoard[$x][$y] != ''){
			throw new Exception("Trying to set $x $y ".$this->myBoard[$x][$y]." with $val");
		}
		$this->validate($x,$y);
		$this->myBoard[$x][$y] = $val;
		$this->myCount--;
	}
	public function getFilledSect($x,$y){
		$this->validate($x,$y);
		$ret = array();
		$x--;
		$y--;
		$sx = intval($x/3); // kluge
		$sy = intval($y/3); // kluge
		$sx *= 3;
		$sy *= 3;
		for($i = 1; $i <= $this->X/3;$i++){
		for($j = 1; $j <= $this->X/3;$j++){
			if($this->myBoard[$i + $sx][$j + $sy] != ''){
				$ret[] = $this->myBoard[$i + $sx][$j + $sy];
				//print "I $i sx $sx j $j sy $sy ".$this->myBoard[$i + $sx][$j + $sy]."\n";
			}
		}
		}
		//print "SECT $x $y ".print_r($ret,true);
		return $ret;
	}
	public function getFilledRow($y){
		$this->validate(1,$y);
		$ret = array();
		for($i = 1; $i <= $this->X;$i++){
			if($this->myBoard[$i][$y] != ''){
				//print "i $i y $y my ".$this->myBoard[$i][$y]."\n";
				$ret[] = $this->myBoard[$i][$y];
			}
		}
		return $ret;
	}
	public function getFilledCol($x){
		$this->validate($x,1);
		$ret = array();
		for($i = 1; $i <= $this->Y;$i++){
			if($this->myBoard[$x][$i] != '')
				$ret[] = $this->myBoard[$x][$i];
		}
		return $ret;
	}
	public function getEmptyColAddrs($x){
		$this->validate($x,1);
		$ret = array();
		for($i = 1; $i <= $this->Y;$i++){
			if($this->myBoard[$x][$i] == '')
				$ret[] = $i;
		}
		return $ret;
	}
	public function getEmptyRowAddrs($y){
		$this->validate(1,$y);
		$ret = array();
		for($i = 1; $i <= $this->X;$i++){
			if($this->myBoard[$i][$y] == '')
				$ret[] = $i;
		}
		return $ret;
	}
	public function getCount(){
		return $this->myCount;
		$ret = 0;
		for($i = 1;$i <= $this->X;$i++){
			for($j = 1;$j <= $this->Y;$j++){
				if($this->myBoard[$i][$j] == ''){
				$ret++;
				}
			}
		}
		return $ret;
	}
}
?>
