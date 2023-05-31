<?php 

class BBLX_APIDATA {
	/*
	* Fetch the multiple APIs data
	*/

	protected function curlUrl($apiData = []){
		$curl_handle = curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$apiData);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		$buffer 	= curl_exec($curl_handle);
		$etfData    = json_decode($buffer);
		$etfData    = (array)$etfData;
		$buffer 	= curl_exec($curl_handle);
		return $etfData;
	}

	public function apiDataFetch($post){
		$apiNames = 'overview,distribution,basket-exposures,holdings,total-return,performance&ticker='.$post->post_title;
		$apiData = BBLX_STG_API_URL.'?apikey='.BBLX_STG_API_KEY.'&function='.$apiNames;
		$curl = $this->curlUrl($apiData);
		return $curl;
	}

	protected function getDateTime (){
		$month 			= date("n");
		$day 			= date("d");
		$previousYear 	= date("Y",strtotime("-1 year"));
		$time 			= $previousYear.'.'.$month.'.'.$day;
		return $time;
	}

	/*
	* Fetch the Premium Discount APIs data
	*/
	public function apiPremiumDiscount($post){
		$time = $this->getDateTime();
		$apiNames = 'premium-discount&ticker='.$post->post_title.'&date_gteq='.$time;
		$apiPremiumData = BBLX_STG_API_URL.'?apikey='.BBLX_STG_API_KEY.'&function='.$apiNames;
		$curl = $this->curlUrl($apiPremiumData);
		return $curl;
	}

	/*
	* Fetch the Premium Discount Statistics APIs data
	*/
	public function apiPremiumDiscountStatistics($post){
		$time = $this->getDateTime();
		$apiNames = 'premium-discount-statistics&ticker='.$post->post_title.'&date_gteq='.$time;
		$apiPremiumData = BBLX_STG_API_URL.'?apikey='.BBLX_STG_API_KEY.'&function='.$apiNames;
		$curl = $this->curlUrl($apiPremiumData);
		return $curl;
	}

	/*
	* Function for number shorting
	*/
	public function numberShorten($number, $precision = 2) {
	    $suffixes = ['', 'K', 'M', 'B', 'T', 'Qa', 'Qi'];
        $index = 0;
        if (!is_numeric($number))
            return false;
        if ($number > 1000000000000) {
            $index = 4;
        } elseif ($number > 1000000000) {
            $index = 3;
        } elseif ($number > 1000000) {
            $index = 2;
        } elseif ($number > 1000) {
            $index = 2;
        } else {
            $index = 0;
        }
        return number_format($number / 1000 ** $index, $precision) . $suffixes[$index];
	}

	/*
	* Function for truncate number format
	*/
	public function truncate($number, $precision = 0) {
	   $shift = pow(10, $precision);
	   return intval($number * $shift)/$shift;
	}

	/*
	* Function for get to Performance Annual records for "Annual" and "Cumulative" table.
	*/
	public function performanceAnnualRecords($bblxApiData = [], $performance = ''){
		$performanceAnnualNav = [];
		$previousQuarter = $this->getQuarterNumber();
		if(!empty($previousQuarter) && $performance === 'annual'){
			$quarter = 'Q-1';
		}else{
			$quarter = 'T-1';
		}
		if($performance == 'annual'){
			$filterByNav = ['y1', 'y2', 'y3','inception'];
		}else{
			$filterByNav 	= ['wtd', 'mtd', 'qtd', 'ytd'];
		}
		if(isset($bblxApiData['performance']) && !empty($bblxApiData['performance'])){
			foreach ($bblxApiData['performance'] as $value) {
				if($value->asof_date_type === $quarter){
					if(in_array($value->range , $filterByNav )){
						$performanceAnnualNav[$value->range] = $value;
					}
				}
			}
		}
		return $performanceAnnualNav;
	}

	/*
	* Get the Performance Annual Quarter value.
	*/
	public function performanceAnnualQuarter($bblxApiData = []){
		$annualQuarter = [];
		if(isset($bblxApiData['performance']) && !empty($bblxApiData['performance'])){
			$bblxApiData = (array)$bblxApiData['performance'];
			if(!isset($bblxApiData['error']) && ($bblxApiData['error']->display !== "") ){
				$filterByNav = ['Q-1', 'Q-2', 'Q-3'];
				foreach ($bblxApiData as $key => $value) {
					if(in_array($value->asof_date_type , $filterByNav )){
						$annualQuarter[$value->asof_date_type] = $value;
					}
				}
			}
			return $annualQuarter;
		}
	}

	/*
	* Get the Quarter end date.
	*/
	public function quarterEndDate($date){
		$current_quarter = ceil(date('n') / 3);
		return $last_date = date('M t, Y', strtotime($date . '-' . (($current_quarter * 3)) . '-1'));
	}

	/*
	* Get the distribution table data with sorting.
	*/
	public function distributionSortArray($distribution = []){
		$sort = array();
		if(isset($distribution) && !empty($distribution)){
			$bblx = (array)$distribution;
			if(!isset($bblx['error']) && $bblx['error']->display !== "" ){
				foreach($distribution as $k=>$v) {
					$v = (array)$v;
					$sort['ex_date'][$k] = $v['ex_date'];
					$sort['record_date'][$k] = $v['record_date'];
					$sort['pay_date'][$k] = $v['pay_date'];
					$sort['dividend_income'][$k] = $v['dividend_income'];
				}
				array_multisort($sort['ex_date'], SORT_DESC, $sort['ex_date'], SORT_ASC,$distribution);
				return $distribution;
			}
			# It is sorted by event_type in descending order and the title is sorted in ascending order.
		}
	}

	/*
	* Get the performance as of date only.
	*/
	public function performanAsofDate($bblxApiData = []){
		$AsofDateArr = '';
		if(isset($bblxApiData['performance']) && !empty($bblxApiData['performance'])){
			$bblx = (array)$bblxApiData['performance'];
			if(!isset($bblx['error']) && $bblx['error']->display !== "" ){
				$filterByT 	= 'T-1';
			    $AsofDateArr = array_filter($bblxApiData['performance'], function ($var) use ( $filterByT) {
			            return ($var->asof_date_type == $filterByT);
			        });
			}
		    return $AsofDateArr;
		}
	}

	/*
	* Get the records fot Premium Discount Statistics table
	*/
	public function premiumDiscountStatisticsTable($discountStatistics=[]){
		$discountStatisticsArr = [];
		$previousYear = date("Y",strtotime("-1 year"));
		$currentYear = date("Y");
		if(isset($discountStatistics) && !empty($discountStatistics)){
			$bblxStatistics = (array)$discountStatistics;
			if(!isset($bblxStatistics['error']) && $bblxStatistics['error']->display !== "" ){
				$filterByYear 	= 'Y'.$previousYear;
			    $filterByPrice 	= ['Q'.$currentYear.'_Q1', 'Q'.$currentYear.'_Q2', 'Q'.$currentYear.'_Q3', 'Q'.$currentYear.'_Q4'];
			    $discountStatisticsArr[] = array_filter($bblxStatistics, function ($var) use ( $filterByYear) {
		             return (($var->time_period_name == $filterByYear) && ($var->time_period_is_complete == 'false'));
		        });
			    foreach($filterByPrice as $filterPriceKey => $filterPriceVal){
			            $discountStatisticsArr[] = array_filter($bblxStatistics, function ($var) use ($filterPriceVal) {
			            return (($var->time_period_name == $filterPriceVal) && ($var->time_period_is_complete == 'true'));
			        });
			    }
			}
		    return $discountStatisticsArr;
		}
	}

	/*
	* Get all the previous quarter number
	*/
	public function getQuarterNumber(){
		$currentQuarter = ceil(date('n') / 3); // Current quarter
		$lastThreeQuarters = array();
		for ($i = 1; $i <= 3; $i++) {
			$quarter = ($currentQuarter - $i + 4) % 4;
			if ($quarter === 0) {
				$quarter = '4';
			}
			$lastThreeQuarters[] = $quarter;
		}
		return $lastThreeQuarters;
	}

	/*
	* Get annual quarters for table column
	*/
	public function annualQuarter($bblxApiData = []){
		$annualQuarter = $this->performanceAnnualQuarter($bblxApiData);
		$annualQuarterArr = array_reverse($annualQuarter);
		$i = 1;
		foreach($annualQuarterArr as $val){
			$currentQuarter = ceil(date('n') / 3);
			$quarterEndDate = $this->quarterEndDate($val->asof_date);
			$previousQuarter = ($currentQuarter - $i + 4) % 4;
			if ($previousQuarter === 0) {
				$previousQuarter = 4;
			}
			$class = "";
			if($i == 1){
				$class = 'active';
			}
			$quarterNo = 'Q'.$previousQuarter;
			$quarter = $quarterEndDate;
			$response[] = ['quarterNo' => $quarterNo, 'quarter' => $quarter, 'class' => $class, 'currentQuarter' => $currentQuarter];
			$i++;
		}
		return $response;
	}

	/*
	* function for annual and cumulative values with html
	*/
	public function performanceRecords($bblxApiData = [], $performace = ''){
		$returnTable = array(
			'nav_performance_annualized' => 'NAV',
			'close_performance_annualized' => 'Market Price',
			'index_performance_annualized' => 'Index',
		);
		$performanceData   = $this->performanceAnnualRecords($bblxApiData, $performace);
		foreach ($returnTable as $key => $value) {
			$val = array('-','-','-','-');
			$index = 0;
			$trClass = $annualClass = '';
			$position = ['wtd', 'mtd', 'qtd', 'ytd'];
			if($performace === 'annual'){
				$position = ['y1','y2','y3','inception'];
				$trClass = 'class="annual-records"';
				$annualClass = 'class="performanceRes"';
			}
			
			foreach ($position as $pos) {
				if(isset($performanceData[$pos]->$key) && $performanceData[$pos]->$key != null){
					$val[$index] = number_format($performanceData[$pos]->$key*100, 2).'%';
				}
				$index++;
			}
			if($value === 'NAV'){
				$popupInfo = '<a href="javascript:void(0)" id="nav-popup"><span class="fas fa-info-circle infoIcon"></span></a>';
			}
			if($value === 'Market Price'){
				$popupInfo = '<a href="javascript:void(0)" id="market-price"><span class="fas fa-info-circle infoIcon"></span></a>';
			}
			if($value === 'Index'){
				$popupInfo = '';
			}
			$html[] = '<tr '.$trClass.'>
				<td class="">'.$value.$popupInfo.'</span></a></td>
				<td '.$annualClass.'>'.$val[0].'</td>
				<td '.$annualClass.'>'.$val[1].'</td>
				<td '.$annualClass.'>'.$val[2].'</td>
				<td '.$annualClass.'>'.$val[3].'</td>
			</tr>';
		}
		return $response[] = $html;
	}

}