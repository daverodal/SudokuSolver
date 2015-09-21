<html><head>
<title>Solve a sudoku puzzle -- DavidRodal.com</title>
<style type="text/css">
.x11{border-width:4px 0px 0px 4px;}
.x21{border-width:4px 0px 0px 0px;}
.x01{border-width:4px 4px 0px 0px;}
.x12{border-width:0px 0px 0px 4px;}
.x22{border-width:0px 0px 0px 0px;}
.x02{border-width:0px 4px 0px 0px;}
.x10{border-width:0px 0px 4px 4px;}
.x20{border-width:0px 0px 4px 0px;}
.x00{border-width:0px 4px 4px 0px;}
</style>
</head><body>
Solve a sudoku puzzle.<br>
Enter the numbers of the puzzle, or the solution as far as you have
gotten, and push solve.
<?php
/*
 * there are not enough comments, this one doesn't count
 */
require_once "du.php";
	$cell = array(array());
	if($_GET["solve"] == 1){
		$b = new board();
		$cell = $_GET["cell"];
		for($i = 1;$i <= 9;$i++){
		for($j = 1;$j <= 9;$j++){
			if($cell[$i][$j] != '')
				$b->setCell($i,$j,$cell[$i][$j]);
		}
		}
		topSolve($b);
		if($b->depth > 0)
			print "That was hard ".$b->depth;
		else
			print "That was easy";
		$cell = $b->getBoard();
		if($b->getCount() != 0){
			print "Unsolved, ".$b->getCount()." Remaining<br>";
		}
		if($b->invalid){
			print "Invalid Board<br>";
		}
	}
?>
<form method=GET>
<?php
	print "<table border=4>\n";
	for($i = 1;$i <= 9;$i++){
		print "<tr>";
	for($j = 1;$j <= 9;$j++){
		$x = $j%3;
		$y = $i%3;
		print "<td class=\"x$x$y\">";
		print "<input size=2 type=text name=\"cell[$j][$i]\" value=\"".$cell[$j][$i]."\">";
		//print "1";
		print "</td>\n";
	}
		print "</tr>\n";
	}
	print "</table>\n";
?>
<input type="hidden" name="solve" value="1">
<input type="submit" value="Solve">
<input type="reset" value="Reset">
</form>
<a href="ku.php">start again</a>
<br><br>
Web sudoku puzzles can  be found on line at:<br>
<a href="http://www.sudoku.com">www.sudoku.com</a><br>
<a href="http://www.websudoku.com">www.websudoku.com</a><br>
And many other places on the web.
</body>
</html>
