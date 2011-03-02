<?php

function rotateTable45($aTable1) {

	$aTable2 = array();

	$aTable2[1][3] = $aTable1[1][1];
	$aTable2[2][3] = $aTable1[1][2]; 
	$aTable2[3][3] = $aTable1[1][3];
	$aTable2[3][2] = $aTable1[2][3]; 
	$aTable2[3][1] = $aTable1[3][3]; 
	$aTable2[2][1] = $aTable1[3][2]; 
	$aTable2[1][1] = $aTable1[3][1];
	$aTable2[1][2] = $aTable1[2][1];	
	$aTable2[2][2] = $aTable1[2][2];
	
	return $aTable2;
}


function processGameTable($aTable, $sOperation = 'verticalSymmetry') {

	$aNewTable = $aTable;

	switch ($sOperation) {
		case 'verticalSymmetry':
			$aNewTable[1][1] = $aTable[1][3]; $aNewTable[2][1] = $aTable[2][3];	$aNewTable[3][1] = $aTable[3][3];
			$aNewTable[1][3] = $aTable[1][1]; $aNewTable[2][3] = $aTable[2][1]; $aNewTable[3][3] = $aTable[3][1];
		break;

		case 'horizontalSymmetry':
			$aNewTable[1][1] = $aTable[3][1]; $aNewTable[1][2] = $aTable[3][2]; $aNewTable[1][3] = $aTable[3][3];
			$aNewTable[3][1] = $aTable[1][1]; $aNewTable[3][2] = $aTable[1][2]; $aNewTable[3][3] = $aTable[1][3];
		break;
		case 'firstDiagonalSymmetry':
			$aNewTable[1][2] = $aTable[2][1]; $aNewTable[1][3] = $aTable[3][1]; $aNewTable[2][3] = $aTable[3][2];
			$aNewTable[2][1] = $aTable[1][2]; $aNewTable[3][1] = $aTable[1][3]; $aNewTable[3][2] = $aTable[2][3];
		break;
		case 'secondDiagonalSymmetry':
			$aNewTable[1][1] = $aTable[3][3]; $aNewTable[1][2] = $aTable[2][3];	$aNewTable[2][1] = $aTable[3][2];
			$aNewTable[3][3] = $aTable[1][1]; $aNewTable[2][3] = $aTable[1][2];	$aNewTable[3][2] = $aTable[2][1];
		break;
		case 'rotate45':
			$aNewTable = rotateTable45($aTable);		
		break;
		case 'rotate90':
			$aNewTable = rotateTable45($aTable);
			$aNewTable = rotateTable45($aNewTable);
		break;
		case 'rotate135':
			$aNewTable = rotateTable45($aTable);
			$aNewTable = rotateTable45($aNewTable);
			$aNewTable = rotateTable45($aNewTable);
		break;
	}
	
	return $aNewTable;
}

function testIdenticalTables($aTable1, $aTable2) {
	$bIsIdentical = true;
	for ($i = 1; $i <= 3; ++$i) {
		for ($j = 1; $j <= 3; ++$j) {
			if ($aTable1[$i][$j] != $aTable2[$i][$j]) {
				$bIsIdentical = false;
				break 2;
			}
		}
	}
	
	return $bIsIdentical;
}

function testSymmetry($aMoveToTestTable, $aSavedMovedTable) {
	$bIsSymmetric = $sSymmetryType = false;
	if (testIdenticalTables(processGameTable($aMoveToTestTable, 'verticalSymmetry'), $aSavedMovedTable)) {
		// vertical symmetry
		$bIsSymmetric = true;
		$sSymmetryType = 'verticalSymmetry';
	}
	elseif (testIdenticalTables(processGameTable($aMoveToTestTable, 'horizontalSymmetry'), $aSavedMovedTable)) {
		// horizontal symmetry
		$bIsSymmetric = true;
		$sSymmetryType = 'horizontalSymmetry';
	}
	elseif (testIdenticalTables(processGameTable($aMoveToTestTable, 'firstDiagonalSymmetry'), $aSavedMovedTable)) {
		// first diagonal symmetry
		$bIsSymmetric = true;
		$sSymmetryType = 'firstDiagonalSymmetry';
	}
	elseif (testIdenticalTables(processGameTable($aMoveToTestTable, 'secondDiagonalSymmetry'), $aSavedMovedTable)) {
		// second diagonal symmetry
		$bIsSymmetric = true;
		$sSymmetryType = 'secondDiagonalSymmetry';
	}
	elseif (testIdenticalTables(processGameTable($aMoveToTestTable, 'rotate45'), $aSavedMovedTable)) {
		// rotate45 symmetry
		$bIsSymmetric = true;
		$sSymmetryType = 'rotate45';
	}
	elseif (testIdenticalTables(processGameTable($aMoveToTestTable, 'rotate90'), $aSavedMovedTable)) {
		// rotate90 symmetry
		$bIsSymmetric = true;
		$sSymmetryType = 'rotate45';
	}
	elseif (testIdenticalTables(processGameTable($aMoveToTestTable, 'rotate135'), $aSavedMovedTable)) {
		// rotate135 symmetry
		$bIsSymmetric = true;
		$sSymmetryType = 'rotate45';
	}
	
	return array($bIsSymmetric, $sSymmetryType);
}




function generateBoard($aXPositions, $aOPositions) {
	for ($i = 1; $i <= 3; ++$i) {
		for ($j = 1; $j <= 3; ++$j) {
			$aTable[$i][$j] = 0;
		}
	}
	
	foreach ($aXPositions as $key => $value) {
		$aTable[floor(($value - 1) / 3) + 1][($value - 1) % 3 + 1] = 1;
	}

	foreach ($aOPositions as $key => $value) {
		$aTable[floor(($value - 1) / 3) + 1][($value - 1) % 3 + 1] = 2;
	}
	
	return $aTable;
} 

function showBoard($aTable) {
	echo "\n";

	for ($i = 1; $i <= 3; ++$i) {
		for ($j = 1; $j <= 3; ++$j) {
			if ($aTable[$i][$j]) {
				if ($aTable[$i][$j] == 1)
					echo "X";
				else
					echo "0";
			}
			else {
				echo " ";
			}
			if ($j < 3)
				echo " | ";
		}
		echo "\n";
	}
}

function testEndGame($aTable) {
	
	$i = 1;
	$nWinner = 0;
	
	while ($i <= 2 && !$nWinner) {
		if ($aTable[1][1] == $i && $aTable[1][2] == $i && $aTable[1][3] == $i) {
			$nWinner = $i;
		}
		elseif ($aTable[1][1] == $i && $aTable[2][2] == $i && $aTable[3][3] == $i) {
			$nWinner = $i;
		}
		elseif ($aTable[1][1] == $i && $aTable[2][1] == $i && $aTable[3][1] == $i) {
			$nWinner = $i;
		}

		elseif ($aTable[1][3] == $i && $aTable[2][2] == $i && $aTable[3][1] == $i) {
			$nWinner = $i;
		}
		elseif ($aTable[3][3] == $i && $aTable[3][2] == $i && $aTable[3][1] == $i) {
			$nWinner = $i;
		}
		elseif ($aTable[1][3] == $i && $aTable[2][3] == $i && $aTable[3][3] == $i) {
			$nWinner = $i;
		}
		elseif ($aTable[2][1] == $i && $aTable[2][2] == $i && $aTable[2][3] == $i) {
			$nWinner = $i;
		}
		elseif ($aTable[1][2] == $i && $aTable[2][2] == $i && $aTable[3][2] == $i) {
			$nWinner = $i;
		}

		$i++;
	}
	
	return $nWinner;
}



// generate machboxes

function generateMachboxes() {

	$aBoards = array();
	$nb = 0;
	
	for ($i = 0; $i <= 0; ++$i) {
		echo "$i X\n$i 0\n\n";
		
		$aBoards[$i] = array();
		
		// generate all possible combinations for $i X $i 0
		
		// generate all possible combinations for X
		$aAllXPositions = doCombinations(9, $i);
		
		// for each X configuration
		foreach ($aAllXPositions as $aXPositions) {
			// map positions
			$aMap = doMap($aXPositions);

			// generate all configurations for 0
			$_aAllOPositions = doCombinations(9 - $i, $i);
			$aAllOPositions = array();
			
			foreach ($_aAllOPositions as $key => $aOPositions) {
				$aOPositionsMapped = array();
				foreach ($aOPositions as $nPosition) {
					$aOPositionsMapped[] = $aMap[$nPosition];
				}
				$aAllOPositions[$key] = $aOPositionsMapped;
			}
			
			// generate boards
			foreach ($aAllOPositions as $aOPositions) {
				$aBoard = generateBoard($aXPositions, $aOPositions);
				
				$bEndGame = testEndGame($aBoard);
				
				if (!$bEndGame) {
					$bIsSymmetric = false;
					for ($j = 0; $j < count($aBoards[$i]); ++$j) {
						list($bIsSymmetric, $sSymmetryType) = testSymmetry($aBoard, $aBoards[$i][$j]['board']);
						if ($bIsSymmetric) {
							break;
						}
					}

					if (!$bIsSymmetric) {
						// all possible next moves

						$aNewBoards = array();
						for ($ii = 1; $ii <= 3; ++$ii) {
							for ($jj = 1; $jj <= 3; ++$jj) {
								if (!$aBoard[$ii][$jj]) {
									$nMove = ($ii - 1) * 3 + $jj;
									$aNewBoard = $aBoard;
									$aNewBoard[$ii][$jj] = 1;
									$aNewBoards[] = $aNewBoard;
									
									showBoard($aNewBoard);
								}
							}
						}
					
						$aBoards[$i][] =  array('board' => $aBoard, 'moves' => $aNewBoards);
						showBoard($aBoard);
						$nb++;
					}
				}
			}
		}
	}
	
	echo $nb;
}

function doCombinations($n, $p) {
	$aCombinations = $a = array();
	$nPos = 1;
	$a[1] = 0;
	do {
		$a[$nPos] = $a[$nPos] + 1;
		
		if ($a[$nPos] > $n) {
			$nPos--;
		}
		elseif ($nPos == $p) {
			$aCombinations[] = $a;
		}
		else {
			$nPos++;
			$a[$nPos] = $a[$nPos - 1];
		}
	}
	while ($nPos > 0);

	return $aCombinations;
}

function doMap($aPositions) {
	$aMap = $aOcc = array();
	$n = 1;
	$nAdd = 0;
	foreach ($aPositions as $nPosition) {
		$aOcc[$nPosition] = true;
	}

	for ($i = 1; $i <= 9; ++$i) {
		if (isset($aOcc[$i])) {
			$nAdd++;
		}
		else {
			$aMap[$n] = $n + $nAdd;
			$n++;
		}
	}
	
	return $aMap;
}

//print_R(doCombinations(9, 1));

//print_R(doMap(array(1, 2, 5, 6)));

generateMachboxes(); 


?>
