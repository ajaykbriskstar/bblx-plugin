const apiOverview = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=overview&ticker=XTWO';
//const apiFlows = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=flows&ticker=XTWO';
const apiDist = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=distribution&ticker=XTWO';
const apiIndex = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=overview&ticker=XTWO';
jQuery(document).ready(function() {
	jQuery.getJSON(apiOverview, function(data) {
		// API FUNCTION - Overview
		let medSpread = data[0].median_spread_6c11; // Median Spread
		if (medSpread != null) {
			//console.log(medSpread)
			medSpread = (data[0].median_spread_6c11 * 100).toFixed(2);
			jQuery(".medSpread").html(medSpread + "%"); // Median Spread Data - % multiply by 100 and show 3 decimal points
		} else {
			jQuery(".medSpread").html("NA"); // Median Spread null
		}
		let preDr = data[0].prem_discount_ratio; // Premium Discount Ratio
		if (preDr != null) {
			//console.log(preDr)
			preDr = (data[0].prem_discount_ratio * 100).toFixed(2);
			jQuery(".preDr").html(preDr + "%"); // Median Spread Data - % multiply by 100 and show 3 decimal points
		} else {
			jQuery(".preDr").html("NA"); // Median Spread null
		} //  % multiply by 100 and show 2 decimal points
		let shout = data[0].shout; // Shares Outstanding
		if (shout != null) {
			let shoutFormated = shout.toLocaleString("en-US")
			jQuery(".shout").html(shoutFormated); // Shares Outstanding Data - integer, no decimals (do not abbreviate, should be 102,500  and not 102K)
		} else {
			jQuery(".shout").html("NA"); // Shares Outstanding null
		}
		let tickerData = data[0].ticker; // Ticker
		if (tickerData != null) {
			jQuery(".ticker, .ticker2").html(tickerData); // Ticker Data
		} else {
			jQuery(".ticker, .ticker2").html("NA"); // Ticker null
		}
		let fundName = data[0].fund_name; // Fund Name
		if (fundName != null) {
			jQuery(".productName").html(fundName); // Fund Name Data
		} else {
			jQuery(".productName").html("NA"); // Fund Name null
		}
		let navData = data[0].nav; // NAV
		if (navData != null) {
			navData = navData.toFixed(2); // NAV formatting
			jQuery(".navData").html(navData); // NAV data
		} else {
			jQuery(".navData").html("NA"); // NAV null
		}
		let expRatioData = data[0].net_expense_ratio; // Expense Ratio
		if (expRatioData != null) {
			expRatioData = expRatioData * 100;
			//expRatioData = expRatioData.toFixed(2);
			expRatioData = expRatioData;
			jQuery(".expRatio").html(expRatioData); // Expense Ratio Data
		} else {
			jQuery(".expRatio").html("NA"); // Expense Ratio null
		}
		let cusipData = data[0].cusip; //cusip
		if (cusipData != null) {
			jQuery(".cusip").html(cusipData); // cusip data
		} else {
			jQuery(".cusip").html("NA"); // cusip null
		}
		let assetClassData = data[0].assetclass; // Asset Class
		if (assetClassData != null) {
			jQuery(".assetClass").html(assetClassData); // Asset Class data
		} else {
			jQuery(".assetClass").html("NA"); // Asset Class null
		}
		let fid = data[0].inceptiondate; // FID
		if (fid != null) {
			fid = new Date(fid).toLocaleString('en-US'); //FID Format date
			fid = fid.split(',')[0]; //FID Format date
			jQuery(".fid").html(fid);; // FID Data
		} else {
			jQuery(".fid").html("NA"); // FID null
		}
		let peData = data[0].primary_exchange; // Primary Exchange
		if (peData != null) {
			jQuery(".exchange").html(peData); // Asset Class data
		} else {
			jQuery(".exchange").html("NA"); // Asset Class null
		}
		let adjedClose = data[0].adjclose; // Adjusted Close
		if (adjedClose != null) {
			jQuery(".closingP").html(adjedClose.toFixed(2)); // Adjusted Close data
		} else {
			jQuery(".closingP").html("--"); // Adjusted Close null
		}
		let aumData = data[0].aum; // AUM
		if (aumData != null) {
			aumData = (nFormatter(data[0].aum, 2));
			aumData = aumData.split(/(\d+)/);
			jQuery(".fA1").html(aumData[1]); // AUM Formatting
			jQuery(".fA2").html(aumData[2]); // AUM Formatting
			jQuery(".fA3").html(aumData[3]); // AUM Formatting
			jQuery(".fundAssetsPost").html(aumData[4]); // AUM Data
		} else {
			jQuery(".fA1").html("NA"); // AUM null
		}
		let asOfDate = data[0].asof_date; // As of Date
		if (asOfDate != null) {
			////// TIMEZONE FORMATING SHOWING -1 on THE DATE
			//asOfDate = new Date(asOfDate).toLocaleString('en-US'); // As of Date Formatting
			asOfDate = asOfDate.split('-'); // As of Date Formatting
			asOfDate = asOfDate[1] + "\/" + asOfDate[2] + "\/" + asOfDate[0];
			jQuery(".asOfDate").html(asOfDate); // As of Date data
		} else {
			jQuery(".asOfDate").html("NA"); // As of Date null
		}
		expenseratio = data[0].expenseratio *100;
		if(expenseratio != ""){
			jQuery(".management_fees").html(expenseratio+'%');	
		}else{
			jQuery(".management_fees").html('NA');
		}
	}); // Close - API FUNCTION - Overview
	function getDataLayout(date) {
		// date.split("-").reverse().join("-")
		return new Date(date.replace(/-/g, '\/')).toLocaleString('en-US', {
			month: 'short',
			day: 'numeric',
			year: 'numeric'
		});
	}
	// API FUNCTION - Distribution
	jQuery.getJSON(apiDist, function(data) {
		if ((data != undefined) && (data[0] != undefined)) {
			function padZero (num, lead, trail) 
			{
				var cString = num.toString();
				var cWhole, cDec, cCheck = cString.indexOf(".");
				//NO DECIMAL PLACES, A WHOLE NUMBER
				if (cCheck == -1) {
				  cWhole = cString.length;
				  cDec = 0;
				  cString += ".";
				}
				//IS A DECIMAL NUMBER
				else {
				  cWhole = cCheck;
				  cDec = cString.substr(cCheck+1).length;
				}
				//PAD WITH LEADING ZEROES
				if (cWhole < lead) {
				  for (let i=cWhole; i<lead; i++) { cString = "0" + cString; }
				}
				//PAD WITH TRAILING ZEROES
				if (cDec < trail) {
				  for (let i=cDec; i<trail; i++) { cString += "0"; }
				}
				return cString;
			}
			/* historical distributions in Desc order by Ex-Date */
			data.sort(function compare(obj1, obj2){return new Date(obj2.ex_date) - new Date(obj1.ex_date)})
			data.forEach(tableCreate);
			function tableCreate(item ,index, arr)
			{
				var table = document.getElementById("dividends-table");
				var row = table.insertRow();
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				var cell3 = row.insertCell(2);
				var cell4 = row.insertCell(3);
				
				/* existing formatting for dates/amounts */
				cell1.innerHTML = getDataLayout(arr[index].ex_date);
				cell2.innerHTML = getDataLayout(arr[index].record_date);
				cell3.innerHTML = getDataLayout(arr[index].pay_date);
				cell4.innerHTML = padZero(arr[index].dividend_income,0,5);
			}
		} else {
			var table = document.getElementById("dividends-table");
			var row = table.insertRow();
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);
			cell1.innerHTML = '-';
			cell2.innerHTML = '-';
			cell3.innerHTML = '-';
			cell4.innerHTML = '-';
		}
	});
	// API FUNCTION - Index
	jQuery.getJSON(apiIndex, function(data) {
		let divIncomeData = data[data.length - 1].index_provider; // Provider Name
		if (divIncomeData != null) {
			jQuery(".pName").html(data[data.length - 1].index_provider); // Provider Name Data
		} else {
			jQuery(".pName").html("NA"); // Provider Name null
		}
		let pIndexData = data[data.length - 1].index_fullname; // index name
		if (pIndexData != null) {
			jQuery(".pIndex").html(data[data.length - 1].index_fullname); // index name Data
		} else {
			jQuery(".pIndex").html("NA"); // index name null
		}
		let bbtcData = data[data.length - 1].related_index_ticker; // bloomb name
		if (bbtcData != null) {
			jQuery(".bbtc").html(data[data.length - 1].related_index_ticker); // bloomb name Data
		} else {
			jQuery(".bbtc").html("NA"); // bloomb name null
		}
		let imCap = data[0].related_index_market_cap_usd; // bloomb name
		if (imCap != null) {
			imCap = (nFormatter(data[0].related_index_market_cap_usd, 2)); // bloomb name Data Formatted
			jQuery(".imCap").html(imCap); //index market data
		} else {
			jQuery(".imCap").html("NA"); // bloomb name null
		}
	});
	// API FUNCTION - Portfolio Characteristics
	jQuery.getJSON(apiIndex, function(data) {
		let duration = data[0].weighted_average_effective_duration; // Duration
		if (duration != null) {
			let durationFormated = (data[0].weighted_average_effective_duration).toFixed(2);
			jQuery(".pc-duration").html(durationFormated); // 
		} else {
			jQuery(".pc-duration").html("NA"); //  null
		}

		let acr = data[0].weighted_average_credit_rating; // ACR
		if (acr != null) {
			jQuery(".pc-acr").html(acr); // 
		} else {
			jQuery(".pc-acr").html("NA"); // null
		}
		let pc1Data = data[0].basket_analytics_securities_count;
		if (pc1Data != null) {
			jQuery(".pc1").html(pc1Data); //basket_analytics_securities_count
		} else {
			jQuery(".pc1").html("NA"); //basket_analytics_securities_count null
		}
		let pc2Data = data[0].basket_analytics_issuer_count;
		if (pc2Data != null) {
			jQuery(".pc2").html(pc2Data); //basket_analytics_issuer_count
		} else {
			jQuery(".pc2").html("NA"); //basket_analytics_issuer_count null
		}
		let pc3 = data[0].weighted_average_coupon;
		if (pc3 != null) {
			pc3 = (data[0].weighted_average_coupon * 100).toFixed(2);
			jQuery(".pc3").html(pc3 + "%"); //weighted_average_coupon
		} else {
			jQuery(".pc3").html("--"); //weighted_average_coupon null
		}
		let pc4 = data[0].weighted_average_maturity;
		if (pc4 != null) {
			pc4 = (data[0].weighted_average_maturity).toFixed(2);
			jQuery(".pc4").html(pc4); //weighted_average_maturity
		} else {
			jQuery(".pc4").html("NA"); //weighted_average_maturity null
		}
		let pc5 = data[0].average_yield_to_maturity;
		if (pc5 != null) {
			pc5 = (nFormatter(data[0].average_yield_to_maturity * 100, 2));
			jQuery(".pc5").html(pc5); //average_yield_to_maturity
		} else {
			jQuery(".pc5").html("NA"); //average_yield_to_maturity null
		}
		let pc6Data = data[0].yield_to_worst;
		if (pc6Data != null) {
			pc6Data = (nFormatter(data[0].yield_to_worst * 100, 2));
			jQuery(".pc6").html(pc6Data + "%"); //average_yield_to_maturity
		} else {
			jQuery(".pc6").html("NA"); //yield_to_worst null
		}
		let pc7 = data[0].average_option_adjusted_spread;
		if (pc7 != null) {
			pc7 = (nFormatter(data[0].average_option_adjusted_spread, 0));
			jQuery(".pc7").html(pc7); //weighted_average_option_adjusted_spread
		} else {
			jQuery(".pc7").html("NA"); //weighted_average_option_adjusted_spread null
		}
		let pc8 = data[0].weighted_average_spread_duration;
		if (pc8 != null) {
			pc8 = (nFormatter(data[0].weighted_average_spread_duration, 2));
			jQuery(".pc8").html(pc8); //weighted_average_spread_duration
		} else {
			jQuery(".pc8").html("NA"); //weighted_average_spread_duration null
		}
		let pc9 = data[0].sec_yield_30d_subsidized;
		if (pc9 != null) {
			//console.log((pc9 * 100).toFixed(2) + '%');
			jQuery(".pc9").html((pc9 * 100).toFixed(2) + '%'); //sec_yield_30d_subsidized
		} else {
			jQuery(".pc9").html("NA"); //sec_yield_30d_subsidized null
		}
		let pc10 = data[0].basket_analytics_securities_count;
		if (pc10 != null) {
			jQuery(".pc10").html(pc9); //basket_analytics_securities_count
		} else {
			jQuery(".pc10").html("NA"); //basket_analytics_securities_count null
		}
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
	const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=basket-exposures&ticker=XTWO';
	const response = await fetch(api);
	const data = await response.json();
	let barGraph = {
		labels: [],
		points: [],
		ordered: []
	};
	let sortedByCategory = [];
	for (i = 0; i < data.length; i++) {
		if (sortedByCategory[data[i].metric_type] == undefined) {
			sortedByCategory[data[i].metric_type] = [];
		}
		sortedByCategory[data[i].metric_type].push(data[i]);
	}
	for (i = 0; i < sortedByCategory.credit_ratings.length; i++) {
		barGraph.labels.push(sortedByCategory.credit_ratings[i].basket_metric)
		barGraph.points.push(Math.fround(sortedByCategory.credit_ratings[i].basket_exposure * 100))
		barGraph.ordered.push(sortedByCategory.credit_ratings[i].numeric_rank)
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
					enabled: true,
					formatter: function() {
						return Highcharts.numberFormat(this.y, 2) + "%";
					}
				}
			}
		},
		legend: {
			enabled: false
		},
		credits: {
			enabled: false
		},
		series: [{
			dataSorting: {
				enabled: true,
				sortKey: 'ordered'
			},
			name: 'Perecentage',
			color: '#24a5e0',
			data: barGraph.points
		}]
	});
};
async function getMatGraphData() {
	const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=basket-exposures&ticker=XTWO';
	const response = await fetch(api);
	const data = await response.json();
	 let matGraph = {
		labels: [],
		points: []
	};
	let sortedByCategory = [];
	for (i = 0; i < data.length; i++) {
		if (sortedByCategory[data[i].metric_type] == undefined) {
			sortedByCategory[data[i].metric_type] = [];
		}
		sortedByCategory[data[i].metric_type].push(data[i]);
	}
	for (i = 0; i < sortedByCategory.maturity_buckets.length; i++) {
		//if not null do the following sortedByCategory.maturity_buckets[i].basket_exposure!=null
		if (sortedByCategory.maturity_buckets[i].basket_exposure != null) {
			matGraph.labels.push(sortedByCategory.maturity_buckets[i].basket_metric)
			matGraph.points.push(Math.fround(sortedByCategory.maturity_buckets[i].basket_exposure * 100))
		}
	}
	new Highcharts.chart('matGraph', {
		chart: {
			type: 'bar',
			backgroundColor: '#fff'
		},
    exporting: {
      enabled: false
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
          style:{
            textOutline: false,
            textShadow: false
          },
					formatter: function() {
						return Highcharts.numberFormat(this.y, 2) + "%";
					}
				}
			}
		},
		legend: {
			enabled: false
		},
		credits: {
			enabled: false
		},
		series: [{
      borderWidth: 0,
			name: 'Perecentage',
			color: '#24a5e0',
			data: matGraph.points
		}]
	});
};
async function getPortfolioBreakdownData() {
	// Build Table for Portfolio Breakdown using only metric_type = issuer_category
	const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=basket-exposures&ticker=XTWO';
	const response = await fetch(api);
	const data = await response.json();
	let portFilteredData = data.filter(obj => {
		return obj.metric_type === 'issuer_category'
	});
	let portData = [];
	for (i = 0; i < portFilteredData.length; i++) {
		tr = jQuery('<tr/>');
		tr.append("<td>" + portFilteredData[i].basket_metric + "</td>");
		tr.append("<td>" + (parseFloat(portFilteredData[i].basket_exposure) * 100).toFixed(1) + "%" + "</td>"); //format number
		jQuery('#portTable').append(tr);
	};
};
async function getPieChartData() {
	const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=basket-exposures&ticker=XTWO';
	const response = await fetch(api);
	const data = await response.json();
	let filteredData = data.filter(obj => {
		return obj.metric_type === 'sector_exposure_breakdown'
	});
	let pieChartData = [];
	for (i = 0; i < filteredData.length; i++) {
		let name = filteredData[i].basket_metric;
		let value = filteredData[i].basket_exposure;
		pieChartData.push({
			name: name,
			y: value
		})
	};
	// Make monochrome colors
	var pieColors = (function() {
		var colors = ['#00b1f4', '#67d5ff', '#a5e7ff', '#d7f5ff', '#88bbff', '#00aebb', '#15d5cf', '#00eeca', '#9bfff0', '#4e7194']
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
				fontFamily: '"Roboto", Sans-serif'
			}
		},
		title: {
			text: null
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>',
			padding: 12
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
					enabled: true,
					format: '{point.percentage:.2f}%',
					color: '#001d3a',
					distance: 5
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
async function getTableData() {
	apiAll = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=holdings&ticker=XTWO';
	response = await fetch(apiAll);
	dataAll = await response.json();
	stocks_list_all = dataAll;
	apiTop = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=basket-exposures&ticker=XTWO';
	response = await fetch(apiTop);
	apiTop = await response.json();
	stocks_list = apiTop;
	//hereman
	let asofP = dataAll[0].as_of_date;
	var date = asofP.substring(0, 10);
	date = date.split('-');
	var fulldate = date[1] + "\/" + date[2] + "\/" + date[0];  
	jQuery(".asOfDateHolding").html(fulldate);
	stocks_list_top_10 = stocks_list.filter((obj) => obj.metric_type === 'top_issuers').sort((a, b) => a.numeric_rank.toString().localeCompare(b.numeric_rank.toString()));
	let table = '<table border="1">';
	table += `
		<tr>
			<th>Name</th>
			<th>Weight</th>
		</tr>`;
	stocks_list_top_10.forEach((stock) => {
		table = table + `<tr>`;
		table = table + `<td> ${stock.basket_metric}`;
		table = table + `<td> ${(stock.basket_exposure * 100).toFixed(2)} %`;
		table += `</tr>`;
	});
	table += "</table>";
	document.getElementById("top10Table").innerHTML = table;
	let table1 = '<table border="1">';
	table1 += `
		<tr>
			<th>Name</th>
			<th>CUSIP</th>
			<th>Market Value</th>
			<th>% of Net Assets</th>
		</tr>`;
	chunk(stocks_list_all, 20).forEach((stocks_list_all) => {
		stocks_list_all.forEach((stock_all) => {
			const unFormatedNumber = stock_all.market_value.toFixed(2);
			const formatedNumber = new Intl.NumberFormat('en-US', {
				style: 'currency',
				currency: 'USD'
			}).format(unFormatedNumber)
			table1 = table1 + `<tr>`;
			table1 = table1 + `<td> ${stock_all.security_name}`;
			table1 = table1 + `<td> ${stock_all.cusip}`;
			table1 = table1 + `<td> ${formatedNumber}`;
			table1 = table1 + `<td> ${(stock_all.weight * 100).toFixed(3)} %`;
			table1 += `</tr>`;
		})
	});
	table1 += "</table>";
	document.getElementById("table").innerHTML = table1;
}
async function getInvestmentGrowth() {
	const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=total-return&format=json&ticker=XTWO';
	const response = await fetch(api);
	const data = await response.json();
	//let startDate = new Date(Date.now() - 12096e5)
	let startDate = new Date(Date.now() - 14);
	let itemAmtArray = [];
	let userLang = navigator.languages === undefined ? [navigator.language] : navigator.languages[0];
	let separator = ',';
	if (userLang == 'en-SE') {
		separator = ' ';
	} else if (userLang == 'en-DE' || userLang == 'de-DE') {
		separator = '.';
	}

	function dateRange(startDate, endDate, steps = 1) {
		const dateArray = [];
		let currentDate = new Date(startDate);
		while (currentDate <= new Date(endDate)) {
			dateArray.push(new Date(currentDate));
			// Use UTC date to prevent problems with time zones and DST
			currentDate.setUTCDate(currentDate.getUTCDate() + steps);
		}
		return dateArray;
	}
	const last = new Date(data.slice(-1)[0].date);
	const first = new Date(data[0].date);
	const dates = dateRange(first, last);
	let apidays = [],
		price = [],
		daynumber = [];
	let datecounter = 0,
		pricecounter = 0;
	data.forEach(dateCopy);
	data.forEach(priceCopy)

	function dateCopy(item) {
		let currentDate = new Date(item.date);
		apidays[datecounter] = currentDate;
		datecounter++;
	}

	function priceCopy(item) {
		price[pricecounter] = item.growth_of_10k_inception;
		pricecounter++;
	}
	for (let i = 0; i < dates.length; i++) {
		if (dates[i].getTime() == apidays[i].getTime()) {
			daynumber[i] = i;
		} else {
			daynumber[i] = i;
			apidays.splice(i, 0, dates[i]);
			price.splice(i, 0, price[i - 1])
		}
	}
	data.map((item) => {
		growth = parseInt(item.growth_of_10k_inception);
		itemAmtArray.push(growth)
		dates.push(item.date)
	});
	Highcharts.setOptions({
		lang: {
			thousandsSep: separator
		}
	});
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
			},
			labels: {
				format: '${value:,1f}'
			}
		},
		xAxis: {
			categories: daynumber,
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
		legend: {
			enabled: true
		},
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
			data: price
		}],
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
async function getPremDisDataTable() {
	var dateObj = new Date();
	let lastYear = {
		day: dateObj.getUTCDate(),
		month: dateObj.getUTCMonth() + 1,
		year: dateObj.getUTCFullYear() - 1
	};
	/**
	 * Display Current Year
	 * */
	const api = `https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=premium-discount&ticker=XTWO&date_gteq=${lastYear.year}.${lastYear.month}.${lastYear.day}`;
	const response = await fetch(api);
	const data = await response.json();
	var dateOptions = {
		year: 'numeric',
		month: 'long',
		day: 'numeric'
	};
	let linePointsCombined = [];
	var year = '';
	data.map((item) => {
		date = item.asof_date;
		growth = item.prem_discount_ratio;
		date = date.split('-'); // asof_date - Formatting
		year = date[0];
		date = date[1] + "\/" + date[2] + "\/" + date[0];
		convertToDate = new Date(date);
		linePointsCombined.push([Date.parse(convertToDate), (growth * 100)]);
	})
	if (year != "") {
		if (jQuery(".getCurrentYear").length > 0) {
			jQuery(".getCurrentYear").text(year);
		}
	}
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
			formatter: function() {
				let tooltip = '<div style="min-width:300px;max-height:68px;min-height:68px;">';
				tooltip += '<span style="color:#7f7f7f;font-weight:bold;">' + `${new Date(this.x).toLocaleDateString("en-US")}` + '</span><br>' + '<span style="color:#00a8f2;font-weight:bold;">' + 'Premium/Discount:' + '</span><br>' + '<span style="background-color:#00a8f2;font-weight:bold;color:#fff;margin-top:10px;margin-left:85px;padding:2px 50px 2px 50px;position:relative;top:0px;">' + `${Highcharts.numberFormat(this.y,2)}%` + '</span>' + '</div>';
				return tooltip;
				//return '<span style="color:#7f7f7f;font-weight:bold;">' + `${new Date(this.x).toLocaleDateString("en-US", dateOptions)}` + '</span><br>' + '<span style="color:#00a8f2;font-weight:bold;">' + 'Premium/Discount:' + '</span><br>' + '<span style="background-color:#00a8f2;font-weight:bold;color:red;">' + `${Highcharts.numberFormat(this.y,2)} %` + '</span>';
			}
		},
		series: [{
			name: 'XTWO',
			data: linePointsCombined,
			color: '#24a5e0',
			tooltip: {
				formatter: function() {
					return `<b>${new Date(this.y)}</b>`;
				},
				valueDecimals: 8
			}
		}]
	});
}
// Preformance Returns table
async function getPreformanceReturnsTable() {
	const api = `https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&&function=performance&format=json&ticker=XTWO`;
	//const api = `https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&&function=performance&format=json&ticker=XTWO`;
	const response = await fetch(api);
	const data = await response.json();
	const sortPref = data.filter(obj => obj.range === 'inception' && obj.asof_date_type === 'T-1');
	let asofP = sortPref[0].asof_date;
	//asofP = new Date(asofP).toLocaleString('en-US');
	//asofP = asofP.split(',')[0];
	function getDataLayout(date) {
		// date.split("-").reverse().join("-")
		return new Date(date.replace(/-/g, '\/')).toLocaleString('en-US', {
			month: 'short',
			day: 'numeric',
			year: 'numeric'
		});
	}
	if (asofP != null) {
		////// TIMEZONE FORMATING SHOWING -1 on THE DATE
		//asOfDate = new Date(asOfDate).toLocaleString('en-US'); // As of Date Formatting
		asofP = asofP.split('-'); // asof_date - Formatting
		asofP = asofP[1] + "\/" + asofP[2] + "\/" + asofP[0];
		//console.log(getDataLayout(asofP));
		jQuery(".asofP").html(getDataLayout(asofP)); // asof_date - Formatted
	} else {
		jQuery(".asofP").html("02/17/2022"); // asof_date - null
	}
	// jQuery(".asofP").html(asofP); // asof_date - Formatted
	// num formatting
	function truncateToDecimals(num, dec = 2) {
		const calcDec = Math.pow(10, dec);
		return Math.trunc(num * calcDec) / calcDec;
	}
	//nav price items
	let navAll = data.filter(obj => obj.range === 'inception' && obj.asof_date_type === 'T-1'); //wtd stats
	let navAllf = navAll[0].nav_performance_annualized * 100;
	let navAllg = truncateToDecimals(navAllf);
	if (navAllf != null) {
		jQuery(".navAll").html(navAllg + "%"); //wtd nav preformance
	} else {
		jQuery(".navAll").html("--"); //wtd nav preformance - null
	}
	let nav3yr = data.filter(obj => obj.range === 'y3' && obj.asof_date_type === 'T-1'); //wtd stats
	let nav3yrf = nav3yr[0].nav_performance_annualized * 100;
	let nav3yrg = truncateToDecimals(nav3yrf);
	if (nav3yrf != 0) {
		jQuery(".nav3yr").html(nav3yrg + "%"); //wtd nav preformance
	} else {
		jQuery(".nav3yr").html("--"); //wtd nav preformance - null
	}
	let nav2yr = data.filter(obj => obj.range === 'y2' && obj.asof_date_type === 'T-1'); //wtd stats
	let nav2yrf = nav2yr[0].nav_performance_annualized * 100;
	let nav2yrg = truncateToDecimals(nav2yrf);
	if (nav2yrf != 0) {
		jQuery(".nav2yr").html(nav2yrg + "%"); //wtd nav preformance
	} else {
		jQuery(".nav2yr").html("--"); //wtd nav preformance - null
	}
	let nav1yr = data.filter(obj => obj.range === 'y1' && obj.asof_date_type === 'T-1'); //wtd stats
	let nav1yrf = nav2yr[0].nav_performance_annualized * 100;
	let nav1yrg = truncateToDecimals(nav1yrf);
	if (nav1yrf != 0) {
		jQuery(".nav1yr").html(nav1yrg + "%"); //wtd nav preformance
	} else {
		jQuery(".nav1yr").html("--"); //wtd nav preformance - null
	}
	let navYtd = data.filter(obj => obj.range === 'ytd' && obj.asof_date_type === 'T-1'); //wtd stats
	let navYtdf = navYtd[0].nav_performance_annualized * 100;
	let navYtdg = truncateToDecimals(navYtdf);
	if (navYtdf != 0) {
		jQuery(".navYtd").html(navYtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".navYtd").html("--"); //wtd nav preformance - null
	}
	let navQtd = data.filter(obj => obj.range === 'qtd' && obj.asof_date_type === 'T-1'); //wtd stats
	let navQtdf = navQtd[0].nav_performance_annualized * 100;
	let navQtdg = truncateToDecimals(navQtdf);
	if (navQtdf != 0) {
		jQuery(".navQtd").html(navQtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".navQtd").html("--"); //wtd nav preformance - null
	}
	let navWtd = data.filter(obj => obj.range === 'wtd' && obj.asof_date_type === 'T-1'); //wtd stats
	let navWtdf = navWtd[0].nav_performance_annualized * 100;
	let navWtdg = truncateToDecimals(navWtdf);
	if (navWtdf != 0) {
		jQuery(".navWtd").html(navWtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".navWtd").html("--"); //wtd nav preformance - null
	}
	let navMtd = data.filter(obj => obj.range === 'mtd' && obj.asof_date_type === 'T-1'); //wtd stats
	let navMtdf = navMtd[0].nav_performance_annualized * 100;
	let navMtdg = truncateToDecimals(navMtdf);
	if (navMtdf != 0) {
		jQuery(".navMtd").html(navWtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".navMtd").html("--"); //wtd nav preformance - null
	}
	// //mp price items
	let mpAll = data.filter(obj => obj.range === 'inception' && obj.asof_date_type === 'T-1'); //wtd stats
	let mpAllf = mpAll[0].close_performance_annualized * 100;
	let mpAllg = truncateToDecimals(mpAllf);
	if (mpAllf != 0) {
		jQuery(".mpAll").html(mpAllg + "%"); //wtd nav preformance
	} else {
		jQuery(".mpAll").html("--"); //wtd nav preformance - null
	}
	let mp3yr = data.filter(obj => obj.range === 'y3' && obj.asof_date_type === 'T-1'); //wtd stats
	let mp3yrf = mp3yr[0].close_performance_annualized * 100;
	let mp3yrg = truncateToDecimals(mp3yrf);
	if (mp3yrf != 0) {
		jQuery(".mp3yr").html(mpAllg + "%"); //wtd nav preformance
	} else {
		jQuery(".mp3yr").html("--"); //wtd nav preformance - null
	}
	let mp2yr = data.filter(obj => obj.range === 'y2' && obj.asof_date_type === 'T-1'); //wtd stats
	let mp2yrf = mp2yr[0].close_performance_annualized * 100;
	let mp2yrg = truncateToDecimals(mp2yrf);
	if (mp2yrf != 0) {
		jQuery(".mp2yr").html(mpAllg + "%"); //wtd nav preformance
	} else {
		jQuery(".mp2yr").html("--"); //wtd nav preformance - null
	}
	let mp1yr = data.filter(obj => obj.range === 'y1' && obj.asof_date_type === 'T-1'); //wtd stats
	let mp1yrf = mp1yr[0].close_performance_annualized * 100;
	let mp1yrg = truncateToDecimals(mp1yrf);
	if (mp1yrf != 0) {
		jQuery(".mp2yr").html(mp1yrg + "%"); //wtd nav preformance
	} else {
		jQuery(".mp2yr").html("--"); //wtd nav preformance - null
	}
	let mpYtd = data.filter(obj => obj.range === 'ytd' && obj.asof_date_type === 'T-1'); //wtd stats
	let mpYtdf = mpYtd[0].close_performance_annualized * 100;
	let mpYtdg = truncateToDecimals(mpYtdf);
	if (mpYtdf != 0) {
		jQuery(".mpYtd").html(mpYtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".mpYtd").html("--"); //wtd nav preformance - null
	}
	let mpQtd = data.filter(obj => obj.range === 'qtd' && obj.asof_date_type === 'T-1'); //wtd stats
	let mpQtdf = mpQtd[0].close_performance_annualized * 100;
	let mpQtdg = truncateToDecimals(mpQtdf);
	if (mpQtdf != 0) {
		jQuery(".mpQtd").html(mpQtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".mpQtd").html("--"); //wtd nav preformance - null
	}
	let mpWtd = data.filter(obj => obj.range === 'wtd' && obj.asof_date_type === 'T-1'); //wtd stats
	let mpWtdf = mpWtd[0].close_performance_annualized * 100;
	let mpWtdg = truncateToDecimals(mpWtdf);
	if (mpWtdf != 0) {
		jQuery(".mpWtd").html(mpWtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".mpWtd").html("--"); //wtd nav preformance - null
	}
	let mpMtd = data.filter(obj => obj.range === 'mtd' && obj.asof_date_type === 'T-1'); //wtd stats
	let mpMtdf = mpMtd[0].close_performance_annualized * 100;
	let mpMtdg = truncateToDecimals(mpMtdf);
	if (mpMtdf != 0) {
		jQuery(".mpMtd").html(mpMtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".mpMtd").html("--"); //wtd nav preformance - null
	}
	//i price items
	let iAll = data.filter(obj => obj.range === 'inception' && obj.asof_date_type === 'T-1'); //wtd stats
	let iAllf = iAll[0].index_performance_annualized * 100;
	let iAllg = truncateToDecimals(iAllf);
	if (iAllf != 0) {
		jQuery(".iAll").html(iAllg + "%"); //wtd nav preformance
	} else {
		jQuery(".iAll").html("--"); //wtd nav preformance - null
	}
	let i3yr = data.filter(obj => obj.range === 'y3' && obj.asof_date_type === 'T-1'); //wtd stats
	let i3yrf = i3yr[0].index_performance_annualized * 100;
	let i3yrg = truncateToDecimals(i3yrf);
	if (i3yrf != 0) {
		jQuery(".i3yr").html(i3yrg + "%"); //wtd nav preformance
	} else {
		jQuery(".i3yr").html("--"); //wtd nav preformance - null
	}
	let i2yr = data.filter(obj => obj.range === 'y2' && obj.asof_date_type === 'T-1'); //wtd stats
	let i2yrf = i2yr[0].index_performance_annualized * 100;
	let i2yrg = truncateToDecimals(i2yrf);
	if (i2yrf != 0) {
		jQuery(".i2yr").html(i2yrg + "%"); //wtd nav preformance
	} else {
		jQuery(".i2yr").html("--"); //wtd nav preformance - null
	}
	let i1yr = data.filter(obj => obj.range === 'y1' && obj.asof_date_type === 'T-1'); //wtd stats
	let i1yrf = i1yr[0].index_performance_annualized * 100;
	let i1yrg = truncateToDecimals(i2yrf);
	if (i2yrf != 0) {
		jQuery(".i1yr").html(i1yrg + "%"); //wtd nav preformance
	} else {
		jQuery(".i1yr").html("--"); //wtd nav preformance - null
	}
	let iYtd = data.filter(obj => obj.range === 'ytd' && obj.asof_date_type === 'T-1'); //wtd stats
	let iYtdf = iYtd[0].index_performance_annualized * 100;
	let iYtdg = truncateToDecimals(iYtdf);
	if (iYtdf != 0) {
		jQuery(".iYtd").html(iYtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".iYtd").html("--"); //wtd nav preformance - null
	}
	let iQtd = data.filter(obj => obj.range === 'qtd' && obj.asof_date_type === 'T-1'); //wtd stats
	let iQtdf = iQtd[0].index_performance_annualized * 100;
	let iQtdg = truncateToDecimals(iQtdf);
	if (iQtdf != 0) {
		jQuery(".iQtd").html(mpQtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".iQtd").html("--"); //wtd nav preformance - null
	}
	let iWtd = data.filter(obj => obj.range === 'wtd' && obj.asof_date_type === 'T-1'); //wtd stats
	let iWtdf = mpWtd[0].index_performance_annualized * 100;
	let iWtdg = truncateToDecimals(iWtdf);
	if (iWtdf != 0) {
		jQuery(".iWtd").html(iWtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".iWtd").html("--"); //wtd nav preformance - null
	}
	let iMtd = data.filter(obj => obj.range === 'mtd' && obj.asof_date_type === 'T-1'); //wtd stats
	let iMtdf = iMtd[0].index_performance_annualized * 100;
	let iMtdg = truncateToDecimals(iMtdf);
	if (iMtdf != 0) {
		jQuery(".iMtd").html(iMtdg + "%"); //wtd nav preformance
	} else {
		jQuery(".iMtd").html("--"); //wtd nav preformance - null
	}
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
async function downloadCsv() {
	const api = 'https://data.etflogic.io/test?apikey=a2cabd13-a2f6-3412-a1af-13fa70eabc31&function=holdings&ticker=XTWO';
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
	const lookup = [{
		value: 1,
		symbol: ""
	}, {
		value: 1e3,
		symbol: "k"
	}, {
		value: 1e6,
		symbol: "M"
	}, {
		value: 1e9,
		symbol: "B"
	}, {
		value: 1e12,
		symbol: "T"
	}];
	const rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
	var item = lookup.slice().reverse().find(function(item) {
		return num >= item.value;
	});
	return item ? (num / item.value).toFixed(digits).replace(rx, "$1") + item.symbol : "0";
}