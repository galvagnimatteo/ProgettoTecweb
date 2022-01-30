<?php
	
	function generateItalianDate($timestamp) {
		
		$mesi = array(1=>'gennaio', 'febbraio', 'marzo', 'aprile',
               'maggio', 'giugno', 'luglio', 'agosto',
               'settembre', 'ottobre', 'novembre','dicembre');

		$giorni = array('Sunday' => 'Domenica','Monday' => 'Lunedì','Tuesday' => 'Marted','Wednesday' => 'Mercoledì',
                'Thursday'=> 'Giovedì','Friday' => 'Venerdì','Saturday' => 'Sabato');

		list($nomeGiorno, $giorno, $mese, $anno) = explode('-',date('l-d-m-Y', $timestamp));
		return $giorni[$nomeGiorno] . ' ' . $giorno . ' ' . $mesi[intval($mese)] . ' ' . $anno;
	}


?>