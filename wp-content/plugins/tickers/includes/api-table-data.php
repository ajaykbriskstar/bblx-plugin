<?php
class BBLX_ApiTableData {
	function getApiTableData($data, $title, $categoryName){
		$overview = [];
		$numberAum = $asof_date = $medSpread = $premDiscountRatio = $expenseratio = $weightedAvgMaturity = $coupon_amt = $strippedYtm = $yield_to_worst = $spread_duration = $yield_30d_subsidized = $related_index = $inceptionDate = null;
		if(!empty($data)){
			$overview = $data['overview'][0];	
		}

		$bblxApi = new BBLX_APIDATA();
		if(!empty($overview)){
			$numberAum 				= $bblxApi->numberShorten($overview->aum);
			$related_index 			= $bblxApi->numberShorten(($overview->related_index_market_cap_usd),3);
			$weightedAvgMaturity 	= number_format(($overview->weighted_average_maturity),2);
			$medSpread 				= isset($overview->median_spread_6c11)?number_format(($overview->median_spread_6c11 * 100),2).'%':"NA";
			$yield_to_worst 		= floatval(number_format(($overview->yield_to_worst * 100),2)).'%';
			$expenseratio 			= floatval($overview->expenseratio * 100);
			$charLength = strlen($expenseratio);
		    if($charLength == 3){
		        $expenseratio = $expenseratio.'0';
		    }
			$spread_duration 		= floatval(number_format(($overview->weighted_average_spread_duration),2));
			$premDiscountRatio 		= isset($overview->prem_discount_ratio)?number_format(($overview->prem_discount_ratio * 100),2).'%':"NA";
			$yield_30d_subsidized 	= isset($overview->sec_yield_30d_subsidized)?number_format(($overview->sec_yield_30d_subsidized * 100),2).'%':"NA";
			$asof_date 				= isset($overview->asof_date)?date('m/d/Y', strtotime($overview->asof_date)):"NA";

			if( isset($overview->inceptiondate) && strpos($overview->inceptiondate, '.') !== false){
			  $date = explode(".",$overview->inceptiondate);
			  $inceptionDate = $date[0].'-'.$date[1].'-'.$date[2];
			} else {
			  $inceptionDate = date('y-m-d', strtotime($overview->inceptiondate));
			}
		}

		if($title == "XEMD"){
			$portfolio_count 		= 'Number of Countries';
			$adjusted_spread 		= 'Spread to Worst';
			$portfolioCount 		= $overview->country_count;
			$optionAdjustedSpread 	= isset($overview->stw_trsy)?(intval($overview->stw_trsy*10000)):"NA";
			$coupon_amt 			= floatval(number_format(($overview->coupon_amt * 100),2)).'%';
			$strippedYtm 			= number_format(($overview->stripped_ytm * 100),2).'%';
		}else if($categoryName == 'Treasuries'){
			$portfolio_count = 'Duration';
			$portfolioCount = number_format(($overview->weighted_average_effective_duration),2);
			$strippedYtm 			= number_format(($overview->average_yield_to_maturity * 100),2).'%';
			$coupon_amt 			= floatval(number_format(($overview->weighted_average_coupon * 100),2)).'%';
		}else{
			$portfolio_count = 'Number of Issuers';
			$adjusted_spread = 'Option Adjusted Spread';
			$portfolioCount = $overview->basket_analytics_issuer_count;
			$optionAdjustedSpread = isset($overview->average_option_adjusted_spread)?number_format(($overview->average_option_adjusted_spread)):"NA";
			$coupon_amt 			= floatval(number_format(($overview->weighted_average_coupon * 100),2)).'%';
			$strippedYtm 			= number_format(($overview->average_yield_to_maturity * 100),2).'%';
		}
		
		if(isset($numberAum) && $numberAum != ''){
			$number_aum = '$'.substr($numberAum, 0, -1);
		}else{
			$number_aum = '$--';
		}

		if($categoryName == 'Treasuries'){
			$characteristics = array(
				'Yield_to_Maturity' 	=> isset($strippedYtm)?$strippedYtm:"NA",
				'30-Day_SEC_Yield' 		=> $yield_30d_subsidized,
				'Average_Credit_Rating' => isset($overview->weighted_average_credit_rating)?$overview->weighted_average_credit_rating:"NA",
			);
		}else{
			$characteristics = array(
				'Yield_to_Maturity' 	=> isset($strippedYtm)?$strippedYtm:"NA",
				'Yield_to_Worst' 		=> isset($yield_to_worst)?$yield_to_worst:"NA",
				$adjusted_spread 		=> $optionAdjustedSpread,
				'Spread_Duration' 		=> isset($spread_duration)?$spread_duration:"NA",
				'30-Day_SEC_Yield' 		=> $yield_30d_subsidized,
			);
		}

		$tableData = array(
			'fund_details' => array(
				'Product_Name' 			=> isset($overview->fund_name)?$overview->fund_name:"NA",
				'Ticker' 				=> isset($overview->ticker)?$overview->ticker: "NA",
				'CUSIP' 				=> isset($overview->cusip)?$overview->cusip: "NA",
				'Asset_Class' 			=> isset($overview->assetclass)?$overview->assetclass:"NA",
				'Fund_Inception_Date' 	=> isset($inceptionDate)?date("n/d/Y", strtotime($inceptionDate)): "NA",
				'Exchange' 				=> isset($overview->primary_exchange)?$overview->primary_exchange:"NA",
				'Distribution_Frequency'=> "Monthly",
				'Fund_Net_Assets_($MM)' => $number_aum,
				'Shares_Outstanding' 	=> isset($overview->shout)?number_format($overview->shout):"NA",
				'NAIC_Rating' 			=> "NA",
				'Median_Spread' 		=> isset($medSpread)?$medSpread:"NA",
				'Premium_Discount_Ratio'=> isset($premDiscountRatio)?$premDiscountRatio:"NA",
				'Closing_Price_as_of '.$asof_date => isset($overview->adjclose)?'$'.number_format($overview->adjclose,2):"$--",
			),
			'fees' => array(
				'Management_Fee' 					=> isset($expenseratio)?$expenseratio.'%':"0.00%",
				'Acquired_Fund_Fees_and_Expenses' 	=> "0.00%",
				'Foreign_Taxes_and_Other_Expenses' 	=> "0.00%",
				'Expense_Ratio' 					=> isset($expenseratio)?$expenseratio.'%':"0.00%",
			),
			'portfolio_characteristics' => array(
				'Number_of_Securities' 	=> isset($overview->basket_analytics_securities_count)?$overview->basket_analytics_securities_count:"NA",
				$portfolio_count 		=> isset($portfolioCount)?$portfolioCount:"NA",
				'Average_Coupon' 		=> isset($coupon_amt)?$coupon_amt:"0.0",
				'Average_Maturity' 		=> isset($weightedAvgMaturity)?$weightedAvgMaturity.' Years':"NA",
			),
			'portfolio_characteristics1' => $characteristics,
			'index_details' => array(
				'Provider_Name' 	=> isset($overview->index_provider)?$overview->index_provider:"NA",
				'Index' 			=> isset($overview->index_fullname)?$overview->index_fullname:"NA",
				'Index_Ticker' 		=> isset($overview->related_index_ticker)?$overview->related_index_ticker:"NA",
				'Index_Market_Cap' 	=> isset($related_index)?'$'.$related_index:"$--",
			),
			
		);
	//echo "<pre>"; print_r($tableData);exit;
	return $tableData;
	}

	function truncate($number, $precision = 0) {
	   // warning: precision is limited by the size of the int type
	   $shift = pow(10, $precision);
	   return intval($number * $shift)/$shift;
	}
}

?>