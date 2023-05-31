jQuery(".naic-iball").click(function(){
  	jQuery(".modal_section").fadeToggle( "slow" );
});


jQuery(".close").click(function(){
  	jQuery(".modal_section").fadeToggle( "hide" );
});

jQuery(function() {
  var appendthis = "<div class='overlay'></div>";
  jQuery(".popup").on("click", function(e) {
    e.preventDefault();
    var TickerId = jQuery(this).attr("id");
    
    jQuery("body").append(appendthis);
    jQuery(".overlay").fadeTo(500, 0.7);
    jQuery(".popup_box").fadeTo(100, 1).removeClass("boom-out").addClass("boom-in");
    //   var box_id = jQuery(this).attr('data-popup-id');
    //   jQuery('#'+box_id).fadeIn(jQuery(this).data());
  });

  //   close the popup box

  jQuery(document).on("click", ".close, .overlay", function() {
    jQuery(".popup_box").addClass("boom-out").removeClass("boom-in").fadeTo(100, 0);

    jQuery(".overlay").fadeOut(500, function() {
      jQuery(this).remove();
    });
  });

  //   keep popup in center
  jQuery(window).resize(function() {
    jQuery(".popup_box").css({
      top: (jQuery(window).height() - jQuery(".popup_box").outerHeight(true)) / 2 + 5,
      left: (jQuery(window).width() - jQuery(".popup_box").outerWidth(true)) / 2
    });
    if (jQuery(this).width() <= 480) {
      jQuery(".popup_box").css({
        top: (jQuery(window).height() - jQuery(".popup_box").outerHeight(true)) / 2 + 5,
        left: (jQuery(window).width() - jQuery(".popup_box").outerWidth(true)) / 2 + 3
      });
    }
  });
  jQuery(window).resize();
});

jQuery(function() {
  var appendthis = "<div class='overlay'></div>";
  jQuery(".popup_one").on("click", function(e) {
    e.preventDefault();
    jQuery("body").append(appendthis);
    jQuery(".overlay").fadeTo(500, 0.7);
    jQuery(".popup_box_one").fadeTo(100, 1).removeClass("boom-out").addClass("boom-in");
    //   var box_id = jQuery(this).attr('data-popup-id');
    //   jQuery('#'+box_id).fadeIn(jQuery(this).data());
  });

  //   close the popup box

  jQuery(document).on("click", ".close, .overlay", function() {
    jQuery(".popup_box_one").addClass("boom-out").removeClass("boom-in").fadeTo(100, 0);

    jQuery(".overlay").fadeOut(500, function() {
      jQuery(this).remove();
    });
  });

  //   keep popup in center
  jQuery(window).resize(function() {
    jQuery(".popup_box_one").css({
      top: (jQuery(window).height() - jQuery(".popup_box_one").outerHeight(true)) / 2 + 5,
      left: (jQuery(window).width() - jQuery(".popup_box_one").outerWidth(true)) / 2
    });
    if (jQuery(this).width() <= 480) {
      jQuery(".popup_box_one").css({
        top: (jQuery(window).height() - jQuery(".popup_box_one").outerHeight(true)) / 2 + 5,
        left: (jQuery(window).width() - jQuery(".popup_box_one").outerWidth(true)) / 2 + 3
      });
    }
  });

  jQuery(window).resize();
});

// Show the first tab and hide the rest
jQuery('.tab_title li:first-child').addClass('active');
jQuery('.tab_content .tabList').hide();
jQuery('.tab_content .tabList:first').show();


// Click function
jQuery('.tab_title li').click(function(){
  jQuery('.tab_title li').removeClass('active');
  jQuery(this).addClass('active');
  jQuery('.tab_content .tabList').hide();
  
  var activeTab = jQuery(this).find('a').attr('href');
  jQuery(activeTab).fadeIn();
  return false;
});

function BBLXbarGraph(data = []){
    let barGraph = {
            labels: [],
            points: [],
            ordered: []
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
        barGraph.points.push(Math.fround(sortedByCategory.credit_ratings[i].basket_exposure * 100))
        barGraph.ordered.push(sortedByCategory.credit_ratings[i].numeric_rank)
    }
    if(data[0].ticker == 'XEMD'){
      barGraph.labels.unshift(barGraph.labels.pop());
      barGraph.points.unshift(barGraph.points.pop());  
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
                    color: '#000000',
                    enabled: true,
                    style:{
                      textOutline: false,
                      textShadow: false,
                    },
                    formatter: function () {
                        return Highcharts.numberFormat(this.y,2)+"%";
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
                data: barGraph.points
        }]
    });
}

//**************************************//
function BBLXtotalReturnData(data){
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
      // tickInterval: 5,
      title: {
        text: 'Days Since Inception'
      }
    },
    tooltip: {
        enabled: false
    },
    credits: {
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
      },
      line: {
        marker: {
          enabled: false
        }
      }
    },
    series: [{
        name: 'NAV Value',
        color: '#24a5e0',
        data: price
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

//***************************//
function BBLXmatGraphData(data) {
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
//*****************************//
function BBLXPieChartData(data,metric_type) {
  let filteredData = data.filter(
    obj => {
      return obj.metric_type === metric_type
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
        fontFamily:'"Roboto", Sans-serif'
      }
    },
    title: {
      text: null
    },
    tooltip: {
      pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>',
      padding:12
    },
    accessibility: {
      point: {
        valueSuffix: '%'
      }
    },
    credits: {
        enabled: false
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

//***************************//

function BBLXPremDisDataTable(data){
  var dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
  let linePointsCombined = [];
  var year = '';
  data.map((item) => {
    date = item.asof_date;
    growth = item.prem_discount_ratio;
    date = date.split('-'); // asof_date - Formatting
    year = date[0];
    date = date[1]+"\/"+date[2]+"\/"+date[0];
    convertToDate = new Date(date);
    linePointsCombined.push([Date.parse(convertToDate), (growth * 100)])
  });
  /**
     * Display Current Year
     * */
    if(year != ""){
        if (jQuery(".getCurrentYear").length > 0) {
            jQuery(".getCurrentYear").text(year);
        }
    }else{
      jQuery(".getCurrentYear").text(dateObj.getUTCFullYear());
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
        formatter: function () {
          let tooltip = '<div style="min-width:300px;max-height:68px;min-height:68px;">';
          tooltip += '<span style="color:#7f7f7f;font-weight:bold;">' + `${new Date(this.x).toLocaleDateString("en-US")}` + '</span><br>' + '<span style="color:#00a8f2;font-weight:bold;">' + 'Premium/Discount:' + '</span><br>' + '<span style="background-color:#00a8f2;font-weight:bold;color:#fff;margin-top:10px;margin-left:85px;padding:2px 50px 2px 50px;position:relative;top:0px;">' + `${Highcharts.numberFormat(this.y,2)}%` + '</span>' +'</div>';
             return tooltip;
          //return '<span style="color:#7f7f7f;font-weight:bold;">' + `${new Date(this.x).toLocaleDateString("en-US", dateOptions)}` + '</span><br>' + '<span style="color:#00a8f2;font-weight:bold;">' + 'Premium/Discount:' + '</span><br>' + '<span style="background-color:#00a8f2;font-weight:bold;color:red;">' + `${Highcharts.numberFormat(this.y,2)} %` + '</span>';
        }
      },
      credits: {
          enabled: false
      },
      series: [{
        name: 'XEMD',
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

//**********************************//
function downloadHoldingCsv(data){
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

//***********************************//

function BBLXTableData(dataAll, apiTop = [], topHolding = ''){
  stocks_list_all = dataAll;
  stocks_list = apiTop;
  //hereman
  stocks_list_top_10 = stocks_list.filter((obj) => obj.metric_type === topHolding).sort( (a, b) => b.basket_exposure.toString().localeCompare(a.basket_exposure.toString()));
  var count = 0;
  let table = '<table border="1">';
  table += `
    <tr>
      <th>Name</th>
      <th>Weight</th>
    </tr>`;
    stocks_list_top_10.forEach((stock) => {
      if(count > 10){
        return false;
      }else{
        table = table + `<tr>`;
        if(stock.basket_metric != "Other"){
          table = table + `<td> ${stock.basket_metric}`;
          table = table + `<td> ${(stock.basket_exposure * 100).toFixed(2)} %`;
        }
        table += `</tr>`;
        count++;
      }
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
          const formatedNumber = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(unFormatedNumber)
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

const chunk = function(array, size) {
  if (!array.length) {
    return [];
  }
  const head = array.slice(0, size);
  const tail = array.slice(size);

  return [head, ...chunk(tail, size)];
};

//********************************************//

/*window.addEventListener('load', () => {
  document.getElementsByClassName('tablinks')[0].click();
})*/

function changeTab(evt, tabName) {
  evt.preventDefault();
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  console.log(tablinks);
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}

//********************************************//
function BBLXPremDisDataTable(data){
  var dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
  let linePointsCombined = [];
  var year = '';
  data.map((item) => {
    date = item.asof_date;
    growth = item.prem_discount_ratio;
    date = date.split('-'); // asof_date - Formatting
    year = date[0];
    date = date[1]+"\/"+date[2]+"\/"+date[0];
    convertToDate = new Date(date);
    linePointsCombined.push([Date.parse(convertToDate), (growth * 100)])
  });
  console.log(linePointsCombined);
  /**
   * Display Current Year
   * */
  if(year != ""){
      if (jQuery(".getCurrentYear").length > 0) {
          jQuery(".getCurrentYear").text(year);
      }
  }else{
    jQuery(".getCurrentYear").text(dateObj.getUTCFullYear());
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
        formatter: function () {
          let tooltip = '<div style="min-width:300px;max-height:68px;min-height:68px;">';
          tooltip += '<span style="color:#7f7f7f;font-weight:bold;">' + `${new Date(this.x).toLocaleDateString("en-US")}` + '</span><br>' + '<span style="color:#00a8f2;font-weight:bold;">' + 'Premium/Discount:' + '</span><br>' + '<span style="background-color:#00a8f2;font-weight:bold;color:#fff;margin-top:10px;margin-left:85px;padding:2px 50px 2px 50px;position:relative;top:0px;">' + `${Highcharts.numberFormat(this.y,2)}%` + '</span>' +'</div>';
             return tooltip;
          //return '<span style="color:#7f7f7f;font-weight:bold;">' + `${new Date(this.x).toLocaleDateString("en-US", dateOptions)}` + '</span><br>' + '<span style="color:#00a8f2;font-weight:bold;">' + 'Premium/Discount:' + '</span><br>' + '<span style="background-color:#00a8f2;font-weight:bold;color:red;">' + `${Highcharts.numberFormat(this.y,2)} %` + '</span>';
        }
      },
      credits: {
          enabled: false
      },
      series: [{
        name: 'XEMD',
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

//*********************************************//

function BBLXPortfolioBreakdownData(data){
// Build Table for Portfolio Breakdown using only metric_type = issuer_category
  let portFilteredData = data.filter(
      obj => {
        return obj.metric_type === 'issuer_category'
      }
  );
  let portData = [];
  for ( i = 0; i < portFilteredData.length; i++) {
    tr = jQuery('<tr/>');
    tr.append("<td>" + portFilteredData[i].basket_metric + "</td>");
    tr.append("<td class='text_bold'>" + (parseFloat(portFilteredData[i].basket_exposure) * 100).toFixed(1) + "%" + "</td>"); //format number
    jQuery('#portTable').append(tr);
  };
};


