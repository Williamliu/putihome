<?php
function curl_post($url, array $post = NULL, array $options = array())
{
    $defaults = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 4,
        CURLOPT_POSTFIELDS => http_build_query($post)
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch))
    {
		echo "curl error:" . curl_error($ch) . "\n";
    }
    curl_close($ch);
    return $result;
} 

function curl_https($url, $data) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_FORBID_REUSE, 0);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

/*
	$defaults = array(
        CURLOPT_POST => 1,
		CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 4,
        CURLOPT_POSTFIELDS => $data
    );

	$ch=curl_init();
    
	curl_setopt_array($ch, ($options + $defaults));
*/	

    if(! $res = curl_exec($curl))
    {
        //trigger_error(curl_error($ch));
		echo "curl error:" . curl_error($curl) . "\n";
    }
	
	curl_close($curl);
    return $res;
}


function curl_http($url, $data) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_FORBID_REUSE, 0);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

/*
	$defaults = array(
        CURLOPT_POST => 1,
		CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 4,
        CURLOPT_POSTFIELDS => $data
    );

	$ch=curl_init();
    
	curl_setopt_array($ch, ($options + $defaults));
*/	

    if(! $res = curl_exec($curl))
    {
        //trigger_error(curl_error($ch));
		echo "curl error:" . curl_error($curl) . "\n";
    }
	
	curl_close($curl);
    return $res;
}

function exec_php($script) {
	$query = "/usr/local/bin/php " . $script;
	$output = shell_exec($query);
	return $output;

}
?>