<?php
    /**
     *  titolo: A Christmas Carol
     *  autore: Matteo Enna
     *  licenza GPL3
     **/
    define('BOT_TOKEN', '');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

    // read incoming info and grab the chatID
    $content = file_get_contents("php://input");
    $update = json_decode($content, true);
    $chatID = $update["message"]["chat"]["id"];
    
    $favola = file_get_contents('Cantico_di_Natale.txt');
    
    $array_fav = array();
    
    $righe = explode(chr(10).chr(10).chr(10),$favola);
    
	$intro = array(
		'Il Canto di Natale (A Christmas Carol: A Goblin Story of Some Bells that Rang an Old Year Out and a New Year In), noto anche come Cantico di Natale, Ballata di Natale o Racconto di Natale, è un romanzo breve di genere fantastico del 1843 di Charles Dickens (1812-1870), di cui è una delle opere più famose e popolari. È il più importante della serie dei Libri di Natale (The Christmas Books), una serie di storie che include anche Le campane (The Chimes, 1845), Il grillo del focolare (The Cricket on the Hearth, 1845), La battaglia della vita (The Battle for Life, 1846) e Il patto col fantasma (The Haunted Man, 1848).',
		'',
		'Inizia a leggere /prima_1'
	);
	
    $array_fav['intro']   = implode ("\n",$intro);
    $array_fav['prima']   = str_replace(chr(10).chr(10), chr(10), $righe[10]);
    $array_fav['seconda'] = str_replace(chr(10).chr(10), chr(10), $righe[11]);
    $array_fav['terza']   = str_replace(chr(10).chr(10), chr(10), $righe[13]);
    $array_fav['quarta']  = str_replace(chr(10).chr(10), chr(10), $righe[15]);
    $array_fav['quinta']  = str_replace(chr(10).chr(10), chr(10), $righe[17]);
    $array_fav['sesta']   = str_replace(chr(10).chr(10), chr(10), $righe[19]);
    $array_fav['credits'] = 'Questo bot telegram è stato realizzato da @matteoenna. Il racconto invece potete trovarlo a questo indirizzo: https://it.wikisource.org/wiki/Cantico_di_Natale';

    
    $benvenuto_ray = array(
        'Benvenuto su "A Christmas Carol Bot".',
        'Il Bot telegram (Open Source) dove potrai leggere il Romanzo "il cantico di natale" di Charles Dickens.',
        'Questo bot telegram è stato realizzato da @matteoenna.',
        'Il testo è presente su Wikisource: https://it.wikisource.org/wiki/Cantico_di_Natale ',
		'Puoi partire dai primo capitolo: /intro',
		'Oppure consultare l\'indice: /capitoli'
    );
		
    $benvenuto=implode ("\n",$benvenuto_ray);
    
    $capitoli = array(
        'Introduzione : /intro',
        'Strofa prima : /prima',
        'Strofa seconda : /seconda',
        'Strofa terza : /terza',
        'Strofa quarta : /quarta',
        'Strofa quinta : /quinta',
        'Strofa sesta : /sesta',
        'Credits : /credits'
    );
    
    $cap=implode ("\n",$capitoli);
    
    if($update["message"]["text"]=="/start" || $update["message"]["text"]=="/help"){
		$sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".urlencode($benvenuto);
		file_get_contents($sendto);
		
		die();
    
    } elseif($update["message"]["text"]=="/capitoli") {        
		$sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".urlencode($cap);
		file_get_contents($sendto);
		
		die();
    } else {
        if($update["message"]["text"]=="/intro") {      
            $sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".urlencode($array_fav['intro']);
            file_get_contents($sendto);	
            die();            
        }
		
		libro ($update, $array_fav);
		
        $sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".urlencode($benvenuto);
		file_get_contents($sendto);
	
		die();
    }
	
	function libro ($update, $array_fav) {
		$chatID = $update["message"]["chat"]["id"];
		
			
		if (stripos($update["message"]["text"],'/')!==FALSE) {		
			$mess = str_replace('/','',$update["message"]["text"]);
			if(stripos($mess, '_')!==FALSE) {
				
				$str = explode('_',$mess);
				$strofa = $str[0];
				
				if(stripos($update["message"]["text"],"/".$strofa)!==FALSE) {
					$key_p = explode('/'.$strofa.'_', $update["message"]["text"]);
					$page_S =explode("(pp)",$array_fav[$strofa]);
					
					$page = $key_p[1] - 1;
					
					$sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".urlencode(substr($page_S[$page],0,4096));
					file_get_contents($sendto);
					
					$mess = $strofa;
				}
				
			} else {
				$strofa = $mess;
			}
			
			$array_key = array(
				'prima',
				'seconda',
				'terza',
				'quarta',
				'quinta',
				'sesta',			   
			);
						
			if(!in_array($mess, $array_key)) {
				return ;
			}
			
			$page_S =explode("(pp)",$array_fav[$strofa]);
			$paginazione = array();
			foreach($page_S as $k => $o_p) {
				$numero = $k+1;
				$paginazione[] = "Pagina n°".$numero.": /".$strofa."_".$numero;
			}
			$paginazione[] = '';
			$paginazione[] = 'Indice: /capitoli';
			$cap=implode ("\n",$paginazione);
			$sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".urlencode($cap);
			file_get_contents($sendto);
			
			
			die;
			
		}
		
		
	}
	
    
?>
