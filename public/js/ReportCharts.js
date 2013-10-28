function pieChart(dataArray,optionsArray){
	var options = optionsArray;
	var divTag = '';
	
	var chartTitle = '';
	var chartDiameter;
	var seriesColorsVar;
    var highlightColorsVar ;
	
	var GridDrawBorder = false;
	var GridShadow = false;
	var GridBackground = '#ffffff';
	
	var legendShow = true;
	var legendLocation = 'ne';
	var legendPlacement = 'inside';
	var legendNumberRows ;
	var legendNumberColumns;
	var legendMarginTop = '';
	var legendMarginBottom = '';
	var legendMarginLeft = '';
	var legendMarginRight = '';
	var isReplot = 'no';
	var dataLabelFormatString= '%.2f';
	
	for (var opt in options){
		var key = opt;
		var value = options[opt];
		//alert("key is = "+key+" value is = "+value);
		if (key == "divTag"){
			divTag = value;
		}
		else if(key == "dataLabelFormatString"){
			dataLabelFormatString = value;
		
		}
		else if(key == "chartTitle"){
			chartTitle = value;
		
		}
		else if(key == "chartDiameter"){
			chartDiameter = value;

		}
		else if(key == "seriesColorsVar"){
			seriesColorsVar = value;
			
		}
		else if(key == "highlightColorsVar"){
			highlightColorsVar = value;
		}
		
		else if(key == "GridDrawBorder"){
			GridDrawBorder = value;
		}
		else if(key == "GridShadow"){
			GridShadow = value;
		}
		else if(key == "GridBackground"){
			GridBackground = value;
		}
		
		
		else if(key == "legendShow"){
			legendShow = value;
		}
		else if(key == "legendLocation"){
			legendLocation = value;
		}
		else if(key == "legendPlacement"){
			legendPlacement = value;
		}
		else if(key == "legendNumberRows"){
			legendNumberRows = value;
		}
		else if(key == "legendNumberColumns"){
			legendNumberColumns = value;
		}
		else if(key == "legendMarginTop"){
			legendMarginTop = value;
		}
		else if(key == "legendMarginBottom"){
			legendMarginBottom = value;
		}
		else if(key == "legendMarginLeft"){
			legendMarginLeft = value;
		}
		else if(key == "legendMarginRight"){
			legendMarginRight = value;
		}
		else if(key == "isReplot"){
			isReplot = value;
		}
		
	}
	
    var plot3 = jQuery.jqplot (divTag,[dataArray], 
          { 
    		//title: chartTitle, 
    		title: {
    			text : chartTitle,
    			fontFamily : 'Bold',
    			textAlign: 'right',  
    			textColor : 'blue',
    			// title for the plot,
    	        show: true,
    	    },

    		animate: true,
    		seriesColors: seriesColorsVar,
    		highlightColors: highlightColorsVar,
            series:[{lineWidth:4, markerOptions:{style:'diamond'}}],
	    	grid: {
	            drawBorder: GridDrawBorder, 
	            background: GridBackground,
	            shadow:GridShadow
	        },
	        

           seriesDefaults: {
        	 
              // Make this a pie chart.
              renderer: jQuery.jqplot.PieRenderer, 
              
              rendererOptions: {
            	  showDataLabels: true,  
            	  animation: {
  		            speed: 30000
  		        },
  		     
                // Put data labels on the pie slices.
                // By default, labels show the percentage of the slice.
            	diameter : chartDiameter,
            	//dataLabels: 'value',
               // dataLabelFormatString:'%g',
                         
              }
            },   
            
            legend: { 
            	show:legendShow,
            	location: legendLocation, 
        	    placement: legendPlacement, 
        	    rendererOptions: {
                    numberRows: legendNumberRows,
                    numberColumns : legendNumberColumns,
                   
                }, 
                marginTop: legendMarginTop,
                marginBottom: legendMarginBottom,
                marginLeft: legendMarginLeft,
                marginRight: legendMarginRight
             },
            
          }
    );
   if(isReplot == 'no'){
	    // will capture click event on slice of the charts
	    $('#'+divTag).bind('jqplotDataClick', function(ev, seriesIndex, pointIndex, data) {
	       alert(data);
	    });
	    
	    $('#'+divTag).bind('jqplotDataHighlight', function(ev, seriesIndex, pointIndex, data) {
	        var $this = $(this);                
	
	        $this.attr('title', data[0] + ": " + data[1]);                               
	    }); 
	    $('#'+divTag).bind('jqplotDataUnhighlight', function(ev, seriesIndex, pointIndex, data) {
	        var $this = $(this);                
	
	        $this.attr('title',""); 
	    });
    }
}
function barCharts(data,options){
	/* series varibale should be in this below format
	series: [
	    {label:'Hotel'},
	    {label:'Event Regristration'},
	    {label:'Airfare'}
    ]
	*/
	var divTag = '';
	var chartTitle = '';
	var seriesLabel;
	var xaxisLabel;
	var yaxisLabel;
	var legendShow = true;
	var legendPlacement = 'insideGrid';
	var ticksLabel;
	var yFormatString = '%.2f';
	var y_min = 0;
	var y_max = 100;
	
	for (var opt in options){
		var key = opt;
		var value = options[opt];
		//alert("key is = "+key+" value is = "+value);
		
		if (key == "divTag"){
			divTag = value;
		}
		else if(key == "chartTitle"){
			chartTitle = value;
		}
		else if (key == "seriesLabel"){
			seriesLabel = value;
		}
		else if (key == "xaxisLabel"){
			xaxisLabel = value;
		}
		else if (key == "yaxisLabel"){
			yaxisLabel = value;
		}
		else if (key == "legendShow"){
			legendShow = value;
		}
		else if (key == "legendPlacement"){
			legendPlacement = value;
		}
		else if (key == "ticksLabel"){
			ticksLabel = value;
		}
		else if (key == "yFormatString"){
			yFormatString = value;
		}
		else if (key == "y-min"){
			y_min = value;
		}
		else if (key == "y-max"){
			y_max = value;
		}
	}
	
    var plot2 = $.jqplot(divTag, data, {
    	
    	title: {
			text : chartTitle,
			fontFamily : 'Bold',
			textAlign: 'right',  
			textColor : 'blue',
			// title for the plot,
	        show: true,
	    },
    	animate: true,
    	
        // The "seriesDefaults" option is an options object that will
        // be applied to all series in the chart.
        seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            rendererOptions: {
	    		fillToZero: true,
	    		
		        // speed up the animation a little bit.
		        // This is a number of milliseconds.
		        // Default for a line series is 2500.
		        animation: {
		            speed: 3000
		        }
            }
        },
        // Custom labels for the series are specified with the "label"
        
        series:seriesLabel,
        
        // Show the legend and put it outside the grid, but inside the
        // plot container, shrinking the grid to accomodate the legend.
        // A value of "outside" would not shrink the grid and allow
        // the legend to overflow the container.
        legend: {
            show: legendShow,
            placement: legendPlacement,
            
        },
        highlighter: {
            show: true
            , showTooltip: true
            , tooltipLocation: 'ne'
            , tooltipAxes: 'yx'
            , useAxesFormatters: true
            , formatString:'<table class="jqplot-highlighter"><tr><td>%s</td></tr></table>'    },
        axesDefaults: {
	        labelRenderer: $.jqplot.CanvasAxisLabelRenderer
	      },
        axes: {
            // Use a category axis on the x axis and use our custom ticks.
            xaxis: {
            	show: true,
            	label:xaxisLabel,
                renderer: $.jqplot.CategoryAxisRenderer,
                ticks: ticksLabel,
  	          	tickRenderer: $.jqplot.CanvasAxisTickRenderer,
  	          	tickOptions: {
  	              labelPosition: 'right',
  	              angle: -15
  	          	},
  	          	showTicks: true,
                
            },
            // Pad the y axis just a little so bars can get close to, but
            // not touch, the grid boundaries.  1.2 is the default padding.
            yaxis: {
            	label: yaxisLabel,
              //  pad: 1.05,
               tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                tickOptions: {formatString: yFormatString,
                	angle: -30
                	},
              //  min: y_min,
               // max: y_max
            }
        }
	      
    });
	
    
    //will capture click event on slice of the charts
    $('#'+divTag).bind('jqplotDataClick', function(ev, seriesIndex, pointIndex, data) {
        alert(data);
    });
    
    
}
	

function lineChart(data,options){
	var chartTitle = '';
	var divTag = '';
	var xaxisLabel;
	var yaxisLabel;
	var markerOptions;
	var legendShow = true;
	var legendPlacement = 'outsideGrid';
	var seriesLabel;
	var series = "";
	
	for (var opt in options){
		var key = opt;
		var value = options[opt];
		//alert("key is = "+key+" value is = "+value);
		
		if (key == "divTag"){
			divTag = value;
		}
		else if(key == "chartTitle"){
			chartTitle = value;
		}
		else if (key == "seriesLabel"){
			seriesLabel = value;
		}
		else if (key == "xaxisLabel"){
			xaxisLabel = value;
		}
		else if (key == "yaxisLabel"){
			yaxisLabel = value;
		}
		else if (key == "markerOptions"){
			markerOptions = value;
		}
		else if (key == "legendShow"){
			legendShow = value;
		}
		else if (key == "legendPlacement"){
			legendPlacement = value;
		}
	}
	
	var plot2 = $.jqplot (divTag, data, {
	      // Give the plot a title.
	      title: chartTitle,
	      animate: true,
	      
	      // You can specify options for all axes on the plot at once with
	      // the axesDefaults object.  Here, we're using a canvas renderer
	      // to draw the axis label which allows rotated text.
	      seriesDefaults:{
	    	  lineWidth:2,
	          markerOptions: { style:markerOptions },
			  rendererOptions: {
		        // speed up the animation a little bit.
		        // This is a number of milliseconds.
		        // Default for a line series is 2500.
		        animation: {
		            speed: 3000
		        }
		      }
	      },
	      // Custom labels for the series are specified with the "label"
	      series:seriesLabel,
	      
	      axesDefaults: {
	        labelRenderer: $.jqplot.CanvasAxisLabelRenderer
	      },
	      // An axes object holds options for all axes.
	      // Allowable axes are xaxis, x2axis, yaxis, y2axis, y3axis, ...
	      // Up to 9 y axes are supported.
	      axes: {
	        // options for each axis are specified in seperate option objects.
	        xaxis: {
	          label: xaxisLabel,
	          renderer: $.jqplot.CategoryAxisRenderer,
	          labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
	          tickRenderer: $.jqplot.CanvasAxisTickRenderer,
	          tickOptions: {
	              // labelPosition: 'middle',
	              angle: -30
	          }
	          
	        },
	        yaxis: {
	          label: yaxisLabel,
	        }
	      },
	      highlighter: {
              show: true
              , showTooltip: true
              , tooltipLocation: 'ne'
              , tooltipAxes: 'xy'
              , useAxesFormatters: true
              , formatString:'<table class="jqplot-highlighter"><tr style="display:none;"><td>hi:</td><td>%s</td></tr><tr><td></td><td>%s</td></tr><tr style="display:none;"><td>close:</td><td>%s</td></tr></table>' 
          },

	      legend: {
	            show: legendShow,
	            placement: 'outsideGrid'
	            //offset: '-50'
	            
	       }
	      
	    });
	
	// will capture click event on slice of the charts
    $('#'+divTag).bind('jqplotDataClick', function(ev, seriesIndex, pointIndex, data) {
        alert(data);
    });
  
	
}

function findMinMax (data, min, max)
{
	var min = 0;
	var max = 100;
	for (var i=0; i < data.length; i++)
	{
		for (var j=0; j<data[i].length; j++)
		{
			var currentNo = parseInt(data[i][j][1]);
			if (currentNo < min)
				min = currentNo;
			if (currentNo > max)
				max = currentNo;
		}
	}
	
	var extra = (max*20)/100;
	
	var returnArr = [];
	returnArr[0] = min;
	returnArr[1] = max+extra;
	return returnArr;
}
