<?php

get_header();
global $post;
$postID       = $post->ID;
$postTitle    = get_post_meta( $postID, 'ticker_name', true );
$terms        = get_the_terms( $post->ID , 'category' );
$categoryName = '';
if(!empty($terms) && $terms[0] != ''){
    $categoryName = $terms[0]->name;    
}

$creditRatingBreakdown  = get_post_meta( $postID, 'credit_rating_breakdown', true );
$performanceGrowth      = get_post_meta( $postID, 'performance_growth', true );
$performanceReturns     = get_post_meta( $postID, 'performance_returns', true );
$allRightsReserved      = get_post_meta( $postID, 'all_rights_reserved', true );
$investmentDisclaimer   = get_post_meta( $postID, 'investment_disclaimer', true );
$prospectus             = get_post_meta( $postID, 'prospectus', true );
$distributionSchedule   = get_post_meta( $postID, 'distribution_schedule', true );
$fundInformation        = get_post_meta( $postID, 'fund_information', true );
$sai                    = get_post_meta( $postID, 'sai', true );
$semiAnnualReport       = get_post_meta( $postID, 'semi_annual_report', true );
$disclosureTitle        = get_post_meta( $postID, 'disclosure_title', true );
$premium_discount       = get_post_meta( $postID, 'premium_discount', true );
$factsheet              = get_post_meta( $postID, 'factsheet', true );
$download_product_list  = get_post_meta( $postID, 'download_product_list', true );

$bblxApi         = new BBLX_APIDATA();
$bblxApiData     = $bblxApi->apiDataFetch($post);
$premiumDiscount = $bblxApi->apiPremiumDiscount($post);

//*************************************//

$bblxTableData    = new BBLX_ApiTableData();
$bblxApiTableData = $bblxTableData->getApiTableData($bblxApiData,$postTitle,$categoryName);
$basket_exposures = isset($bblxApiData['basket-exposures'])?(array)$bblxApiData['basket-exposures']: null;
$distribution     = isset($bblxApiData['distribution'])?(array)$bblxApiData['distribution']: null;
$overview         = isset($bblxApiData['overview'][0])?(array)$bblxApiData['overview'][0]: null;
$totalReturn      = isset($bblxApiData['total-return'])?(array)$bblxApiData['total-return']: null;
$holdings         = isset($bblxApiData['holdings'])?(array)$bblxApiData['holdings']: null;
$performanceData  = isset($bblxApiData['performance'])?(array)$bblxApiData['performance']: null;

//*************************************//

$distributionArr        = $bblxApi->distributionSortArray($distribution);

$discountStatistics     = $bblxApi->apiPremiumDiscountStatistics($post);
$discountStatisticsArr  = $bblxApi->premiumDiscountStatisticsTable($discountStatistics);
//$cumulativeArr          = $bblxApi->performanCecumulativeNav($bblxApiData);
$performenceDateArr     = $bblxApi->performanAsofDate($bblxApiData);

$annualQuarterData      = $bblxApi->annualQuarter($bblxApiData);

$perAsofDate = '';
if(!empty($performenceDateArr)){
    foreach($performenceDateArr as $k => $v){
        $perAsofDate = isset($v->asof_date)?date("M j, Y", strtotime($v->asof_date)):'';
    }
}

// Performance Data
if(!empty($performanceData)){
    foreach($performanceData as $key => $val){
        $performanceDetails[] = (array)$val;
        if(isset($val->display) && $val->display == "" ){
            $performanceDate = date('m/d/Y');
        }else{
            $performanceDate = $val->asof_date;
        }
    }
}

$topHolding = 'top_issuers';
if($postTitle == 'XEMD'){
    $topHolding = 'country';
}

//Premium Discount
if(isset($premiumDiscount) && !empty($premiumDiscount)){
    foreach($premiumDiscount as $key => $val){
        $premiumDiscountData[] = (array)$val;
    }    
}

// Holding
if(isset($holdings) && !empty($holdings)){
    foreach($holdings as $key => $val){
        $holdingArr[] = (array)$val;
    }
}

// Basket Exposures
if(isset($basket_exposures) && !empty($basket_exposures)){
    foreach($basket_exposures as $key => $val){
        $basketExposures[] = (array)$val;
    }    
}
if($postTitle == 'XEMD'){
    $metric_type = 'country';
}else if($postTitle == 'XHYE' || $postTitle == 'XHYH'){
    $metric_type = 'ml_industry_lvl_4';
}else{
    $metric_type = 'sector_exposure_breakdown';
}
if(!empty($totalReturn)){
    foreach($totalReturn as $key => $val){
        $totalReturnData[] = $val;
    }
}
//$holdings = $totalReturnData = $basketExposures = $premiumDiscountData = [];
$holdingAsOfDate = $holdings[0]->as_of_date;
$holdingAsOfDate = isset($holdingAsOfDate)?date('m/d/Y', strtotime($holdingAsOfDate)):"NA";

if(isset($holdingArr) && !empty($holdingArr)){
    $holdingJson = json_encode($holdingArr);    
}

if(isset($performanceDetails) && !empty($performanceDetails)){
    $performanceAsOfDate = isset($performanceDetails[0]['asof_date'])?date("M j, Y", strtotime($performanceDetails[0]['asof_date'])):'';
}
if(isset($totalReturnData) && !empty($totalReturnData)){
    $totalReturnData = json_encode($totalReturnData);    
}
if(isset($basketExposures) && !empty($basketExposures)){
    $basketExposures = json_encode($basketExposures);
}
if(isset($premiumDiscountData) && !empty($premiumDiscountData)){
    $premiumDiscountData = json_encode($premiumDiscountData);    
}

$sec_yield_asof_date = $asOfDate = $netExpenseRatio = $symbol = "NA";
$aumSum = "$--";
if(!empty($overview)){
    $asOfDate = isset($overview['asof_date'])?date('m/d/Y', strtotime($overview['asof_date'])):"NA";
    $sec_yield_asof_date = isset($overview['sec_yield_asof_date'])?date('m/d/Y', strtotime($overview['sec_yield_asof_date'])):"NA";
    //$netExpenseRatio = number_format(($overview['net_expense_ratio']*100)).'%';
    $netExpenseRatio = floatval($overview['net_expense_ratio']*100);
    $charLength = strlen($netExpenseRatio);
    if($charLength == 3){
        $netExpenseRatio = $netExpenseRatio.'0';
    }
    $aum = $bblxApi->numberShorten($overview['aum']);
    if(isset($aum) && $aum != ""){
        $aumSum = substr($aum, 0, -1);
        $symbol = substr($aum, -1);    
    }
}
$treasuriesClass = 'tabcontent';
$activeClass = '';
$navpopup = "nav-popup";
$market_place = "market-price";
if($categoryName == 'Treasuries'){
    $treasuriesClass = 'treasuries';
    $activeClass = 'active';
    $navpopup = "nav-treasuries-popup";
    $market_place = "t-market-price";
}
?>

<!-- section-->
<section class="section cyclicals_bg_img" id="overview">
    <div class="custom-wapper">
        <div class="custom-band">
            <div class="custom-box">
                <div class="custom-section">
                    <div class="custom_container">
                        <div class="xhyc_box">
                            <div class="xhyc_wapper">
                                <div class="xhyc_band">
                                    <div class="xhyc_tag">
                                        <span class="bblx_ticker_title"><?php echo isset($overview)?$overview['ticker']:"NA"; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="title_box">
                            <div class="title_wapper">
                                <div class="title_band">
                                    <h1><span class="productname"><?php echo isset($overview)?$overview['fund_name']:"NA"; ?></span></h1>
                                </div>
                            </div>
                        </div>
                        <div class="btn_wapper">
                            <div class="btn_band">
                                <div class="btn_group">
                                    <a href="<?php echo $prospectus; ?>" class="bondbloxx_btn" target="_blank" role="button">
                                        <span class="btn-wapper"> 
                                            <span class="btn-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="23.606" viewBox="0 0 18 23.606"><g id="white_download_icon" data-name="white download icon" transform="translate(0 0)"><path id="Path_548" data-name="Path 548" d="M0,0H18" transform="translate(0 22.856)" fill="none" stroke="#00eeca" stroke-width="1.5"></path><g id="Group_121" data-name="Group 121" transform="translate(5.112)"><path id="Path_467" data-name="Path 467" d="M-401.9,274.018h3.1V261.059h1.567v12.959h3.1l-3.888,5.821Z" transform="translate(401.901 -261.059)" ></path></g></g></svg></span>
                                            <span class="btn-text">Download Prospectus</span>
                                        </span>
                                    </a>
                                </div>
                                <div class="btn_group">
                                    <a href="javascript:void(0);" class="bondbloxx_btn popup_one"  role="button">
                                        <span class="btn-wapper">
                                            <span class="btn-icon"> <svg xmlns="http://www.w3.org/2000/svg" id="white_arrow" data-name="white arrow" width="40" height="7.978" viewBox="0 0 40 7.978"><path id="Path_2" data-name="Path 2" d="M-465.363,259.978v-3.185H-499.17v-1.607h33.807V252l6.193,3.989Z" transform="translate(499.17 -252)" ></path></svg></span>
                                            <span class="btn-text">Document Library</span>
                                        </span>
                                    </a>
                                </div>
                                <div class="btn_group">
                                    <?php 
                                    if($factsheet !="") { ?>
                                        <a href="<?php echo site_url('/').$factsheet?>" class="bondbloxx_btn" role="button">
                                            <span class="btn-wapper">
                                                <span class="btn-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="23.606" viewBox="0 0 18 23.606"><g id="white_download_icon" data-name="white download icon" transform="translate(0 0)"><path id="Path_548" data-name="Path 548" d="M0,0H18" transform="translate(0 22.856)" fill="none" stroke="#00eeca" stroke-width="1.5"></path><g id="Group_121" data-name="Group 121" transform="translate(5.112)"><path id="Path_467" data-name="Path 467" d="M-401.9,274.018h3.1V261.059h1.567v12.959h3.1l-3.888,5.821Z" transform="translate(401.901 -261.059)" ></path></g></g></svg></span>
                                                <span class="btn-text">Download Factsheet</span>
                                            </span>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- section-->
<section class="blue_section section" id="fundinformation">
    <div class="custom-container">
        <div class="portfolio_wapper">
            <div class="bond_band_col_wapper left-bar"> 
                <div class="bond_band_col">
                    <div class="bond_band">
                        <h4>Net asset value</h4>
                        <strong>$<?php echo isset($overview['nav'])?number_format($overview['nav'],2):"NA"; ?></strong>
                        <?php /* <small>AS OF DATE <?php //echo $asOfDate; </small> */?>
                        <!-- <small>ALL DATA AS OF <?php echo $asOfDate; ?>,</small><small> UNLESS OTHERWISE STATED</small> -->
                    </div>
                </div>
                <div class="bond_band_col">
                    <div class="bond_band">
                        <h4>EXPENSE RATIO</h4>
                        <strong><?php echo $netExpenseRatio; ?>%</strong>
                    </div>
                </div>
            </div>
            <div class="bond_band_col_wapper right-bar"> 
                <div class="bond_band_col">
                    <div class="bond_band">
                        <h4>Yield to Maturity</h4>
                        <strong>
                            <?php
                                if($postTitle == "XEMD"){
                                    echo isset($overview['stripped_ytm'])?number_format(($overview['stripped_ytm'] * 100),2).'%':"NA";
                                }else{
                                    echo isset($overview['average_yield_to_maturity'])?number_format(($overview['average_yield_to_maturity'] * 100),2).'%':"NA";
                                }
                            ?>
                            </strong>
                    </div>
                </div>
                <div class="bond_band_col">
                    <div class="bond_band">
                        <h4>30-Day Sec Yield</h4>
                        <strong>
                            <?php 
                            echo isset($overview['sec_yield_30d_subsidized'])?number_format(($overview['sec_yield_30d_subsidized'] * 100),2).'%':"NA";
                            ?>
                            </strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="bond_box_content">
           <i> ALL DATA AS OF <?php echo $asOfDate; ?>, UNLESS OTHERWISE STATED. 30-DAY SEC YIELD AS OF <?php echo $sec_yield_asof_date; ?>. The performance data quoted represents past performance and is no guarantee of future results. Investment return and principal value of an investment will fluctuate so that an investor’s shares, when redeemed, may be worth more or less than their original cost. Current performance may be lower or higher than the performance data quoted. For the most recent month-end performance please visit the performance section of this page.</i>
        </div>
    </div>
</section>
<!-- section-->
<section class="tab_section animated fadeInUp">
    <div class="custom-container">
        <ul>
            <li class="current"><a href="#overview">Overview</a></li>
            <li><a href="#fundinformation">Fund Information</a></li>
            <li><a href="#portfolio">Portfolio</a></li>
            <li><a href="#holdings">Holdings</a></li>
            <li><a href="#performance">Performance</a></li>
        </ul>
    </div>
</section>
<!-- section-->
<section class="section">
    <div class="tab_wapper">
        <div class="fund_information">
            <div class="custom-container">
                <div class="wapper_row">
                    <div class="left_band bar_chart">
                        <div class="content_band p-10">
                            <h2>Overview</h2>
                            <?php
                                $asOf = '';
                                $string = trim($post->post_content);
                                $newstring = substr($string, -5);
                                if($newstring == 'as of'){
                                    $asOf = $asOfDate;
                                }
                            ?>
                            <p><?php echo $post->post_content; ?> <?php echo $asOf; ?></p>
                        </div>
                        <div class="pieChart_section" style="<?php echo ($categoryName == 'Treasuries') ? 'display: none' : 'display: block'; ?>" >
                            <div class="text_title">
                                <?php 
                                    if($postTitle == 'XEMD'){
                                        echo '<h2>Regional Breakdown %</h2>';
                                    }else{
                                        echo '<h2>Sub-Sector</h2>';
                                    }
                                ?>
                            </div>
                            <div class="card_band chart_sec">
                                <div id="pieChart" class="pieChart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="right_band">
                        <div class="text_title">
                            <h2>Fund Details</h2>
                        </div>
                        <div class="card_band">
                            <table>
                                <tbody>
                                    <?php 
                                        if(!empty($bblxApiTableData['fund_details'])){
                                            foreach($bblxApiTableData['fund_details'] as $tableKey => $tableVal){
                                                $tableKey = str_replace('_', ' ', $tableKey);
                                                $popup = '';
                                                if ($tableKey != "NAIC Rating")
                                                { ?>
                                                    <tr>    
                                                        <td><?php echo $tableKey.$popup; ?></td>
                                                        <td><span><?php echo $tableVal; ?></span></td>
                                                    </tr>
                                                <?php }
                                                /* if($tableKey == 'NAIC Rating'){
                                                    $popup = '<a href="javascript:void(0)" id="naic-iball"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                } */?>
                                                
                                        <?php }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="fund_details">
                            <div class="text_title">
                                <h2>Fees</h2>
                            </div>
                            <div class="card_band fees_sec_text">
                                <table>
                                    <tbody>
                                        <?php 
                                            if(!empty($bblxApiTableData['fees'])){
                                            foreach($bblxApiTableData['fees'] as $feesKey => $feesVal){
                                                $feesKey = str_replace('_', ' ', $feesKey);
                                                $popup = '';
                                                if($feesKey == 'Acquired Fund Fees and Expenses'){
                                                    $popup = '<a href="javascript:void(0)" class="" id="affe-iball"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                }else if($feesKey == 'Expense Ratio'){
                                                    $popup = '<a href="javascript:void(0)" class="" id="er-iball"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                }
                                                ?>
                                                <tr>    
                                                    <td class="bold"><?php echo $feesKey.$popup ?></td>
                                                    <td class="bold"><?php echo $feesVal; ?></td>
                                                </tr>
                                        <?php }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>      
        <div class="portfolio" id="portfolio">
            <div class="portfolio_sec">
                <div class="blue_band animated fadeInLeft"></div>
                <div class="custom-container">
                    <h2>Portfolio Characteristics</h2>
                    <div class="portfolio_content">
                        <div class="portfolio_wapper">
                            <div class="half_sec">
                                <div class="card_band">
                                    <table>
                                        <tbody>
                                        <?php 
                                        if(!empty($bblxApiTableData['portfolio_characteristics'])){
                                            foreach($bblxApiTableData['portfolio_characteristics'] as $key => $val){
                                                $key = str_replace('_', ' ', $key);
                                                $popup = '';
                                                if($key == 'Average Coupon'){
                                                    $popup = '<a href="javascript:void(0)" id="ac-iball"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                }else if($key == 'Average Maturity'){
                                                    $popup = '<a href="javascript:void(0)" id="am-iball"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                }else if($key == 'Number of Countries'){
                                                    $popup = '<a href="javascript:void(0)" id="iball-countries"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                }else if($key == 'Number of Issuers'){
                                                    $popup = '<a href="javascript:void(0)" id="noi-iball"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                }
                                                ?>
                                                <tr>    
                                                    <td><?php echo $key.$popup ?></td>
                                                    <td><?php echo $val; ?></td>
                                                </tr>
                                        <?php }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <span style="color:#A0A0A0; font-size:13px;font-family: 'New Frank',Sans-serif;font-weight: 400;">* 30-Day SEC Yield is as of <?php echo $sec_yield_asof_date; ?></span>
                                </div>
                            </div>
                            <div class="half_sec">
                                <div class="card_band">
                                    <table>
                                        <tbody>
                                            <?php 
                                                if(!empty($bblxApiTableData['portfolio_characteristics1'])){
                                                    foreach($bblxApiTableData['portfolio_characteristics1'] as $key => $val){
                                                    $key = str_replace('_', ' ', $key);
                                                    $popup = '';
                                                    if($key == 'Yield to Maturity'){
                                                        $popup = '<a href="javascript:void(0)" id="ytm-iball"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                    }else if($key == 'Yield to Worst'){
                                                        $popup = '<a href="javascript:void(0)" id="ytw-iball"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                    }else if($key == 'Option Adjusted Spread'){
                                                        $popup = '<a href="javascript:void(0)" id="oas-iball"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                    }else if($key == 'Spread to Worst'){
                                                        $popup = '<a href="javascript:void(0)" id="iball-spread-to-worst"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                    }else if($key == 'Spread Duration'){
                                                        $popup = '<a href="javascript:void(0)" id="sd-iball"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                    }else if($key == '30-Day SEC Yield'){
                                                        $popup = '<span class="asterisk">*</span>';
                                                    }
                                                    $class = '';
                                                    if(($categoryName == 'Treasuries') && (($key == 'Yield to Worst') || ($key == 'Spread to Worst') || ($key == 'Spread Duration'))){
                                                        $class = 'class="treasuries-cat"';
                                                    }
                                                    ?>
                                                    <tr <?php echo $class; ?>>    
                                                        <td><?php echo $key.$popup; ?></td>
                                                        <td><?php echo $val; ?></td>
                                                    </tr>
                                            <?php }
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fund_information">
                <div class="custom-container">
                    <div class="wapper_row">
                        <div class="left_band p-10">
                            <div class="text_title">
                                <h2>Portfolio Breakdown</h2>
                            </div>
                            <div class="card_band portfolio_bar">
                                <table id="portTable">
                                </table>
                                <span style ="color:#A0A0A0; font-size:13px;font-family: 'New Frank',Sans-serif;font-weight: 400;">Due to rounding, these values may exceed 100%</span>
                            </div>
                            <div class="text_title">
                                <h2>Index Details</h2>
                            </div>
                            <div class="card_band portfolio__band">
                                <table>
                                    <tbody>
                                        <?php 
                                            if(!empty($bblxApiTableData['index_details'])){
                                                foreach($bblxApiTableData['index_details'] as $key => $val){
                                                $key = str_replace('_', ' ', $key);
                                                $popup = '';
                                                if($key == 'Index Market Cap'){
                                                    $popup = '<a href="javascript:void(0)" id="imc-iball"><span class="fas fa-info-circle infoIcon"></span></a>';
                                                }
                                                ?>
                                                <tr>    
                                                    <td><?php echo $key.$popup ?></td>
                                                    <td><?php echo $val; ?></td>
                                                </tr>
                                        <?php }
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="right_band m-10">
                            <div class="text_title">
                                <h2>Maturity Breakdown %</h2>
                            </div>
                            <div class="card_band p-0 matGraph_sec">
                                <div id="matGraph" class="matGraph"></div>
                            </div>
                            <div class="text_title credit_rating"  style="<?php echo ($categoryName == 'Treasuries') ? 'display: none' : 'display: block'; ?>">
                                <h2>Credit Rating Breakdown %</h2>
                            </div>
                            <div class="card_band p-0"   style="<?php echo ($categoryName == 'Treasuries') ? 'display: none' : 'display: block'; ?>">
                                <div id="barGraph" class="barGraph"></div>
                            </div>
                            <div class="details_band"   style="<?php echo ($categoryName == 'Treasuries') ? 'display: none' : 'display: block'; ?>">
                                <p><?php echo $creditRatingBreakdown; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
        <div class="holdings_sec" id="holdings">
            <div class="title">
                <div class="custom-container">  
                    <div class="holdings_wapper">
                        <h2>Holdings</h2>
                    </div>
                </div>
            </div>
            <div class="gray_band">
                <div class="custom-container">  
                    <div class="tab_sec">
                        <div class="tabContainer">
                            <div class="tab active">
                                <?php 
                                $active = $activate = '';
                                if($categoryName == 'Treasuries'){
                                    $active = 'active';
                                }
                                
                                if($categoryName == 'High Yield Ratings'){
                                    $activate = 'active';
                                }
                                if($categoryName == 'High Yield Sectors'){
                                    $activate = 'active';
                                }
                                $stockTitle = 'Top 10 Issuers';
                                if($postTitle == 'XEMD') {
                                    $stockTitle = 'Top 10 Countries';
                                    $activate = 'active';
                                    
                                }?>
                                
                                <button class="tablinks oneclicksb <?php echo $activate; ?>" data-class="top10Stocks" style="<?php echo ($categoryName == 'Treasuries') ? 'display: none' : 'display: block'; ?>">
                                    <?php echo $stockTitle; ?>
                                </button>
                                <button data-class="allStocks" class="oneclicksb tablinks allH <?php echo $active; ?>">All Holdings</button>
                            </div>
                                <div id="top10Stocks" class="tabcontent" style="<?php echo ($categoryName == 'Treasuries') ? 'display: none' : 'display: block'; ?>">
                                    <div id="top10Table" class="tabList"></div>
                                </div>
                            <div id="allStocks" class="tabcontent" style="<?php echo ($categoryName != 'Treasuries') ? 'display: none' : 'display: block'; ?>">
                                <div id="table" class="tabList"></div>
                            </div>
                        </div>
                        <div class="tab_content">
                            <div class="button_wapper">
                                <button class="bondbloxx-btn downloadCsv" onclick="downloadCsv()"> Download CSV
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" id="white_arrow" data-name="white arrow" width="40" height="7.978" viewBox="0 0 40 7.978"><path id="Path_2" data-name="Path 2" d="M-465.363,259.978v-3.185H-499.17v-1.607h33.807V252l6.193,3.989Z" transform="translate(499.17 -252)"></path></svg>
                                    </span>
                                </button>
                                <div class="sub_date">As of <span class="asOfDate"><?php echo $holdingAsOfDate; ?></span> and subject to change.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="performance_sec" id="performance">
            <div class="title">
                <div class="custom-container">  
                    <h2>Performance </h2>
                </div>
            </div>
            <div class="first_sec">
                <div class="custom-container">  
                    <div class="wapper_row">
                        <div class="left_sec">
                            <div class="text_title">
                                <h2>Growth Of $10,000 Since Inception <a href="javascript:void(0)" id="go10k-iball"><span class="fas fa-info-circle infoIcon"></span></a></h2>
                            </div>
                            <div class="card_band p-0">
                                <div id="lineChart" class="lineChart"></div>
                            </div>
                            <div class="details_band">
                                <p><?php echo $performanceGrowth; ?></p>
                            </div>
                        </div>
                        <div class="right_sec">
                            <div class="text_title">
                                <h2>Distributions</h2>
                            </div>
                            <div class="dividends-band">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Ex-Date</th>
                                            <th>Record Date</th>
                                            <th>Pay Date</th>
                                            <th>Distribution Paid<br> (Per Share)</th>
                                        </tr>       
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if(isset($distributionArr) && !empty($distributionArr)){
                                            foreach($distributionArr as $key => $val){ 
                                                $ex_date = isset($val->ex_date)?date("M j, Y", strtotime($val->ex_date)):'-';
                                                $record_date = isset($val->record_date)?date("M j, Y", strtotime($val->record_date)):'-';
                                                $pay_date = isset($val->pay_date)?date("M j, Y", strtotime($val->pay_date)):'-';
                                                $dividend_income = isset($val->dividend_income)?$val->dividend_income:"-";

                                                ?>
                                                <tr>
                                                    <td><?php echo $ex_date; ?></td>
                                                    <td><?php echo $record_date; ?></td>
                                                    <td><?php echo $pay_date; ?></td>
                                                    <td><?php echo number_format($dividend_income,5); ?></td>
                                                </tr>

                                        <?php }
                                         }else{ ?>
                                            <tr>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="second_sec dropdown-custom">
                <div class="custom-container">  
                    <div class="performance_returns">
                        <div class="text_title">
                            <h2>Performance Returns</h2>
                        </div>
                        <div class="main-table">
                            <div class="table-title">
                                Average Annual
                            </div>
                           
                            <div class="annual-wapper">
                                <div class="annual-loader" >
                                </div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="nice-select-flex">
                                                <span class="nice-select-txt">As of</span>
                                                <select class="nice_select average_date">
                                                  <?php 

                                                    foreach($annualQuarterData as $key => $val){ 
                                                        $quarterNo = $val['quarterNo'];
                                                        $quarter = $val['quarter'];
                                                        $class = $val['class'];
                                                        $currentQuarter = $val['currentQuarter'];
                                                    ?>
                                                        <option <?=$class; ?> data-value="<?=$currentQuarter; ?>" value="<?=$quarterNo; ?>"><?php echo $quarter; ?></option>
                                                    <?php 
                                                         } ?>
                                                </select>
                                                </div>
                                            <th>1yr</th>
                                            <th>2yr</th>
                                            <th>3yr</th>
                                            <th>Incept.</th>
                                        </thead>
                                        <tbody class="quarter-data">
                                          <?php 
                                            $data =$bblxApi->performanceRecords($bblxApiData, 'annual');
                                            foreach($data as $val){
                                                echo $val;
                                            }
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="main-table cumulative-tab">
                            <div class="table-title">
                                Cumulative Performance
                            </div>
                            <table>
                                <tbody>
                                    <tr>
                                        <th><span class="aoPr">As of </span><span class="asofP"><?php echo $perAsofDate; ?></span></th>
                                        <th>WTD</th>
                                        <th>MTD</th>
                                        <th>QTD</th>
                                        <th>YTD</th>
                                    </tr>
                                    <?php 
                                        $data =$bblxApi->performanceRecords($bblxApiData, 'cumulative');
                                        foreach($data as $val){
                                            echo $val;
                                        }
                                         ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="details_band performance_dec">
                    <p><?php echo $performanceReturns; ?></p>
                </div>
                <div class="custom-container">
                    <div class="premium_discount">
                        <div class="text_title">
                            <h2>Premium/Discount</h2>
                        </div>
                        <div class="card_band">  
                            <table>
                                <tbody>
                                    <tr>
                                        <th class="bold">Number of Days At</th>
                                        <th class="bold"><?php echo date('Y') - 1; ?></th>
                                        <th class="bold">Q1 <?php echo date('Y'); ?></th>
                                        <th class="bold">Q2 <?php echo date('Y'); ?></th>
                                        <th class="bold">Q3 <?php echo date('Y'); ?></th>
                                        <th class="bold">Q4 <?php echo date('Y'); ?></th>
                                    </tr>
                                    <tr>
                                    <?php 
                                    $returnTable = array(
                                        'tradeday_premium_all' => 'Premium',
                                        'tradeday_premium_discount_eq_0' => 'NAV',
                                        'tradeday_discount_all' => 'Discount',
                                    );
                                    foreach ($returnTable as $key => $value) {
                                        $val = array('-','-','-','-');
			                            $index = 0;
                                        foreach ($position as $pos) {
                                            
                                        }
                                    }
                                    
                                    for($j = 1; $j < 4; $j++){ 
                                        if( $j == 1 ){
                                            echo '<td class="bold">Premium</td>';
                                        }else if( $j == 2 ){
                                            echo '<td class="bold">NAV</td>';
                                        }else if( $j == 3 ){
                                            echo '<td class="bold">Discount</td>';
                                        }
                                        $i = 1;
                                        if(!empty($discountStatisticsArr)){
                                            foreach($discountStatisticsArr as $key => $val){
                                                if(!empty($val)){
                                                    foreach($val as $k => $v){
                                                        if( $j == 1 ){
                                                            $discount = $v->tradeday_premium_all;
                                                            $class = 'nav'.$i.'yr';
                                                        }else if( $j == 2 ){
                                                            $discount = $v->tradeday_premium_discount_eq_0;
                                                            $class = 'mp'.$i.'yr';
                                                        }else if( $j == 3 ){
                                                            $discount = $v->tradeday_discount_all;
                                                            $class = 'i'.$i.'yr';
                                                        } 
                                                        ?>
                                                        <td><span class="<?php echo $class; ?>">
                                                            <?php
                                                                if($discount != null && $discount != 0){
                                                                    echo $discount;
                                                                }else{
                                                                    echo "--";
                                                                }
                                                            ?>
                                                        </span></td>
                                                 <?php $i++; }
                                                }else{
                                                    echo "<td>--</td>";        
                                                }
                                            }
                                        }else{
                                            echo "<td>--</td>";
                                            echo "<td>--</td>";
                                            echo "<td>--</td>";
                                            echo "<td>--</td>";
                                        }
                                    ?>
                                </tr>
                                <?php }
                                 ?>
                                    
                                </tbody>
                            </table>
                            <div class="premium-discount" id="premium-discount"> </div>
                        </div>
                    </div>
                </div>
                <div class="custom-container">  
                    <div class="performance_content">
                        <p>© <?php echo date('Y'); ?> BondBloxx, Inc. All rights reserved</p>
                        <p><?php echo $investmentDisclaimer; ?></p>
                        <a href="#overview" class="bondbloxx-btn green-btn" role="button">Back to Top </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- modal-->
<div class="modal_section popup_box_one">
    <div class="modal_wapper">
        <div class="modal_content">
            <h3 class="document-library-title">Document Library</h3>
            <div class="close"><img src="https://bondbloxxstg.wpengine.com/wp-content/uploads/2023/01/close-btn.png"></div>
            <div class="dialog-list">
                <?php 
                    $saiTitle = 'Statement of Additional Information';
                    $ProspectusTitle = "Prospectus";
                    if($categoryName == "High Yield Ratings" || $categoryName == "Treasuries"){
                        $ProspectusTitle = "Prospectus and SAI";
                    }
                ?>
                <div class="">
                <?php echo do_shortcode('[doc_library layout="table"]'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    if($categoryName == "Treasuries"){
        $value = "document-library-files";
    }else if($categoryName == "High Yield Sectors"){
        $value = "document-library-sectors";
    }else if($categoryName == "High Yield Ratings"){
        $value = "document-library-ratings";
    }else{
        $value = "document-library-markets";
    }

?>
<script>
jQuery(document).ready(function(){
    jQuery('.nice_select').niceSelect();
    jQuery(document).on('click', '.dlp-preview-modal-close', function(){
        if (jQuery(this).closest(".dlp-preview-modal").hasClass("is-open")) {
            jQuery(this).closest(".dlp-preview-modal").removeClass("is-open")
        }
    });
    jQuery('td a.bblx_subcategory').on('click', function(){
        var dropdownSelectSubValue = '';
        var categoryClass = jQuery(this).attr('class');
        var categoryText = jQuery(this).text();
        console.log('categoryText==',categoryText);
        strValue = categoryClass.replace("bblx_subcategory ", "");
        strValue = jQuery.trim(strValue);
        console.log('categoryClass==',strValue);

        if(strValue == 'treasuries' && categoryText == "Document Library"){
            dropdownSelectSubValue = "document-library-files";
        }else if(strValue == 'treasuries' && categoryText == "Factsheet Files"){
            dropdownSelectSubValue = "factsheet-files-treasuries";
        }else if(strValue == 'high_yield_sectors' && categoryText == "Document Library"){
            dropdownSelectSubValue = "document-library-ratings";
        }else if(strValue == 'high_yield_sectors' && categoryText == "Factsheet Files"){
            dropdownSelectSubValue = "factsheet-files-sectores";
        }else if(strValue == 'high_yield_ratings' && categoryText == "Document Library"){
            dropdownSelectSubValue = "document-library-ratings";
        }else if(strValue == 'high_yield_ratings' && categoryText == "Factsheet Files"){
            dropdownSelectSubValue = "factsheet-files-ratings";
        }else if(strValue == 'emerging_markets' && categoryText == "Document Library"){
            dropdownSelectSubValue = "document-library-markets";
        }else if(strValue == 'emerging_markets' && categoryText == "Factsheet Files"){
            dropdownSelectSubValue = "factsheet-files-markets";
        }
        getDropDownSelectedValue(dropdownSelectSubValue);
    });
    jQuery('td a.bblx_category').on('click', function(){
        var dropdownSelectValue = '';
        var categoryClass = jQuery(this).text();
        categoryClass = categoryClass.replace(/,/g, "");
        categoryClass = jQuery.trim(categoryClass);
        if(categoryClass == 'Treasuries'){
            dropdownSelectValue = "treasuries";
        }else if(categoryClass == 'High Yield Sectors'){
            dropdownSelectValue = "high-yield-sectors";
        }else if(categoryClass == 'High Yield Ratings'){
            dropdownSelectValue = "high-yield-ratings";
        }else{
            dropdownSelectValue = "emerging-markets";
        }
        getDropDownSelectedValue(dropdownSelectValue);
    });

    var dropdownValue = '<?php echo $value; ?>';
    var categoryName = '<?php echo $categoryName; ?>';
    jQuery('a.popup_one').on('click', function(){
        jQuery('body').addClass('document_popup');
        getDropDownSelectedValue(dropdownValue);
    });
    jQuery(document).on('click','.overlay, .close', function(){
        jQuery('body').removeClass('document_popup');
    });
    jQuery('.annual-loader').hide();
    var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
    var performanceData = <?php echo json_encode($performanceData); ?>;
    jQuery('.average_date').change(function(){
        //var selectedVal = jQuery('option:selected', jQuery(this)).text();
        var selectedVal = jQuery('option:selected').val();
        var quarterNumber = jQuery(this).find(':selected').data('value');
        jQuery.ajax({
          method: "POST",
          beforeSend: function(){
            jQuery('.annual-loader').show();
         },
          url: ajax_url,
          data: {
                action: 'getPerformanceValue',
                quaterName: selectedVal,
                qNumber: quarterNumber,
                performance: performanceData,
            },
            success:function(response){
                jQuery('.performanceRes').hide();
                jQuery('.quarter-data').html(response);
            },
            complete: function(){
                jQuery('.annual-loader').hide();
            },
        });
    });
    const barGraph = <?php echo isset($basketExposures)?$basketExposures:[]; ?>;
    const metric_type = '<?php echo $metric_type; ?>';
    

    BBLXPortfolioBreakdownData(barGraph);

    BBLXmatGraphData(barGraph);
    //********************************//
    const totalReturnData = <?php echo $totalReturnData; ?>;
    BBLXtotalReturnData(totalReturnData);

    BBLXPieChartData(barGraph,metric_type);
    //********************************//
    const topHolding = '<?php echo $topHolding; ?>';
    

    //********************************//
    const holding = <?php echo $holdingJson; ?>;
    jQuery('.downloadCsv').on('click', function(){
        downloadHoldingCsv(holding);
    });
    BBLXTableData(holding, barGraph, topHolding);
    //********************************//

    const premiumDiscount = <?php echo $premiumDiscountData; ?>;
    BBLXPremDisDataTable(premiumDiscount);
    
    BBLXbarGraph(barGraph);

    jQuery(".oneclicksb").click(function(event){
        var className = jQuery(this).attr('data-class');
        changeTab(event, className);
    });
});
function getDropDownSelectedValue(dropdownValue){
    console.log('=====' + dropdownValue);
    setTimeout(function() {
        jQuery('select[name="ptp_filter_doc_categories"]').val(dropdownValue).trigger("change");
    }, 200);
}

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>


<?php 

get_footer();

?>