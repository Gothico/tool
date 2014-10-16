<?php
/*
 * Tale sorgente è distribuito secondo licenza CC BY-NC-SA 4.0: 
 * 			http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode
 * 
 * Tu sei libero di:
 *    - condividere — riprodurre, distribuire, comunicare al pubblico, 
 * 		esporre in pubblico, rappresentare, eseguire e recitare 
 * 		questo materiale con qualsiasi mezzo e formato; 
 *    - modificare — remixare, trasformare il materiale e basarti su di 
 * 		esso per le tue opere.
 * 	
 * Il licenziante non può revocare questi diritti fintanto che tu 
 * rispetti i termini della licenza alle seguenti condizioni:
 * 		- devi riconoscere l'autore originario e fornire un link alla 
 * 		  licenza;
 *      - non puoi usare il materiale per scopi commerciali;
 *      - se modifichi, o comunque ti basi su questo materiale devi 
 * 		  distribuire i tuoi elaborati con la stessa licenza.
 * 			
 * Autore: Riccardo Monterisi
 * */
?>
<?php

         $someUA = array (
				"Mozilla/5.0 (Windows; U; Windows NT 6.0; fr; rv:1.9.1b1) Gecko/20081007 Firefox/3.1b1",
				"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.0",
				"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/0.4.154.18 Safari/525.19",
				"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13",
				"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
					"Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.40607)",
					"Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.1; .NET CLR 1.1.4322)",
				"Mozilla/4.0  (compatible; MSIE 7.0b; Windows NT 5.1; .NET CLR 1.0.3705; Media Center  PC 3.1; Alexa Toolbar; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
				"Mozilla/45.0 (compatible; MSIE 6.0; Windows NT 5.1)",
				"Mozilla/4.08 (compatible; MSIE 6.0; Windows NT 5.1)",
				"Mozilla/4.01 (compatible; MSIE 6.0; Windows NT 5.1)");

function getRandomUserAgent ( ) {
    srand((double)microtime()*1000000);
    global $someUA;
    return $someUA[rand(0,count($someUA)-1)];
}


function getContent ($url) {
 
    // Crea la risorsa CURL
    $ch = curl_init();
 
    // Imposta l'URL e altre opzioni
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, getRandomUserAgent());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   
    // Scarica l'URL e lo passa al browser
    $output = curl_exec($ch);
    $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Chiude la risorsa curl
    curl_close($ch);

    if ($output === false || $info != 200) {
      $output = null;
    }

    return $output;
}  
        

        $x = $_POST['n'];
		$page = getContent($x);

        preg_match_all('/<a title=".(.*?) class="_lms /', $page, $nomi, PREG_PATTERN_ORDER);
        $nomi = $nomi[1];

        preg_match_all('/class="street-address">(.*?)</', $page, $indirizzo, PREG_PATTERN_ORDER);
        $indirizzo = $indirizzo[1];

        preg_match_all('/class="locality">(.*?)</', $page, $citta, PREG_PATTERN_ORDER);
        $citta = $citta[1];

		preg_match_all('/class="value">(.*?)</', $page, $tel, PREG_PATTERN_ORDER);
	
        $tel = $tel[1];
        $tel = array_unique($tel);
           //print_r($tel);   
		preg_match_all('/class="ums">(.*?)</i', $page, $URL, PREG_PATTERN_ORDER);
		
		$URL = $URL[1];        
        
        $j = 0;
        $dim = count($nomi);
        
        for ($i=0; $i<$dim; $i++)  {
            $a = substr($nomi[$i], 14);
            $ind = $URL[$i] .'/contatto?lt=frag'; 
            $email_page = getContent($ind);
            
           preg_match_all('/\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b/i', $email_page, $result, PREG_PATTERN_ORDER);
										$result = $result[0];
										//print_r($result);
									//	$mail = $result[0];
										
										$ur = $URL[$i];
										$tel_page = getContent($ur);
									//	preg_match_all('/class="value">(.*?)-|class="value">(.*?)</i', $tel_page, $res, PREG_PATTERN_ORDER);
									//	print_r($res);
											//$res = $res[0];
								//	$tel = $res[0];

		if ($i===0)									
           echo $a . ';  ' . $indirizzo[$i] . ';  ' . $citta[$i]. ';  '. current($tel) ."; "  . '<br />' ; // . $mail 
         else
            echo $a . ';  ' . $indirizzo[$i] . ';  ' . $citta[$i]. ';  '. next($tel) ."; "  . '<br />' ;   
			
        }   
           
?>
