<?php

    function getCountriesByName($country_name){
	$array = array();
	$curl_response = false;
	$service_url = 'https://restcountries.eu/rest/v2/name/'.$country_name.'?fields=name;alpha2Code;alpha3Code;flag;region;subregion;population;languages';
	//initialize a curl session
	$curl = curl_init();
	//set options for the transfer
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_TIMEOUT, 5);
	curl_setopt($curl, CURLOPT_URL, $service_url);
	curl_setopt($curl, CURLOPT_POST, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	//execute the session
	$curl_response = curl_exec($curl);
	//finish off the session
	curl_close($curl);
	$array = json_decode($curl_response, true);
	if (array_key_exists("status",$array)){
	    $array = False;
	}
	return $array;
    }
    
    function getCountriesByFullName($country_name){
	$array = array();
	$curl_response = false;
	$service_url = 'https://restcountries.eu/rest/v2/name/'.$country_name.'?fullText=true&fields=name;alpha2Code;alpha3Code;flag;region;subregion;population;languages';
	//initialize a curl session
	$curl = curl_init();
	//set options for the transfer
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_TIMEOUT, 5);
	curl_setopt($curl, CURLOPT_URL, $service_url);
	curl_setopt($curl, CURLOPT_POST, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	//execute the session
	$curl_response = curl_exec($curl);
	//finish off the session
	curl_close($curl);
	$array = json_decode($curl_response, true);
	if (array_key_exists("status",$array)){
	    $array = False;
	}
	return $array;
    }
    
    function getCountriesByAlpha($country_name){
	$array = array();
	$curl_response = false;
	$service_url = 'https://restcountries.eu/rest/v2/alpha/'.$country_name.'?fields=name;alpha2Code;alpha3Code;flag;region;subregion;population;languages';
	//initialize a curl session
	$curl = curl_init();
	//set options for the transfer
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_TIMEOUT, 5);
	curl_setopt($curl, CURLOPT_URL, $service_url);
	curl_setopt($curl, CURLOPT_POST, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	//execute the session
	$curl_response = curl_exec($curl);
	//finish off the session
	curl_close($curl);
	$array[] = json_decode($curl_response, true);
	if (array_key_exists("status",$array)){
	    $array = False;
	}
	return $array;
    }

    function sortByNamePop($a,$b){
	$name = strcmp(strtolower($a['name']), strtolower($b['name']));
	if($name === 0){$name = (int)$a['population'] - (int)$b['population'];}
	return $name;
    }    
    
    function limitJson($org_array){
	$data = array();
	foreach($org_array as $a ) {
	    $data[] = $a;
	    if ($i++ == 49) break;
	}
	return $data;
    }
    
if($_POST)
{ 
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        
        $output = json_encode(array( //create JSON data
            'type'=>'error', 
            'text' => 'Sorry Request must be Ajax POST'
        ));
        die($output); //exit script outputting json data
    } 
    
    //Sanitize input data using PHP filter_var().
    $country_name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $search_type = filter_var($_POST["search_type"], FILTER_SANITIZE_STRING);
    
    //additional php validation
    if(strlen($country_name)<1){ // If length is less than 4 it will output JSON error.
        $output = json_encode(array('type'=>'error', 'text' => 'Name is empty!'));
        die($output);
    }
    if($search_type == 'name'){
	$results = getCountriesByName($country_name);
    }else if($search_type == 'full_name'){
	$results = getCountriesByFullName($country_name);
    }else if($search_type == 'alpha'){
	$results = getCountriesByAlpha($country_name);
    }

    usort($results, 'sortByNamePop');

    $elementCount = count($results);
    if ($elementCount > 50){
	$results = limitJson($results);
    }

    if(!$results)
    {
        $output = json_encode(array('type'=>'error', 'text' => 'No results found.'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'message', 'text' => $results));
        die($output);
    }
}
?>