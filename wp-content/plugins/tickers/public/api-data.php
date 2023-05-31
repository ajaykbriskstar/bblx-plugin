<?php 

class BBLX_APIDATA {

	function apiDataFetch($post){
		//global $post;
		$postID = $post->ID;
		$apiNames = 'overview,distribution,basket-exposures,holdings,total-return,performance&ticker='.$post->post_title;


		$apiData = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function='.$apiNames;
		
		$curl_handle = curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$apiData);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		$buffer = curl_exec($curl_handle);
		$etfData        = json_decode($buffer);
		$etfData        = (array)$etfData;
		$buffer = curl_exec($curl_handle);
		return $etfData;
	}

	function apiPremiumDiscount($post){
		//global $post;
		$postID = $post->ID;
		
		$month = date("n");
		$day = date("d");
		$previousYear = date("Y",strtotime("-1 year"));
		$time = $previousYear.'.'.$month.'.'.$day;

		//$apiNames = $overview.','.$distribution.','.$basket_exposures.','.$holdings.','.$total_return.','.$performance.'&ticker='.$post->post_title;
		$apiNames = 'premium-discount&ticker='.$post->post_title.'&date_gteq='.$time;
		
		$apiPremiumData = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function='.$apiNames;

		$curl_handle = curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$apiPremiumData);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		$bufferData = curl_exec($curl_handle);
		$etfPremiumData        = json_decode($bufferData);
		$etfPremiumData        = (array)$etfPremiumData;
		$bufferData = curl_exec($curl_handle);
		return $etfPremiumData;
	}
}

?>