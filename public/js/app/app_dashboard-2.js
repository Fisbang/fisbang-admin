$(function	()	{

	//Flot Chart
	//Website traffic chart				
	var init = { data: [[0, 50], [1, 80], [2, 50], [3, 80], [4, 70], [5,90], [6, 80], [7, 80], [8, 10], [9, 12], [10, 10]],
			 label: "Visitor"
		},
		options = {	
			series: {
				lines: {
					show: true, 
					fill: true,
					fillColor: 'rgba(121,206,167,0.2)'
				},
				points: {
					show: true,
					radius: '4.5'
				}
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			colors: ["#37b494"]
		},
		plot;
			
	plot = $.plot($('#placeholder'), [init], options);
			
	$("<div id='tooltip'></div>").css({
		position: "absolute",
		display: "none",
		border: "1px solid #222",
		padding: "4px",
		color: "#fff",
		"border-radius": "4px",
		"background-color": "rgb(0,0,0)",
		opacity: 0.90
	}).appendTo("body");

	$("#placeholder").bind("plothover", function (event, pos, item) {

		var str = "(" + pos.x.toFixed(2) + ", " + pos.y.toFixed(2) + ")";
		$("#hoverdata").text(str);
	
		if (item) {
			var x = item.datapoint[0],
				y = item.datapoint[1];
			
				$("#tooltip").html("Visitor : " + y)
				.css({top: item.pageY+5, left: item.pageX+5})
				.fadeIn(200);
		} else {
			$("#tooltip").hide();
		}
	});

	$("#placeholder").bind("plotclick", function (event, pos, item) {
		if (item) {
			$("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
			plot.highlight(item.series, item.datapoint);
		}
	});
			
	//var animate = function () {
	//   $('#placeholder').animate( {tabIndex: 0}, {
	//	   duration: 3000,
	//	   step: function ( now, fx ) {

	//			 var r = $.map( init.data, function ( o ) {
	//				  return [[ o[0], o[1] * fx.pos ]];
	//			});

	//			 plot.setData( [{ data: r }] );
	//		 plot.draw();
	//		}	
	//	});
	//}
		
	//animate();


	//Flot Chart
	//Website traffic chart				
	var init2 = { data: [[0, 5], [1, 8], [2, 5], [3, 8], [4, 7], [5,9], [6, 8], [7, 8], [8, 10], [9, 12], [10, 10]],
			 label: "Visitor"
		},
		options = {	
			series: {
				lines: {
					show: true, 
					fill: true,
					fillColor: 'rgba(121,206,167,0.2)'
				},
				points: {
					show: true,
					radius: '4.5'
				}
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			colors: ["#37b494"]
		},
		plot;
			
	plot = $.plot($('#placeholder2'), [init2], options);
			
	$("<div id='tooltip'></div>").css({
		position: "absolute",
		display: "none",
		border: "1px solid #222",
		padding: "4px",
		color: "#fff",
		"border-radius": "4px",
		"background-color": "rgb(0,0,0)",
		opacity: 0.90
	}).appendTo("body");

	$("#placeholder2").bind("plothover", function (event, pos, item) {

		var str = "(" + pos.x.toFixed(2) + ", " + pos.y.toFixed(2) + ")";
		$("#hoverdata").text(str);
	
		if (item) {
			var x = item.datapoint[0],
				y = item.datapoint[1];
			
				$("#tooltip").html("Visitor : " + y)
				.css({top: item.pageY+5, left: item.pageX+5})
				.fadeIn(200);
		} else {
			$("#tooltip").hide();
		}
	});

	$("#placeholder2").bind("plotclick", function (event, pos, item) {
		if (item) {
			$("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
			plot.highlight(item.series, item.datapoint);
		}
	});
	

	//var animate2 = function () {
	//   $('#placeholder2').animate2( {tabIndex: 0}, {
	//	   duration: 3000,
	//	   step: function ( now, fx2 ) {

	//			 var r2 = $.map( init2.data, function ( o ) {
	//				  return [[ o[0], o[1] * fx2.pos ]];
	//			});

	//			 plot.setData( [{ data: r2 }] );
	//		 plot.draw();
	//		}	
	//	});
	//}
		
	//animate2();


	//Sparkline
	$('#visits').sparkline([15,19,20,22,33,27,31,27,19,30,21,10,15,18,25,9], {
		type: 'bar', 
		barColor: '#FC8675',	
		height:'35px',
		weight:'96px'
	});
	$('#balances').sparkline([220,160,189,156,201,220,104,242,221,111,164,242,183,165], {
		type: 'bar', 
		barColor: '#65CEA7',	
		height:'35px',
		weight:'96px'
	});
	
	//Timeline color box
	$('.timeline-img').colorbox({
		rel:'group1',
		width:"90%",
		maxWidth:'800px'
	});

	//Resize graph when toggle side menu
	$('.navbar-toggle').click(function()	{
		setTimeout(function() {
			donutChart.redraw();
			lineChart.redraw();
			barChart.redraw();			
			
			$.plot($('#placeholder'), [init], options);
		},500);	
	});
	
	$('.size-toggle').click(function()	{
		//resize morris chart
		setTimeout(function() {
			donutChart.redraw();
			lineChart.redraw();
			barChart.redraw();	

			$.plot($('#placeholder'), [init], options);			
		},500);
	});

	//Refresh statistic widget
	$('.refresh-button').click(function() {
		var _overlayDiv = $(this).parent().children('.loading-overlay');
		_overlayDiv.addClass('active');
		
		setTimeout(function() {
			_overlayDiv.removeClass('active');
		}, 2000);
		
		return false;
	});
	
	$(window).resize(function(e)	{
		
		//Sparkline
		$('#visits').sparkline([15,19,20,22,33,27,31,27,19,30,21,10,15,18,25,9], {
			type: 'bar', 
			barColor: '#fa4c38',	
			height:'35px',
			weight:'96px'
		});
		$('#balances').sparkline([220,160,189,156,201,220,104,242,221,111,164,242,183,165], {
			type: 'bar', 
			barColor: '#92cf5c',	
			height:'35px',
			weight:'96px'
		});

		//resize morris chart
		setTimeout(function() {
			donutChart.redraw();
			lineChart.redraw();
			barChart.redraw();			
			
			$.plot($('#placeholder'), [init], options);
		},500);
	});
	
	$(window).load(function(e)	{
	
		//Number Animation
		var currentUser = $('#userCount').text();
		$({numberValue: 0}).animate({numberValue: currentUser}, {
			duration: 2500,
			easing: 'linear',
			step: function() { 
				$('#userCount').text(Math.ceil(this.numberValue)); 
			}
		});
				
		var currentServerload = $('#serverloadCount').text();
		$({numberValue: 0}).animate({numberValue: currentServerload}, {
			duration: 2500,
			easing: 'linear',
			step: function() { 
				$('#serverloadCount').text(Math.ceil(this.numberValue)); 
			}
		});
			
		var currentOrder = $('#orderCount').text();
		$({numberValue: 0}).animate({numberValue: currentOrder}, {
			duration: 2500,
			easing: 'linear',
			step: function() { 
				$('#orderCount').text(Math.ceil(this.numberValue)); 
			}
		});
			
		var currentVisitor = $('#visitorCount').text();
		$({numberValue: 0}).animate({numberValue: currentVisitor}, {
			duration: 2500,
			easing: 'linear',
			step: function() { 
				$('#visitorCount').text(Math.ceil(this.numberValue)); 
			}
		});
	
		setInterval(function() {
			var currentNumber = $('#userCount').text();
			var randomNumber = Math.floor(Math.random()*20) + 1;
			var newNumber = parseInt(currentNumber, 10) + parseInt(randomNumber, 10); 
		
			$({numberValue: currentNumber}).animate({numberValue: newNumber}, {
				duration: 500,
				easing: 'linear',
				step: function() { 
					$('#userCount').text(Math.ceil(this.numberValue)); 
				}
			});
		}, 3000);
			
		setInterval(function() {
			var currentNumber = $('#visitorCount').text();
			var randomNumber = Math.floor(Math.random()*50) + 1;
			var newNumber = parseInt(currentNumber, 10) + parseInt(randomNumber, 10); 
		
			$({numberValue: currentNumber}).animate({numberValue: newNumber}, {
				duration: 500,
				easing: 'linear',
				step: function() { 
					$('#visitorCount').text(Math.ceil(this.numberValue)); 
				}
			});
		}, 5000);
	});
});
