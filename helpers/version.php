<?php

class SatelliteVersionHelper extends SatellitePlugin {
	
    private $versionCheckUrl    =   "http://c-pr.es/latest-satellite-version.txt";
    private $membersPageUrl     =   "http://c-pr.es/members";
    
    function checkLatestVersion(){
      /*
            $thisVersion            = $this->get_option('stldb_version'); // check installed version
            $latestVersionAvailable = $this->readFile($this->versionCheckUrl); // check current version available
            $messageLatest          = "New Premium edition available!! Release version ".$latestVersionAvailable.". Automatic Update may cause problems with your installation, instead go to C-Pres <a href='".$this->membersPageUrl."' target='_blank'>Members Page</a> to download the latest version.";

            if($thisVersion >= $latestVersionAvailable && $latestVersionAvailable != '' && $latestVersionAvailable){ // check if the current version is latest
                    $arr['latest']  = true;
                    $arr['version'] =  $thisVersion;
            } else {
                    $arr['latest']  = false;
                    $arr['version'] =  $latestVersionAvailable;
                    $arr['message'] =  $messageLatest;
            }
            //DEBUG
            //print_r($thisVersion." = ".$latestVersionAvailable);
            return $arr;

    }*/
      return false;
	}
	
	function readFile($url){
            if (function_exists('curl_init')) {  // check if curl is enabled

               $ch = curl_init(); // initialize curl
               curl_setopt($ch, CURLOPT_URL, $url); // set the url
               curl_setopt($ch, CURLOPT_HEADER, 0); // set curl response headers to false
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return the value
               $content = curl_exec($ch);
               curl_close($ch); // close curl and free resources

            } else if (ini_get('allow_url_fopen') == '1') {
                     if ($fp = fopen($url, 'r')) {
                       $content = '';
                       while ($line = fgets($fp, 1024)) {  // read the entire file
                              $content .= $line;
                       }
                     }

            }else{

                    $content = file_get_contents($url);
            }			
            return trim($content);
	}
}
?>
