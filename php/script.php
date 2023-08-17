<?php
	function Mesto($mesto){
		for($i=0; $i<4; $i++){
			$mesto = trim(substr($mesto, strpos($mesto, '|') + 1));
		}
		$mesto = substr($mesto, 0, strpos($mesto, " |"));
		return $mesto;
	}
	
	function Ispuni(){
		$file = fopen("../text/imenik.txt", "r") or die("Unable to open file!");
		$mesta = array();
		$mesto = Mesto(fgets($file));
		$mesta[] = $mesto;
		while(!(feof($file))){
			$mesto = Mesto(fgets($file));
			if(!(in_array($mesto, $mesta))){
				$mesta[]=$mesto;
			}
		}
		foreach($mesta as $mesto){
			echo '<option value="' . $mesto .'"> '. $mesto . ' </option>';
		}
		fclose($file);
	}
	
	function Tabela(&$rezultati){
		for($i=0; $i<sizeof($rezultati); $i++){
			echo
				"<tr>" . 
					"\t <td> " . $rezultati[$i][0] . " </td> \n".
					"\t <td> " . $rezultati[$i][1] . " </td> \n".
					"\t <td> " . $rezultati[$i][2] . " </td> \n".
					"\t <td> " . $rezultati[$i][3] . " </td> \n".
					"\t <td> " . $rezultati[$i][4] . " </td> \n".
					"\t <td> " . $rezultati[$i][5] . " </td> \n".
					"\t <td> " . $rezultati[$i][6] . " </td> \n".
				"</tr> \n";
		}
	}
	
	$rezultati = array();
	
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$IME = $_POST["ime"];
		$PREZIME = $_POST["prezime"];
		$ADRESA = $_POST["adresa"];
		if(isset($_POST["mesto"])){
			$MESTO = $_POST["mesto"];
		}
		else{
			$MESTO = "";
		}
		$BROJ = $_POST["broj"];
		$file = fopen("../text/imenik.txt", "r") or die("Unable to open file!");
		while(!(feof($file))){
			$line = fgets($file);
			$ind = 1;
			$sifra = substr($line, 0, strpos($line, " |"));
			$line = trim(substr($line, strpos($line, '|') + 1));
			$ime = substr($line, 0, strpos($line, " |"));
			$line = trim(substr($line, strpos($line, '|') + 1));
			$prezime = substr($line, 0, strpos($line, " |"));
			$line = trim(substr($line, strpos($line, '|') + 1));
			$adresa = substr($line, 0, strpos($line, " |"));
			$line = trim(substr($line, strpos($line, '|') + 1));
			$mesto = substr($line, 0, strpos($line, " |"));
			$line = trim(substr($line, strpos($line, '|') + 1));
			$broj = substr($line, 0, strpos($line, " |"));
			$line = trim(substr($line, strpos($line, '|') + 1));
			$email = $line;
			if(!(empty($IME))){
				if (strpos(" ".$ime, $IME) == false){
					$ind = 0;
				}	
			}
			if(!empty($PREZIME)){
				if (strpos(" ".$prezime, $PREZIME) == false){
					$ind = 0;
				}	
			}
			if(!empty($ADRESA)){
				if (strpos(" ".$adresa, $ADRESA) == false){
					$ind = 0;
				}	
			}
			if(!empty($MESTO)){
				if (strpos(" ".$mesto, $MESTO) == false){
					$ind = 0;
				}	
			}
			if(!empty($BROJ)){
				if (strpos(" ".$broj, $BROJ) == false){
					$ind = 0;
				}	
			}
			if($ind == 1){
				$rezultat = array();
				$rezultat[] = $sifra;
				$rezultat[] = $ime;
				$rezultat[] = $prezime;
				$rezultat[] = $adresa;
				$rezultat[] = $mesto;
				$rezultat[] = $broj;
				$rezultat[] = $email;
				$rezultati[] = $rezultat;
			}
		}
		fclose($file);
	}
	
	Tabela($rezultati);
?>