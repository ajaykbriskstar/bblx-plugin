	const apiOverview = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=overview&ticker=IBD';
	const apiFlows = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=flows&ticker=IBD';
	const apiDist = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=distribution&ticker=IBD';
	const apiIndex = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=overview&ticker=JDCI';

jQuery(document).ready(function() {



		jQuery.getJSON(apiOverview, function(data){

// API FUNCTION - Overview
			jQuery(".ticker, .ticker2").html(data[0].ticker); // Ticker
			jQuery(".productName").html(data[0].fund_name); // Product Name
			let navData = data[0].nav;
					navData = navData.toFixed(2);
			jQuery(".navData").html(navData); // NAV
			let asOfDate = data[0].asof_date;
					asOfDate = new Date(asOfDate).toLocaleString('en-US');
					asOfDate = asOfDate.split(',')[0];
			jQuery(".asOfDate").html(asOfDate); // As of Date - Formatted
			jQuery(".expRatio").html(data[0].net_expense_ratio)
			jQuery(".cusip").html(data[0].cusip); //cusip
			jQuery(".assetClass").html(data[0].assetclass); //asset class
			let fid = data[0].inceptiondate;
					fid = new Date(fid).toLocaleString('en-US');
					fid = fid.split(',')[0];
			jQuery(".fid").html(fid);//fund inception date * NEED TO FORMAT
			jQuery(".exchange").html(data[0].primary_exchange);//exchange
			/* jQuery(".distFreq").html(data[0].cusip);//distribution frequency * UNKNOWN IN API */
			/* jQuery(".netAssets").html(data[0].cusip);//net assets * UNKNOWN IN API - ?AUM? */
			/* jQuery(".naicRating").html(data[0].cusip);//naic rating * UNKNOWN IN API */
			jQuery(".closingP").html(data[0].adjclose);//closing price as of * USING adjclose - MAKE SURE THIS IS RIGHT

		});

// API FUNCTION - Flows
        jQuery.getJSON(apiFlows, function(data){
            let aum = data[0].aum;
                aum = (nFormatter(data[0].aum, 2));
                aum = aum.split(/(\d+)/);
            jQuery(".fA1").html(aum[1]); // Fund Assets
            jQuery(".fA2").html(aum[2]); // Fund Assets
            jQuery(".fA3").html(aum[3]); // Fund Assets
            jQuery(".fundAssetsPost").html(aum[4]); // Fund Assets Donmination
        });

// API FUNCTION - Distribution
     	jQuery.getJSON(apiDist, function(data){
				let exDate = data[data.length - 1].ex_date;
						exDate = new Date(exDate).toLocaleString('en-US');
						exDate = exDate.split(',')[0];
				jQuery(".ex_date").html(exDate); // Ex Date - Formatted
				let reDate = data[data.length - 1].record_date;
						reDate = new Date(reDate).toLocaleString('en-US');
						reDate = reDate.split(',')[0];
				jQuery(".record_date").html(reDate); // Ticker - Formatted
				let payDate = data[data.length - 1].pay_date;
						payDate = new Date(payDate).toLocaleString('en-US');
						payDate = payDate.split(',')[0];
				jQuery(".pay_date").html(payDate); // Ticker
				jQuery(".dividend_income").html(data[data.length - 1].dividend_income); // Ticker
			});

// API FUNCTION - Index
     	jQuery.getJSON(apiIndex, function(data){
				jQuery(".pName").html(data[0].index_provider);//provider name
				jQuery(".pIndex").html(data[0].index_fullname);//index name
				jQuery(".itc").html(data[0].related_index_ticker);//related index ticker
				jQuery(".bbtc").html(data[0].related_index_bbg_ticker);//bloomb name
				let imCap = data[0].related_index_market_cap_usd;
						imCap = (nFormatter(data[0].related_index_market_cap_usd, 2));
				jQuery(".imCap").html(imCap);//index market cap
			});

// API FUNCTION - Portfolio Characteristics
jQuery.getJSON(apiIndex, function(data){
	jQuery(".pc1").html(data[0].basket_analytics_securities_count);//basket_analytics_securities_count
	jQuery(".pc2").html(data[0].basket_analytics_issuer_count);//basket_analytics_issuer_count
	let pc3 = data[0].weighted_average_coupon;
			pc3 = (nFormatter(data[0].weighted_average_coupon, 2));
	jQuery(".pc3").html(pc3);//weighted_average_coupon
	let pc4 = data[0].weighted_average_maturity;
			pc4 = (nFormatter(data[0].weighted_average_maturity, 2));
	jQuery(".pc4").html(pc4);//weighted_average_maturity
	let pc5 = data[0].average_yield_to_maturity;
			pc5 = (nFormatter(data[0].average_yield_to_maturity, 2));
	jQuery(".pc5").html(pc5);//average_yield_to_maturity
	jQuery(".pc6").html(data[0].yield_to_worst);//yield_to_worst
	let pc7 = data[0].weighted_average_option_adjusted_spread;
			pc7 = (nFormatter(data[0].weighted_average_option_adjusted_spread, 2));
	jQuery(".pc7").html(pc7);//weighted_average_option_adjusted_spread
	jQuery(".pc8").html(data[0].weighted_average_spread_duration);//weighted_average_spread_duration
	jQuery(".pc9").html(data[0].sec_yield_30d_subsidized);//sec_yield_30d_subsidized
	jQuery(".pc10").html(data[0].basket_analytics_securities_count);//basket_analytics_securities_count
});

});

getBarGraphData();
getPortfolioBreakdownData();
getPieChartData();
getTableData();
getInvestmentGrowth();
getMatGraphData();
getPremDisDataTable();
getPreformanceReturnsTable();
nFormatter();

async function getBarGraphData() {
    const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=basket-exposures&ticker=IBD';
    const response = await fetch(api);
    const data = await response.json();
    let barGraph = {
        labels: [],
        points: []
    };
    let sortedByCategory = [];
    for( i = 0; i < data.length; i++ ) {
        if( sortedByCategory[data[i].metric_type] == undefined ){
            sortedByCategory[data[i].metric_type] = [];
        }
        sortedByCategory[data[i].metric_type].push(data[i]);
    }
    for (i = 0; i < sortedByCategory.credit_ratings.length; i++){
        barGraph.labels.push(sortedByCategory.credit_ratings[i].basket_metric)
        barGraph.points.push(Math.round(sortedByCategory.credit_ratings[i].basket_exposure * 100))
    }
    new Highcharts.chart('barGraph', {
        chart: {
            type: 'bar',
            backgroundColor: '#fff'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: barGraph.labels,
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: '',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            enabled: false,
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: false
                }
            }
        },
        legend: { enabled:false },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Perecentage',
            color: '#24a5e0',
            data: barGraph.points
        }]
    });
};

async function getMatGraphData() {
    const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=basket-exposures&ticker=IBD';
    const response = await fetch(api);
    const data = await response.json();
    let matGraph = {
        labels: [],
        points: []
    };
    let sortedByCategory = [];
    for( i = 0; i < data.length; i++ ) {
        if( sortedByCategory[data[i].metric_type] == undefined ){
            sortedByCategory[data[i].metric_type] = [];
        }
        sortedByCategory[data[i].metric_type].push(data[i]);
    }
    for (i = 0; i < sortedByCategory.maturity_buckets.length; i++){
        //if not null do the following sortedByCategory.maturity_buckets[i].basket_exposure!=null
        if ( sortedByCategory.maturity_buckets[i].basket_exposure!=null ){
        matGraph.labels.push(sortedByCategory.maturity_buckets[i].basket_metric)
        matGraph.points.push(Math.fround(sortedByCategory.maturity_buckets[i].basket_exposure * 100))
      }
    }
    new Highcharts.chart('matGraph', {
        chart: {
            type: 'bar',
            backgroundColor: '#fff'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: matGraph.labels,
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: null
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return Highcharts.numberFormat(this.y,2);
                    }
                }
            }
        },
        legend: { enabled:false },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Perecentage',
            color: '#24a5e0',
            data: matGraph.points
        }]
    });
};

async function getPortfolioBreakdownData(){
// Build Table for Portfolio Breakdown using only metric_type = issuer_category
    const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=basket-exposures&ticker=IBD';
    const response = await fetch(api);
    const data = await response.json();
    let portFilteredData = data.filter(
        obj => {
            return obj.metric_type === 'issuer_category'
        }
    );
    let portData = [];
    for ( i = 0; i < portFilteredData.length; i++) {
        tr = jQuery('<tr/>');
        tr.append("<td>" + portFilteredData[i].basket_metric + "</td>");
        tr.append("<td>" + parseFloat(portFilteredData[i].basket_exposure).toFixed(2)*100 +"%" + "</td>");//format number
        jQuery('#portTable').append(tr);
    };
};

async function getPieChartData() {
    const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=basket-exposures&ticker=IBD';
    const response = await fetch(api);
    const data = await response.json();
    let filteredData = data.filter(
        obj => {
            return obj.metric_type === 'sector_exposure_breakdown'
        }
    );
    let pieChartData = [];
    for( i = 0; i < filteredData.length; i++ ){
        let name = filteredData[i].basket_metric;
        let value = filteredData[i].basket_exposure;
        pieChartData.push({name: name, y: value})
    };
    // Make monochrome colors
    var pieColors = (function () {
      var colors = ['#00b1f4', '#67d5ff', '#a5e7ff', '#d7f5ff', '#000911', '#00376f', '#0559ad', '#4285f4', '#88bbff', '#00aebb', '#15d5cf', '#00eeca', '#9bfff0', '#4e7194']
      return colors;
    }());

    new Highcharts.chart('pieChart', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
            backgroundColor: '#fff'
        },
			legend: {
						layout: 'horizontal',
						width: '100%',
						alignColumns: false,
						floating: false,
						itemDistance: 5,
						symbolRadius: 0,
						/*padding: 1,
        		itemMarginTop: 2,
        		itemMarginBottom: 2,*/
        		itemStyle: {
            	lineHeight: '2px',
							fontWeight: '400',
            	fontFamily:'"Roboto", Sans-serif'
        		}
        },
        title: {
            text: null
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                colors: pieColors,
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    distance: -50
                },
                showInLegend: true
            }
        },
        series: [{
						enableMouseTracking: true,
            name: 'Percent',
            data: pieChartData,
						states: {
                inactive: {
                    opacity: 1
                }
						},
            events: {
                legendItemClick: function() {
                    return false;
                }
						}
        }]
    });
};

const chunk = function(array, size) {
    if (!array.length) {
      return [];
    }
    const head = array.slice(0, size);
    const tail = array.slice(size);

    return [head, ...chunk(tail, size)];
};

async function getTableData(){
    api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=holdings&ticker=IBD';
    response = await fetch(api);
    data = await response.json();
    stocks_list = data;
    stocks_list_top_10 = stocks_list.sort((a, b) => b.shares_held-a.shares_held).slice(0,10);

		etftickerplaceholder="XHYT"

    let table = '<table border="1">';
    table += `
        <tr>
            <th>Symbol</th>
            <th>Name</th>
            <th>Security Identifier</th>
            <th>Market Value</th>
            <th>% of Net Assets</th>
        </tr>`;
    stocks_list_top_10.forEach((stock) => {
        const unFormatedNumber = stock.market_value.toFixed(2);
        const formatedNumber = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(unFormatedNumber)
        table = table + `<tr>`;
        table = table + `<td>` + etftickerplaceholder;
        table = table + `<td> ${stock.security_name}`;
        table = table + `<td>` + etftickerplaceholder;
        table = table + `<td> ${formatedNumber}`;
        table = table + `<td> ${(stock.weight * 100).toFixed(3)} %`;
        table += `</tr>`;
    });
    table += "</table>";
    document.getElementById("top10Table").innerHTML = table;
    let table1 = '<table border="1">';
    table1 += `
        <tr>
            <th>Symbol</th>
            <th>Name</th>
            <th>Security Identifier</th>
            <th>Market Value</th>
            <th>% of Net Assets</th>

        </tr>`;
        chunk(stocks_list, 20).forEach((stock_list) => {
            stock_list.forEach((stock) => {
                const unFormatedNumber = stock.market_value.toFixed(2);
                const formatedNumber = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(unFormatedNumber)
                table1 = table1 + `<tr>`;
                table1 = table1 + `<td>` + etftickerplaceholder;
                table1 = table1 + `<td> ${stock.security_name}`;
                table1 = table1 + `<td>` + etftickerplaceholder;
                table1 = table1 + `<td> ${formatedNumber}`;
                table1 = table1 + `<td> ${(stock.weight * 100).toFixed(3)} %`;
                table1 += `</tr>`;
            })
        });
        table1 += "</table>";
    document.getElementById("table").innerHTML = table1;
}

async function getInvestmentGrowth(){
    const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=total-return&format=json&ticker=IBD';
    const response = await fetch(api);
    const data = await response.json();
    //let startDate = new Date(Date.now() - 12096e5)
    let startDate = new Date(Date.now() - 14);
    let itemAmtArray = [];
    let dates = [];
    data.map((item) => {
        growth = parseInt(item.growth_of_10k_inception);
        itemAmtArray.push(growth)
        dates.push(item.date)
    })
    new Highcharts.chart('lineChart', {
      chart: {
        backgroundColor: '#fff'
      },
      title: {
            text: null
        },
			yAxis: {
            title: {
                text: 'Funds'
            }
        },
        xAxis: {
            accessibility: {
                rangeDescription: null
            },
            title: {
                text: 'Days Since Inception'
            }
        },
        tooltip: {
            enabled: false
        },
        legend: { enabled:true },
        plotOptions: {
          series: {
              states: {
                  hover: {
                      enabled: false
                    }
                }
            }
      },
        series: [{
            name: 'NAV Value',
            color: '#24a5e0',
            data: itemAmtArray
        }
        ],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
}

async function getPremDisDataTable(){
    var dateObj = new Date();
    let lastYear = {
        day: dateObj.getUTCDate(),
        month: dateObj.getUTCMonth() + 1,
        year: dateObj.getUTCFullYear() - 1
    };
    const api = `https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=premium-discount&ticker=IBD&date_gteq=${lastYear.year}.${lastYear.month}.${lastYear.day}`;
    const response = await fetch(api);
    const data = await response.json();
    var dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
    let linePointsCombined = [];
    data.map((item) => {
        date = item.asof_date;
        growth = item.prem_discount_ratio;
        convertToDate = new Date(date)
        linePointsCombined.push([Date.parse(convertToDate), (growth * 100)])
    })
    new Highcharts.stockChart('premium-discount', {
        rangeSelector: {
            selected: 1,
						zoomType: ""
        },

        title: {
            text: null
        },
        tooltip: {
            borderColor: '#00a8f2',
            backgroundColor: '#FFFFFF',
            borderWidth: 1,
            useHTML: true,
            outside: true,
            formatter: function () {
                let tooltip = '<div style="min-width:300px;max-height:68px;min-height:68px;">';
                tooltip += '<span style="color:#7f7f7f;font-weight:bold;">' + `${new Date(this.x).toLocaleDateString("en-US", dateOptions)}` + '</span><br>' + '<span style="color:#00a8f2;font-weight:bold;">' + 'Premium/Discount:' + '</span><br>' + '<span style="background-color:#00a8f2;font-weight:bold;color:#fff;margin-top:10px;margin-left:85px;padding:2px 50px 2px 50px;position:relative;top:0px;">' + `${Highcharts.numberFormat(this.y,2)}%` + '</span>' +'</div>';
                   return tooltip;
                //return '<span style="color:#7f7f7f;font-weight:bold;">' + `${new Date(this.x).toLocaleDateString("en-US", dateOptions)}` + '</span><br>' + '<span style="color:#00a8f2;font-weight:bold;">' + 'Premium/Discount:' + '</span><br>' + '<span style="background-color:#00a8f2;font-weight:bold;color:red;">' + `${Highcharts.numberFormat(this.y,2)} %` + '</span>';
            }
        },
        series: [{
            name: 'IBD',
            data: linePointsCombined,
						color: '#24a5e0',
            tooltip: {
                formatter: function () {
                    return `<b>${new Date(this.y)}</b>`;
                },
                valueDecimals: 8
            }
        }]
    });
}

// Preformance Returns table

async function getPreformanceReturnsTable(){
  const api = `https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=performance&format=json&ticker=IBD`;
  response = await fetch(api);
  data = await response.json();

  const sortPref = data.filter(obj => obj.range === 'inceptiondate' && obj.asof_date_type === 'M-1');

  let asofP = sortPref[0].asof_date;
      asofP = new Date(asofP).toLocaleString('en-US');
      asofP = asofP.split(',')[0];
  jQuery(".asofP").html(asofP); // asof_date - Formatted

  jQuery(".trI").html(sortPref[0].close_performance_annualized.toFixed(2));;//close_performance_annualized
  jQuery(".mpI").html(sortPref[0].nav_performance_annualized.toFixed(2));;//nav_performance_annualized

}

document.getElementsByClassName('tablinks')[0].click()
function changeTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

async function downloadCsv(){
    const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=holdings&ticker=IBD';
    const response = await fetch(api);
    const data = await response.json();
    const replacer = (key, value) => value === null ? '' : value // specify how you want to handle null values here
    const header = Object.keys(data[0])
    const csv = [
      header.join(','), // header row first
      ...data.map(row => header.map(fieldName => JSON.stringify(row[fieldName], replacer)).join(','))
    ].join('\r\n')
    //display the created CSV data on the web browser
    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
    hiddenElement.target = '_blank';

    //provide the name for the CSV file to be downloaded
    hiddenElement.download = 'holdingsTable.csv';
    hiddenElement.click();
}
function nFormatter(num, digits) {
    const lookup = [
        { value: 1, symbol: "" },
        { value: 1e3, symbol: "k" },
        { value: 1e6, symbol: "M" },
        { value: 1e9, symbol: "B" },
        { value: 1e12, symbol: "T" }
    ];
    const rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
    var item = lookup.slice().reverse().find(function(item) {
        return num >= item.value;
    });
        return item ? (num / item.value).toFixed(digits).replace(rx, "$1") + item.symbol : "0";
}
