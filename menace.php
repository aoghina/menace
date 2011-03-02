<?php

/*
 * Math component object, implementing various matrix operations
*/
class BrainMathComponent {

	/*
	 * Rotates the game board (3X3 matrix) at 45 degrees
	*/
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

	/*
	 * Tests if two game board configurations (3X3 matrix) are identical
	*/
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

	/*
	 * Performs operations on a game board configuration (3X3 matrix)
	*/
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
				$aNewTable = $this->rotateTable45($aTable);		
			break;
			case 'rotate90':
				$aNewTable = $this->rotateTable45($aTable);
				$aNewTable = $this->rotateTable45($aNewTable);
			break;
			case 'rotate135':
				$aNewTable = $this->rotateTable45($aTable);
				$aNewTable = $this->rotateTable45($aNewTable);
				$aNewTable = $this->rotateTable45($aNewTable);
			break;
		}
	
		return $aNewTable;
	}

	/*
	 * Tests is two board configurations are symmetric
	*/
	function testSymmetry($aMoveToTestTable, $aSavedMovedTable) {
		$bIsSymmetric = $sSymmetryType = false;
		if ($this->testIdenticalTables($this->processGameTable($aMoveToTestTable, 'verticalSymmetry'), $aSavedMovedTable)) {
			// vertical symmetry
			$bIsSymmetric = true;
			$sSymmetryType = 'verticalSymmetry';
		}
		elseif ($this->testIdenticalTables($this->processGameTable($aMoveToTestTable, 'horizontalSymmetry'), $aSavedMovedTable)) {
			// horizontal symmetry
			$bIsSymmetric = true;
			$sSymmetryType = 'horizontalSymmetry';
		}
		elseif ($this->testIdenticalTables($this->processGameTable($aMoveToTestTable, 'firstDiagonalSymmetry'), $aSavedMovedTable)) {
			// first diagonal symmetry
			$bIsSymmetric = true;
			$sSymmetryType = 'firstDiagonalSymmetry';
		}
		elseif ($this->testIdenticalTables($this->processGameTable($aMoveToTestTable, 'secondDiagonalSymmetry'), $aSavedMovedTable)) {
			// second diagonal symmetry
			$bIsSymmetric = true;
			$sSymmetryType = 'secondDiagonalSymmetry';
		}
		elseif ($this->testIdenticalTables($this->processGameTable($aMoveToTestTable, 'rotate45'), $aSavedMovedTable)) {
			// rotate45 symmetry
			$bIsSymmetric = true;
			$sSymmetryType = 'rotate45';
		}
		elseif ($this->testIdenticalTables($this->processGameTable($aMoveToTestTable, 'rotate90'), $aSavedMovedTable)) {
			// rotate90 symmetry
			$bIsSymmetric = true;
			$sSymmetryType = 'rotate90';
		}
		elseif ($this->testIdenticalTables($this->processGameTable($aMoveToTestTable, 'rotate135'), $aSavedMovedTable)) {
			// rotate135 symmetry
			$bIsSymmetric = true;
			$sSymmetryType = 'rotate135';
		}
	
		return array($bIsSymmetric, $sSymmetryType);
	}

	/*
	 * Given a symmetry type, reverses that symmetry on a given board (used when returning from canonical to current board representation)
	*/
	function reverseSymmetry($aCurrentBoard, $sCanonicalSymmetryType) {
		switch ($sCanonicalSymmetryType) {
			case 'verticalSymmetry':
			case 'horizontalSymmetry':
			case 'firstDiagonalSymmetry':
			case 'secondDiagonalSymmetry':
				$aCurrentBoard = $this->processGameTable($aCurrentBoard, $sCanonicalSymmetryType);
			break;
		
			case 'rotate45':
				$aCurrentBoard = $this->processGameTable($aCurrentBoard, 'rotate135');
			break;
		
			case 'rotate90':
				$aCurrentBoard = $this->processGameTable($aCurrentBoard, 'rotate90');
			break;

			case 'rotate135':
				$aCurrentBoard = $this->processGameTable($aCurrentBoard, 'rotate45');
			break;
		}

		return $aCurrentBoard;
	}

	/*
	 * Generates a game board (3X3 matrix) given the positions for the Xs and 0s
	*/
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

	/*
	 * Tests if a game boad configuration is a win, lose or tie
	*/
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
	
	
		// test tie
		if (!$nWinner) {
			$bTie = true;
			for ($i = 1; $i <= 3; ++$i) {
				for ($j = 1; $j <= 3; ++$j) {
					if (!$aTable[$i][$j]) {
						$bTie = false;
						break;
					}
				}
			}
			if ($bTie)
				$nWinner = 3;
		}
	
		return $nWinner;
	}
	
	/* 
	 * Computes combinations of n taken as p 
	*/
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

	/* 
	 * Returns a map of available positions, providede the $aPositions are occupied 
	*/
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
}

/*
 * Visual component object, implementing visual user interactions
*/
class BrainVisualComponent {

	/*
	 * Displays a text on stardard output
	*/
	function displayText($s) {
		echo $s . "\n";
	}
	
	/*
	 * Read from stardard input
	*/
	function getText() {
		return fgets(STDIN);
	}

	/*
	 * Displays a board configuration
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
}

/*
 * Brain object, implementing MENACE
*/
class Brain {
	var $oMathComponent;
	var $oVisualComponent;
	var $sMatchboxesFileName;
	var $aMachBoxes;

	/*
	 * Class constructor. Init the math and visual components of the brain
	*/
	function __construct($sMatchboxesFileName) {
		$this->oMathComponent = new BrainMathComponent();
		$this->oVisualComponent = new BrainVisualComponent();
		$this->sMatchboxesFileName = $sMatchboxesFileName ? $sMatchboxesFileName : 'matchboxes_default.txt';
	}

	/*
	 * Loads the MENACE machine from file, or generates a new one
	*/
	function loadMatchboxes() {
		$sMachboxes = @file_get_contents('./' . $this->sMatchboxesFileName);
		if (!$sMachboxes) {
			$this->generateMachboxes();
		}
		else {
			eval("\$this->aMachboxes = $sMachboxes;");
		}
	}

	/*
	 * Saves current matchboxes configuration
	*/	
	function saveMatchboxes() {
		$fp = fopen('./' . $this->sMatchboxesFileName, 'w');
		$sMachboxes = var_export($this->aMachboxes, true);
		fwrite($fp, $sMachboxes);
	}
	
	/*
	 * Generates the matchboxes with the initial number of beads for each (MENACE machine)
	*/
	function generateMachboxes() {
	
		$this->oVisualComponent->displayText('Generating all board configurations...');

		$aBoards = array();
		$nb = 0;
	
		for ($i = 0; $i <= 4; ++$i) {
			//echo "$i X\n$i 0\n\n";
		
			$aBoards[$i] = array();
		
			// generate all possible combinations for $i X $i 0
		
			// generate all possible combinations for X
			$aAllXPositions = $this->oMathComponent->doCombinations(9, $i);
		
			// for each X configuration
			if (count($aAllXPositions)) {
			foreach ($aAllXPositions as $aXPositions) {
				// map positions
				$aMap = $this->oMathComponent->doMap($aXPositions);

				// generate all configurations for 0
				$_aAllOPositions = $this->oMathComponent->doCombinations(9 - $i, $i);
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
					$aBoard = $this->oMathComponent->generateBoard($aXPositions, $aOPositions);

					$bEndGame = $this->oMathComponent->testEndGame($aBoard);

					if (!$bEndGame) {
						$bIsSymmetric = false;
						for ($j = 0; $j < count($aBoards[$i]); ++$j) {
							list($bIsSymmetric, $sSymmetryType) = $this->oMathComponent->testSymmetry($aBoard, $aBoards[$i][$j]['board']);
							if ($bIsSymmetric) {
								break;
							}
						}

						if (!$bIsSymmetric) {
							$aBoards[$i][] =  array('board' => $aBoard);
							$nb++;
						}
					}
				}
			}
			}
			else {
				// first move
				$aBoards[$i][] = array('board' => $this->oMathComponent->generateBoard(array(), array()));
				$nb = 1;
			}
		
			foreach ($aBoards[$i] as $nBoard => $aBoardContainer) {
				$aBoard = $aBoardContainer['board'];
				// all possible next moves
				$aNewBoards = array();
				for ($ii = 1; $ii <= 3; ++$ii) {
					for ($jj = 1; $jj <= 3; ++$jj) {
						if (!$aBoard[$ii][$jj]) {
							$nMove = ($ii - 1) * 3 + $jj;
							$aNewBoard = $aBoard;
							$aNewBoard[$ii][$jj] = 1;
							$aNewBoards[] = array('board' => $aNewBoard, 'move' => $nMove);
						}
					}
				}
			
				$aNewBoardsUnique = array();
				for ($ii = 0; $ii < count($aNewBoards); ++$ii) {
					$bIsSymmetric = false;
					for ($jj = 0; $jj < count($aNewBoardsUnique); ++$jj) {
						list($bIsSymmetric, $sSymmetryType) = $this->oMathComponent->testSymmetry($aNewBoards[$ii]['board'], $aNewBoardsUnique[$jj]['board']);
						if ($bIsSymmetric) {
							break;
						}
					}
					if (!$bIsSymmetric) {
						$aNewBoardsUnique[] = $aNewBoards[$ii];
					}
				}
			
				// distribute beads
				$nBeadsPerMove = max(4 - $i, 1);
				foreach ($aNewBoardsUnique as $nNB => $aNewBoard) {
					$aBoards[$i][$nBoard]['moves'][] = array('move' => $aNewBoard['move'], 'board' => $aNewBoard['board'], 'beads' => $nBeadsPerMove);
				}
			}
		}
		echo $nb;

		$this->aMachboxes = $aBoards;
		
		$this->oVisualComponent->displayText('Board configurations generated successfully.');
	}

	
	/*
	 * Plays one game
	*/
	function playGame() {	
	
		$this->loadMatchboxes();
		
		$aMove = array();
		$nWinner = $nTurn = $cnt = 0;
		$aMenanceMoves = array();

		$aCurrentBoard = $this->oMathComponent->generateBoard(array(), array());
		
		while (!$nWinner) {
			// find canonical board
			$sCanonicalSymmetryType = '';
			$nCanonicalMaches = 0;
			$nCanonicalBoard = -1;
			if (isset($this->aMachboxes[$nTurn]))
			foreach ($this->aMachboxes[$nTurn] as $nCanonicalBoard => $aBoardContainer) {
				if ($this->oMathComponent->testIdenticalTables($aCurrentBoard, $aBoardContainer['board'])) {
					$nCanonicalMaches++;
					$nCanonicalMatch = $nCanonicalBoard;
					$sCanonicalSymmetryType = '';
				}
				else {
					list($bIsSymmetric, $sSymmetryType) = $this->oMathComponent->testSymmetry($aCurrentBoard, $aBoardContainer['board']);
					if ($bIsSymmetric) {
						$nCanonicalMaches++;
						$nCanonicalMatch = $nCanonicalBoard;
						$sCanonicalSymmetryType = $sSymmetryType;
					}
				}		
			}

			if ($nCanonicalMatch < 0) {
				// this should never happen!
				die("NO CANONICAL MATCH!");
			}

			$aCurrentBoard = $this->aMachboxes[$nTurn][$nCanonicalMatch]['board'];

			// pick next move
			$ii = 0;
			$aMovesToPick = array();
			foreach ($this->aMachboxes[$nTurn][$nCanonicalMatch]['moves'] as $nMove => $aMoveToPick) {
				for ($ii = 0; $ii < $aMoveToPick['beads']; ++$ii)
					$aMovesToPick[] = $nMove;
			}
	
			if (!count($aMovesToPick)) {
				// menace gives up!
				$nWinner = 2;
			}
			else {
				// menace picks move
				$nNextMove = $aMovesToPick[mt_rand(0, count($aMovesToPick) - 1)];
	
				// save move
				$aMenanceMoves[$nTurn] = array('nCanonicalMatch' => $nCanonicalMatch, 'nNextMove' => $nNextMove);

				// board for picked move
				$aCurrentBoard = $this->aMachboxes[$nTurn][$nCanonicalMatch]['moves'][$nNextMove]['board'];

				// switch board given canonical simmetry
				$aCurrentBoard = $this->oMathComponent->reverseSymmetry($aCurrentBoard, $sCanonicalSymmetryType);

				$this->oVisualComponent->displayText('MENACE moves:');
				$this->oVisualComponent->showBoard($aCurrentBoard);

				$nWinner = $this->oMathComponent->testEndGame($aCurrentBoard);
			}

			if (!$nWinner) {
				$this->oVisualComponent->displayText('YOUR TURN:');
				$nUserPosition = intVal($this->oVisualComponent->getText());
	
				$aCurrentBoard[floor(($nUserPosition - 1) / 3) + 1][($nUserPosition - 1) % 3 + 1] = 2;

				$this->oVisualComponent->showBoard($aCurrentBoard);

				$nWinner = $this->oMathComponent->testEndGame($aCurrentBoard);
			}

			$nTurn++;
		}

		return array($nWinner, $aMenanceMoves);
	}
	
	/*
	 * Performes reinforcement at the end of the game
	*/
	function doReinforce($aMenanceMoves, $nBeads) {
		foreach ($aMenanceMoves as $nTurn => $aBoardAndMove) {
			$this->aMachboxes[$nTurn][$aBoardAndMove['nCanonicalMatch']]['moves'][$aBoardAndMove['nNextMove']]['beads'] += $nBeads;
		}
	}
	
	/*
	 * Play games, improve MENACE until user stops
	*/
	function play() {
	
		do {
			list($nWinner, $aMenanceMoves) = $this->playGame();
			
			switch ($nWinner) {
				case 1:
					$this->oVisualComponent->displayText('YOU LOSE!');
					// MENACE WON! => positive reinforcement
					$this->doReinforce($aMenanceMoves, 3);
				break;

				case 2:
					$this->oVisualComponent->displayText('YOU WIN!');
					// MENACE LOST! => negative reinforcement
					$this->doReinforce($aMenanceMoves, -1);
				break;

				case 3:
					$this->oVisualComponent->displayText('TIE!');
					// TIE => positive reinforcement
					$this->doReinforce($aMenanceMoves, 1);
				break;
			}

			$this->saveMatchboxes();
			$this->oVisualComponent->displayText('PLAY MORE? (Y/N): ');

			$sUserPlays = trim($this->oVisualComponent->getText());

		} while ($sUserPlays != 'n' && $sUserPlays != 'N');
	}	
}


/*
 * RUN MENANCE
*/

echo "MENACE FILE:";
$sSavedMenaceFile = fgets(STDIN);

$oBrain = new Brain($sSavedMenaceFile);
$oBrain->play();
?>
