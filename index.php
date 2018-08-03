@@ -0,0 +1,218 @@
<?php 

		function porownaj($element1, $element2)
		{
			if($element1['delta'] > $element2['delta'])
				return 0;
			else
				return 1;
		}
		
		
		function uloz_tabele($tab,$spadek){
			
			$wynik = '';
			
			if($spadek){
				$spadek = 'spadki';
				$ikona = 'arrow_drop_down';
			}
			else{
				$spadek = '';
				$ikona = 'arrow_drop_up';
			}
				

			$wynik .= '<div class="row wzrosty clearfix">';
			$wynik .= '<div class="col-sm-1 '.$spadek.' ">';
			$wynik .= $tab['PositionNo'];
			$wynik .= '</div>';
			$wynik .= '<div class="col-sm-2 '.$spadek.'">';
			$wynik .= $tab['delta'].'<i class="material-icons">'.$ikona.'</i>';
			$wynik .= '</div>';
			$wynik .= '<div class="col-sm-9 '.$spadek.'">';
			$wynik .= '<p>FRAZA</p>';
			$wynik .= '<p>';
			$wynik .= $tab['PhraseValue'].' - ';
			$wynik .= $tab['pozycja'];
			$wynik .= '</p>';
			$wynik .= '</div>';
			$wynik .= '<div class="col-sm-12 ">';
			$wynik .= $tab['TitleValue'];
			$wynik .= '</div>';
			$wynik .= '<div class="col-sm-12">';
			$wynik .= $tab['URLValue'];
			$wynik .= '</div>';
			$wynik .= '<div class="col-sm-12 tabelka_zmiany">';
			$wynik .= '<table>';
			$wynik .= '<tr>';
			$wynik .= '<td>Domain:</td>';
			$wynik .= '<td>TF</td>';
			$wynik .= '<td>CF</td>';
			$wynik .= '<td>BL</td>';
			$wynik .= '<td>RD</td>';
			$wynik .= '<td>RIP</td>';
			$wynik .= '<td>WH</td>';
			$wynik .= '<td>AR</td>';
			$wynik .= '<td>AH</td>';
			$wynik .= '<td>MA</td>';
			$wynik .= '</tr>';
			$wynik .= '</table>';
			$wynik .= '</div>';
			$wynik .= '</div>';
			
			return $wynik;
		}
		 
		$now = date("Y-m-d", strtotime("-14 day"));
		$wczoraj = date("Y-m-d", strtotime("-15 day"));
		$trzy_dni = date("Y-m-d", strtotime("-17 days"));
		$tydzien = date("Y-m-d", strtotime("-21 days"));
		$miesiac = date("Y-m-d", strtotime("-44 days"));
		$trzy_miesiace = date("Y-m-d", strtotime("-104 days"));
		 
		$wynik_wzrosty_24 = '';
		$wynik_spadki_24 = '';
		$wynik_wzrosty_trzy_dni = '';
		$wynik_spadki_trzy_dni = '';
		$wynik_wzrosty_tydzien = '';
		$wynik_spadki_tydzien = '';
		$wynik_wzrosty_miesiac = '';
		$wynik_spadki_miesiac = '';
		$wynik_wzrosty_trzy_miesiace = '';
		$wynik_spadki_trzy_miesiace = '';
		
		include('templates/config.php');
	
		// Create connection
		$conn = new mysqli($servername, $username, $password,$database);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		
		$kategoria_array = explode('-',$_REQUEST['id']);
		$kategorie = '';
		
		foreach($kategoria_array as $kategoria){
			if($kategoria == '')
				continue;
			
			$kategorie .= "Result.PhraseID = '".$kategoria."' OR ";

			
			
		}
		
		$start = date("h:i:sa");
		
		$kategorie = substr($kategorie, 0, -4);
		
		
	//	$sql_wzrosty_24 = "SELECT TitleValue, PositionNo,URLValue FROM Result where ".$kategorie." AND Date = ".$now."";
		$sql_wzrosty_24 = "SELECT TitleValue, PhraseValue, PositionNo,URLValue FROM Result left join Phrase on Phrase.PhraseID = Result.PhraseID where $kategorie AND Date = '$now' ";
		
	//	$sql_wzrosty_24_next = "SELECT TitleValue, PositionNo,URLValue FROM Result where ".$kategorie." Date = ".$wczoraj."";
		$sql_wzrosty_24_next = "SELECT TitleValue,PhraseValue, PositionNo,URLValue FROM Result left join Phrase on Phrase.PhraseID = Result.PhraseID where $kategorie AND Date = '$wczoraj' ";
		
		$sql_wzrosty_24_next = "SELECT TitleValue,PhraseValue, PositionNo,URLValue FROM Result left join Phrase on Phrase.PhraseID = Result.PhraseID where $kategorie AND Date = '$wczoraj' ";
		$sql_wzrosty_trzy_dni = "SELECT TitleValue,PhraseValue, PositionNo,URLValue FROM Result left join Phrase on Phrase.PhraseID = Result.PhraseID where $kategorie AND Date = '$trzy_dni' ";
		$sql_wzrosty_tydzien = "SELECT TitleValue,PhraseValue, PositionNo,URLValue FROM Result left join Phrase on Phrase.PhraseID = Result.PhraseID where $kategorie AND Date = '$tydzien' ";
		$sql_wzrosty_miesiac = "SELECT TitleValue,PhraseValue, PositionNo,URLValue FROM Result left join Phrase on Phrase.PhraseID = Result.PhraseID where $kategorie AND Date = '$miesiac' ";
		$sql_wzrosty_trzy_miesiace = "SELECT TitleValue,PhraseValue, PositionNo,URLValue FROM left join Phrase on Phrase.PhraseID = Result.PhraseID Result where $kategorie AND Date = '$trzy_miesiace' ";

		$result_wzrosty_24 = $conn->query($sql_wzrosty_24);
		$result_wzrosty_24_next = $conn->query($sql_wzrosty_24_next);
		$result_wzrosty_trzy_dni = $conn->query($sql_wzrosty_trzy_dni);
		$result_wzrosty_tydzien = $conn->query($sql_wzrosty_tydzien);
		$result_wzrosty_miesiac = $conn->query($sql_wzrosty_miesiac);
		$result_wzrosty_trzy_miesiace = $conn->query($sql_wzrosty_trzy_miesiace);
		$error = $conn->error;
		
		$end_zapytania = date("h:i:sa");

		
		$tabela_pomocnicza = array();
		$tabela_delta = array();
		$i = 0;
		
		if ($result_wzrosty_24_next->num_rows > 0) {
			// output data of each row
			while($row_wzrosty_24_next = $result_wzrosty_24_next->fetch_assoc()) {
				if ($i == 0) {
				//	echo $result_wzrosty_24_next->num_rows;
					while($row_wzrosty_24 = $result_wzrosty_24->fetch_assoc()) {
						$tabela_pomocnicza[] = $row_wzrosty_24;
						
						if($row_wzrosty_24_next['URLValue'] == $row_wzrosty_24['URLValue']){
							 $delta = intval($row_wzrosty_24_next['PositionNo']) - intval($row_wzrosty_24['PositionNo']);
							 
							 $tabela_delta[$i]['TitleValue'] = $row_wzrosty_24['TitleValue'];
							 $tabela_delta[$i]['PositionNo'] = $row_wzrosty_24['PositionNo'];
							 $tabela_delta[$i]['URLValue'] = $row_wzrosty_24['URLValue'];
							 $tabela_delta[$i]['PhraseValue'] = $row_wzrosty_24['PhraseValue'];
							 $tabela_delta[$i]['delta'] = $delta_next;
							 $tabela_delta[$i++]['pozycja'] = 'dzis:'.$row_wzrosty_24['PositionNo'].' - '.$row_wzrosty_24_next['PositionNo'] ;
						}
						
					}
				}else{
					foreach($tabela_pomocnicza as $row_wzrosty_24){

						if($row_wzrosty_24_next['URLValue'] == $row_wzrosty_24['URLValue']){
							 $delta = intval($row_wzrosty_24_next['PositionNo']) - intval($row_wzrosty_24['PositionNo']);
							 
							 $tabela_delta[$i]['TitleValue'] = $row_wzrosty_24['TitleValue'];
							 $tabela_delta[$i]['PositionNo'] = $row_wzrosty_24['PositionNo'];
							 $tabela_delta[$i]['URLValue'] = $row_wzrosty_24['URLValue'];
							 $tabela_delta[$i]['PhraseValue'] = $row_wzrosty_24['PhraseValue'];
							 $tabela_delta[$i]['delta'] = $delta;
							 $tabela_delta[$i++]['pozycja'] = 'dzis:'.$row_wzrosty_24['PositionNo'].' - '.$row_wzrosty_24_next['PositionNo'] ;

							 break;
						}
					}
				}

				
			}
			
		usort($tabela_delta,'porownaj');
		
			
			$licznik = 0;
			
			foreach($tabela_delta as $row_wzrosty_24){
				
				if($licznik++ == 20 || $row_wzrosty_24['delta'] == 0 )
					break;
				
					$wynik_wzrosty_24 .= uloz_tabele($row_wzrosty_24,false);				
			}
			
			$licznik = 0;
			
			for($y = count($tabela_delta); $y >= 0; $y--){

				if($licznik == 20 || $tabela_delta[$y]['delta'] >= 0 )
					continue;
				
				if($licznik++ == 20)
					break;
				
				$wynik_spadki_24 .= uloz_tabele($tabela_delta[$y],true);		
				
			}
			
		}else{
			 $wynik_spadki_24 = 'brak rekordów';
			 $wynik_wzrosty_24 = 'brak rekordów';
		}
		
		echo  $wynik_spadki_24;
		
		echo $wynik_wzrosty_24;
		
?>
