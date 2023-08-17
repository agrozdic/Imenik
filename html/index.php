<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>"> <!-- deo posle '.css' je kako bi se css primenio na elemente koji se naknadno upišu u HTML pomoću PHP-a ?> -->
	</head>
	<body>
		<ul>
		  <li><a href="index.php"> Imenik </a></li>
		  <li><a href="vazni.html"> Važni kontakti</a></li>
		  <li><a href="uputstvo.html"> Uputstvo </a></li>
		</ul>
		<br>
		<br>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			Ime: <input type="text" name="ime"></input>
			Prezime: <input type="text" name="prezime"></input>
			Adresa: <input type="text" name="adresa"></input>
			Mesto:
				<select name="mesto">
					<option value="" selected disabled hidden> Izaberi </option> <!-- prazan option -->
					<?php Ispuni() ?> <!-- funkcija koju samm napravio u PHP-u da ispuni select gradovima -->
				</select>
			Broj telefona: <input type="text" name="broj"></input>
			<input type="submit" name="submit" value="Traži">
		</form>
		<br>
		<br>
		<table>
			<tr>
				<th> Sifra </th>
				<th> Ime </th>
				<th> Prezime </th>
				<th> Adresa </th>
				<th> Mesto </th>
				<th> Broj </th>
				<th> Email </th>
			</tr>
			<?php
				function Mesto($mesto){ //funkcija koja u redu iz txt fajla izvlači mesto
					for($i=0; $i<4; $i++){ //mesto se nalazi posle četvrte crte, pa se 4 puta seče string
						$mesto = trim(substr($mesto, strpos($mesto, '|') + 1)); //odseći string iz stringa 'mesto' do mesta gde se nalazi uspravna crta
					}
					$mesto = substr($mesto, 0, strpos($mesto, " |")); //mesto je samo ono što je do sledeće uspravne crte
					return $mesto;
				}
				
				function Ispuni(){ //puni select mestima
					$file = fopen("../text/imenik.txt", "r") or die("Unable to open file!"); //otvara txt fajl
					$mesta = array(); //mesta ce se smeštati u niz
					$mesto = Mesto(fgets($file)); //red teksta se šalje u funkciju Mesto kako bi se dobilo mesto
					$mesta[] = $mesto; //prvo mesto se smešta u niy 'mesta'
					while(!(feof($file))){ //dok ne stigne do kraja fajla
						$mesto = Mesto(fgets($file));
						if(!(in_array($mesto, $mesta))){ //ako se mesto već ne nalazi u nizu
							$mesta[]=$mesto;			//smesti ga u niz
						}
					}
					foreach($mesta as $mesto){ //za svako mesto iz niza 'mesta'
						echo '<option value="' . $mesto .'"> '. $mesto . ' </option>'; //ubaci to mesto kao option u select
					}
					fclose($file); //zatvara se fajl na kraju
				}
				
				function Tabela(&$rezultati){ //funkcija za štampanje u tabelu
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
				
				$rezultati = array(); //rezultati su svi redovi koji ispunjavaju parametre
				
				if ($_SERVER["REQUEST_METHOD"] == "POST"){ //ako je stisnuto dugme 'Traži', odnosno poslat POST zahtev
					$IME = $_POST["ime"];			//ubaci stringove iz input polja u promenljive
					$PREZIME = $_POST["prezime"];
					$ADRESA = $_POST["adresa"];
					if(isset($_POST["mesto"])){		//ako nije selektovano ni jedno mesto
						$MESTO = $_POST["mesto"];	//			|
					}								//			|
					else{							//			V
						$MESTO = "";				//posmatraj ga kao prazan string
					}
					$BROJ = $_POST["broj"];
					$file = fopen("../text/imenik.txt", "r") or die("Unable to open file!");
					while(!(feof($file))){
						$line = fgets($file); //smesti red fajla u promenljivu 'line'
						$ind = 1; //indikator koji pokazuje da određeni red zadovoljava parametre
						$sifra = substr($line, 0, strpos($line, " |"));			//rasparčavanje reda
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
						if(!(empty($IME))){							//ako 'IME' (input polje) nije prazno
							if (strpos(" ".$ime, $IME) == false){	//proveri da li podstring 'IME'
								$ind = 0;							//postoji u promenljivoj 'ime'
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
						if($ind == 1){					//ako su ispunjeni parametri
							$rezultat = array();		//svaki od podataka iz txt fajla
							$rezultat[] = $sifra;		//smesta se u niz 'rezultat' koji
							$rezultat[] = $ime;			//se može posmatrati kao slog
							$rezultat[] = $prezime;
							$rezultat[] = $adresa;
							$rezultat[] = $mesto;
							$rezultat[] = $broj;
							$rezultat[] = $email;
							$rezultati[] = $rezultat;	//ceo niz 'rezulat' smešta se u finalan niz 'rezultati'
						}
					}
					fclose($file);
				}
				
				Tabela($rezultati); //ištampaj u tabelu sve rezultate koji ispunjavaju parametre
			?>
		</table>
	</body>
</html>