// JavaScript Document


$(document).ready(function(e) {
	get_onair_array();
	get_fds_m ();
	get_fds_p ();
	get_barix ('gyula');
	get_barix ('oroshaza');
	get_barix ('984mega');
	setInterval (function () {get_onair_array()}, 5000);
	setInterval (function () {get_fds_m ()}, 30000);
	setInterval (function () {get_fds_p ()}, 30000);
	setInterval (function () {get_barix ('gyula')}, 10000);
	setInterval (function () {get_barix ('oroshaza')}, 10000);
	setInterval (function () {get_barix ('984mega')}, 10000);

	/* NEON CLOCK */
	var neclk_size = $('.neclk_container').width();
	$('.neclk_container').css('height', neclk_size + 'px');
	var neclk_trans_x = neclk_size * 0.40;
	console.log(neclk_size);
	  for(var i = 0;i<60;i++){
		$('.neclk_container').append('<div class="neclk_seconds" />');
	  }

	  var deg = 264;
	  for(var i = 1;i<61;i++){
		deg=deg+6;
		// console.log(deg+" : "+i);
		$('.neclk_seconds:nth-child('+i+')').css({
		  '-webkit-transform' : 'rotate('+deg+'deg) translatex(' + neclk_trans_x + 'px)',
		  'opacity' : '0.3', 
		  'width' : '6px', 'height' : '2px', 'border-radius' : '10px'
		}); 
	  }
	    
	  var neclk_t = setInterval(function(){
		var d = new Date();
		var time = ('0' + d.getHours()).slice(-2) + ":" + ('0' + d.getMinutes()).slice(-2);
	
		var s = d.getSeconds();
		for(var i = 1;i<=s+1;i++){
		  if(s===0){
			$('.neclk_seconds').css({'opacity' : '0.3'}); 
		  }
		  $('.neclk_seconds:nth-child('+i+')').css({'opacity' : '1'}); 
		} 
		$('.neclk_time').text(time);
	  },1000);
  
  	/* EOF NEON CLOCK */

});

 
function toggle_html_loading () {
	$('#loading').toggle("fast");
	$('#maincontent').toggle("fast");
}

function rota_reccount (db, callback) {
	var cUrl = "ajax-queries.php?f=rota_reccount&r=" + db;
	var return_a;
	$.ajax({
		type: 'GET',
		url: cUrl,
		dataType: 'json',
		success: function (jsonData) {
			rotacount = jsonData.rotacount;
			$('#rotacount').html(rotacount);
		},
		complete: function () { 
			callback();
		}
	});
}
 
function get_db_rotid_array(db, callback) {
	var cUrl = "ajax-queries.php?f=rota_idarray&r=" + db;
	console.log(cUrl);
	$.ajax({
		beforeSend: function (){
			toggle_html_loading ();
		},
		type: 'GET',
		url: cUrl,
		dataType: 'json',
		success: function (jsonData) {
			rotarray = jsonData.idarray;
		},
		complete: function () {
			toggle_html_loading ();
			callback ();
		}
	});
}

function fullchk_soundpath(db, rotarray, rotacount) {
	var n = 0; m = rotacount - 1;
	myTimer = setInterval (function () {
		if (n == 100) clearInterval(myTimer);
		// $('#a').html(n);
		var id = rotarray [n];
		q_fullchk_soundpath(db, id, n)
		n++;
	}, 200);
}

function q_fullchk_soundpath(db, id, n) {
		var cUrl = "ajax-queries.php?f=get_sound_path&r=" + db + "&id=" + id;
		// console.log(cUrl);
		$.ajax({
			beforeSend: function (){
				// toggle_html_loading ();
			},
			type: 'GET',
			url: cUrl,
			dataType: 'json',
			success: function (jsonData) {
				path = jsonData;
			},
			complete: function () {
				// toggle_html_loading ();
				// callback ();
				$('#c').html(path);
				if (path == 0) talalat++;
				$('#a').html(n);
				$('#d').html(talalat);
			}
		});
}

function get_onair_array() {
	var cUrl = "http://megaradio.hu/megaonline/ajax-queries.php?f=onair&r=mega";
	var title;
	var name;
	var txt;
	// console.log(cUrl);
	$.ajax({
		beforeSend: function (){
		},
		type: 'GET',
		url: cUrl,
		dataType: 'json',
		success: function (jsonData) {
			name = jsonData [1];
			title = jsonData [2];
		},
		complete: function () {
			txt = name + " - " + title;
			$('#onair-mega').html(txt);
		}
	});
}

function get_fds_m () {
	var cUrl = "ajax-queries.php?f=status-m-dsp";
	var card_class = ['bg-success', 'bg-warning', 'bg-danger'];
	var status; var status_code; var fds_code; 
	$.ajax({
		beforeSend: function (){
		},
		type: 'GET',
		url: cUrl,
		dataType: 'json',
		success: function (jsonData) {
			status 			= jsonData ['status'];
			status_code 	= jsonData ['status_code'];
			fds_code		= jsonData ['fds_code']
		},
		complete: function () {
			$('#dsp_m').html(status);
			var addcardclass = card_class [fds_code];
			$('#dsp_m_card').
				removeClass('bg-secondary').
				removeClass('bg-success').
				removeClass('bg-danger').
				removeClass('bg-warning').addClass(addcardclass);
		}
	});
}

function get_barix (radio) {
	var div_card = "#barix_" + radio + "_card";
	var div_title = "#barix_" + radio;
	var cUrl = "ajax-queries.php?f=barix&r=" + radio;
	var card_class = ['bg-success', 'bg-warning', 'bg-danger'];
	var status; var status_code; var fds_code; 
	$.ajax({
		beforeSend: function (){
		},
		type: 'GET',
		url: cUrl,
		dataType: 'json',
		success: function (jsonData) {
			status 			= jsonData ['status'];
			status_code 	= jsonData ['status_code'];
			brx_code		= jsonData ['brx_code']
		},
		complete: function () {
			$(div_title).html(status);
			var addcardclass = card_class [brx_code];
			$(div_card).
				removeClass('bg-secondary').
				removeClass('bg-success').
				removeClass('bg-danger').
				removeClass('bg-warning').addClass(addcardclass);
		}
	});
}

function get_fds_p () {
	var cUrl = "ajax-queries.php?f=status-p-dsp";
	var card_class = ['bg-success', 'bg-warning', 'bg-danger'];
	var status; var status_code; var fds_code; 
	$.ajax({
		beforeSend: function (){
		},
		type: 'GET',
		url: cUrl,
		dataType: 'json',
		success: function (jsonData) {
			status 			= jsonData ['status'];
			status_code 	= jsonData ['status_code'];
			fds_code		= jsonData ['fds_code']
		},
		complete: function () {
			$('#dsp_p').html(status);
			var addcardclass = card_class [fds_code];
			$('#dsp_p_card').
				removeClass('bg-secondary').
				removeClass('bg-success').
				removeClass('bg-danger').
				removeClass('bg-warning').addClass(addcardclass);
		}
	});
}