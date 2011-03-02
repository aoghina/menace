<?php

// again some differences

/*
 * Generate the table matrix for a move
*/
function generateTableMatrixFromMoves($aMove) {
	for ($i = 1; $i <= 3; ++$i) {
		for ($j = 1; $j <= 3; ++$j) {
			$aTable[$i][$j] = 0;
		}
	}
	
	foreach ($aMove as $key => $value) {
		$aTable[floor(($value - 1) / 3) + 1][($value - 1) % 3 + 1] = $key % 2 + 1;
	}
	
	return $aTable;
}

/*
 * Displays the board given the current move
*/
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

/*
 * Generate all possible next-moves given a current board configuration (move)
*/
function generateNextMoves($aMove) {
	$aMoves = array();
	$k = 0;
	for ($i = 1; $i <= 9; ++$i) {
		if (!in_array($i, $aMove)) {
			$aMoves[$k] = $aMove;
			$aMoves[$k++][] = $i;
		}
	}
	
	return $aMoves;
}

/*
 * Test if the current move implies a win or a tie
*/
function testEndGame($aMove) {
	$aTable = generateTableMatrixFromMoves($aMove);
	
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
	
	// tie
	if (!$nWinner && count($aMove) == 9)
		$nWinner = 3;
	
	return $nWinner;
}

/*
 * Pick next move from a list of available moves
*/
function pickMove($aMoves) {
	return $aMoves[mt_rand(0, count($aMoves) - 1)];
}

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

function testIdenticalTables($aTable1, $aTable2) {
	$bIsIndetical = true;
	for ($i = 1; $i <= 3; ++$i) {
		for ($j = 1; $j <= 3; ++$j) {
			if ($aTable1[$i][$j] != $aTable2[$i][$j]) {
				$bIsIndetical = false;
				break 2;
			}
		}
	}
	
	return $bIsIndetical;
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

function reduceMoves($aMoves, $aAllMoves) {
	$aNewMoves = array();

	foreach ($aMoves as $key => $aMoveToTest) {
		$bIsSymmetric = false;
		foreach ($aAllMoves as $key1 => $aSavedMove) {
			if (count($aMoveToTest) == count($aSavedMove)) {
				$aTableToTest = generateTableMatrixFromMoves($aMoveToTest);
				$aSavedTable = generateTableMatrixFromMoves($aSavedMove);
				if (testIdenticalTables($aTableToTest, $aSavedTable)) {
					$bIsSymmetric = true;
					break;
				}
				else {
					list($bIsSymmetric, $sSymmetryType) = testSymmetry($aTableToTest, $aSavedTable);
					if ($bIsSymmetric) {
						$bIsSymmetric = true;
						break;
					}
				}
			}
		}
		if (!$bIsSymmetric) {
			$aNewMoves[] = $aMoveToTest;
			$aAllMoves[] = $aMoveToTest;
		}
	}

	return $aNewMoves;
}

function generateMatchBoxes() {

	$aMove = $aAllMoves = $aMatchBoxes = $aMatchBoxesExplored = $aMatchBoxesGrouped = array();
	$nConfiguration = $nAllConfigurations = 0;

	do {
		$aMoves = generateNextMoves($aMove);
		$aMoves = reduceMoves($aMoves, $aMatchBoxes);

		foreach ($aMoves as $_aMove) {
			$aMatchBoxes[] = $_aMove;
			$aMatchBoxesExplored[] = false;
			
			if (!(count($aMove) % 2)) {
				// only for MENACE moves
				$nMenaceMove = count($aMove) / 2;
				if (!isset($aMatchBoxesGrouped[$nMenaceMove])) {
					$aMatchBoxesGrouped[$nMenaceMove] = array();
					$nConfiguration = 0;
				}
				if (!isset($aMatchBoxesGrouped[$nMenaceMove][$nConfiguration])) {
					$aMatchBoxesGrouped[$nMenaceMove][$nConfiguration] = array('configuration' => $aMove);	
					$nAllConfigurations++;
				}
				// save next moves, add beads
				$aMatchBoxesGrouped[$nMenaceMove][$nConfiguration]['nextMoves'][] = array('move' => $_aMove, 'beads' => max(4 - $nMenaceMove, 1));
			}
		}

		$nConfiguration++;

		$aMove = false;

		for ($i = 0; $i < count($aMatchBoxes); ++$i) {
			if (!$aMatchBoxesExplored[$i]) {
				$bEndGame = testEndGame($aMatchBoxes[$i]);
				if (!$bEndGame) {
					$aMatchBoxesExplored[$i] = true;
					$aMove = $aMatchBoxes[$i];
					break;
				}
				else {
					$aMatchBoxesExplored[$i] = true;
				}
			}
		}
	}
	while ($aMove && count($aMove) < 10);
/*	
	print_R($aMatchBoxes);
	echo $nAllConfigurations;
*/
	return $aMatchBoxesGrouped;
}

/*
$aMove = array(1, 2, 3, 4, 5);
$aTableToTest = generateTableMatrixFromMoves($aMove);
showBoard($aTableToTest);
$aTableToTest = processGameTable($aTableToTest, 'secondDiagonalSymmetry');
showBoard($aTableToTest);
die();


$bEndGame = testEndGame(array(2, 1, 4, 3, 5, 7, 6));
echo ($bEndGame ? "YES" : "NO");
die();
*/

$aMatchBoxes = generateMatchBoxes();

print_R($aMatchBoxes);

$aMove = array();
$nWinner = $nTurn = 0;

while (!$nWinner) {

	echo "MENACE TURN:\n";
	
	// all configurations for this turn
	$aConfigurations = $aMatchBoxes[$nTurn++];
	
	// find matching configuration
	$aMoveTable = generateTableMatrixFromMoves($aMove);
	$nFound = $nConfigurationKey = $sConfigurationSymmetryType = 0;
	foreach ($aConfigurations as $key => $aConfiguration) {
		$aConfigurationTable = generateTableMatrixFromMoves($aConfiguration['configuration']);
		$sSymmetryType = $bIsSymmetric = false;
		if (testIdenticalTables($aMoveTable, $aConfigurationTable)) {
			$bIsSymmetric = true;
		}
		else {
			list($bIsSymmetric, $sSymmetryType) = testSymmetry($aMoveTable, $aConfigurationTable);
		}
		
		if ($bIsSymmetric) {
			$nFound++;
			$nConfigurationKey = $key;
			$sConfigurationSymmetryType = $sSymmetryType;
		}
	}
	
	// by now we should have found the matchbox for this move
	if (!$nFound) {
		// this should never happen
		echo "NO CONFIGURATION FOUND!";
		die();
	}
	else {
		echo "\nMatched boxes: $nFound\n";
		showBoard(generateTableMatrixFromMoves($aConfigurations[$nConfigurationKey]['configuration']));
	
		// select next move
		$aMovesToSelect = array();
		foreach ($aConfigurations[$nConfigurationKey]['nextMoves'] as $nNextMoveKey => $_aNextMove) {
			for ($b = 0; $b < $_aNextMove['beads']; ++$b) {
				$aMovesToSelect[] = $nNextMoveKey;
			}
		}
		
		$nNextMoveKey = $aMovesToSelect[mt_rand(0, count($aMovesToSelect) - 1)];

		$aNextCanonicalMove = $aConfigurations[$nConfigurationKey]['nextMoves'][$nNextMoveKey]['move'];

		if ($sConfigurationSymmetryType) {
			// TODO: reverse symmetry
			$aMove = $aNextCanonicalMove;
		}
		else {
			$aMove = $aNextCanonicalMove;
		}

		echo "MENACE TURN: $sConfigurationSymmetryType \n";
		showBoard(generateTableMatrixFromMoves($aMove));
	}

	$nWinner = testEndGame($aMove);
	
	if (!$nWinner) {
		echo "YOUR TURN:\n";
		$nUserPosition = intVal(fgets(STDIN));
		$aMove[] = $nUserPosition;
		showBoard(generateTableMatrixFromMoves($aMove));
		$nWinner = testEndGame($aMove);
	}
}

switch ($nWinner) {
	case 1:
		// MENACE WON! => positive reinforcement
		//doReinforce($aMove, 1);		
		echo "\nYOU LOSE!\n";
		
	break;
	
	case 2:
		// MENACE LOST! => negative reinforcement
		doReinforce($aMove, -1);
		echo "\nYOU WIN!\n";
	break;
	
	case 3:
		echo "\nTIE\n";
	break;
}

//showBoard(array(8, 1, 3));
/*
$aMove = array(1,2);
$aMoves = generateNextMoves($aMove);
print_R($aMoves);
exit;
echo "Your move:\n";
$nUserPosition = intVal(fgets(STDIN));
echo  $nUserPosition;*/


?>
