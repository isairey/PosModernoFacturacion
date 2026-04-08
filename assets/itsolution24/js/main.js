var langCode = 'en';
switch(lang) {
	case "arabic":
		langCode = "ar";
		break;
	case "bangla":
		langCode = "bn";
		break;
	case "french":
		langCode = "fr";
		break;
	case "germany":
		langCode = "de";
		break;
	case "hindi":
		langCode = "hi";
		break;
	case "spanish":
		langCode = "es";
		break;
	default:
      langCode = 'en';
      break;
}
var storeApp = (function ($) {
	"use strict";
	return {
		codeEditor: function() {
			if ($("#template-content-editor").length) {
				window.editAreaLoader.init({
					id: "template-content-editor"	// id of the textarea to transform		
					,font_size: "10"	
					,allow_resize: "no"
					,allow_toggle: true
					,language: "en"
					,syntax: "html"
					,toolbar: "save, |, search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight, |, help"
					,save_callback: "save_template_content_data"
					,replace_tab_by_spaces: 4
					,min_height: 550
				});

				window.editAreaLoader.init({
					id: "template-css-editor"	// id of the textarea to transform		
					,font_size: "10"	
					,allow_resize: "no"
					,allow_toggle: true
					,language: "en"
					,syntax: "css"
					,toolbar: "save, |, search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight, |, help"
					,save_callback: "save_template_css_data"
					,replace_tab_by_spaces: 4
					,min_height: 550
				});
			}
			
		}
		,intiTinymce: function(myselector) {
			if (window.tinymce) {
				var selector = myselector ? myselector : '.description';
				window.tinymce.init({
					selector: selector,
					height : "300",
					plugins: "fullscreen, code",
					block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment | code | fullscreen',
				});
			}
		}
	 	,datePicker: function() {
      		$("input[type=\"date\"]").each(function() {
      			$(this).attr("type", "text");
      			$(this).datepicker({
      				language: langCode,
      				format: "yyyy-mm-dd",
      				autoclose:true,
      				todayHighlight: true
      			});
      		});
		}
		,timePicker: function() {
			if ($(".showtimepicker").length) {
				$(".showtimepicker").timepicker();
			}
		}
		,select2: function() {
			$("select").select2({
			  tags: false,
			  width: "100%",
			  height: "50px",
			});
		}
		,customCheckbox: function() {
			var checkboxs = $('input[type=checkbox]');
			checkboxs.each(function(){
			    $(this).wrap('<div class="customCheckbox"></div>');
			    $(this).before('<span>&#10004;</span>');
			});
			checkboxs.change(function(){
			    if($(this).is(':checked')){
			     $(this).parent().addClass('customCheckboxChecked');
			    } else {
			     $(this).parent().removeClass('customCheckboxChecked');
			    }
			});
		}
		,modalAnimation: function() {
			$(".modal").on("show.bs.modal", function (e) {
			      $(".modal .modal-dialog").attr("class", "modal-dialog  flipInX  animated"); //bounceIn, pulse, lightSpeedIn,bounceInRight
			});
			$(".modal").on("hide.bs.modal", function (e) {
			      $(".modal .modal-dialog").attr("class", "modal-dialog  flipOutX  animated");
			});
		}
		,generateCardNo: function(x) {
		    if(!x) { x = 16; }
		    var chars = "1234567890";
		    var no = "";
		    for (var i=0; i<x; i++) {
		       var rnum = Math.floor(Math.random() * chars.length);
		       no += chars.substring(rnum,rnum+1);
		   }
		   return no;
		}
		,playSound: function(name, path) {
			path = path ? path : window.baseUrl + '/assets/itsolution24/mp3/' + name;
		  	var audioElement = document.createElement('audio');
		  	audioElement.setAttribute('src', path);
	  		if(typeof audioElement.play === 'function') {
		  		audioElement.play();
		  	}
		}
		,getBase64FromImageUrl: function(url, callback) {
		    var img = new Image();
				img.crossOrigin = "anonymous";
		    img.onload = function () {
		        var canvas = document.createElement("canvas");
		        canvas.width =this.width;
		        canvas.height =this.height;
		        var ctx = canvas.getContext("2d");
		        ctx.drawImage(this, 0, 0);
		        var dataURL = canvas.toDataURL("image/png");
		        var o = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
		        callback(o);
		    };
		    img.src = url;
		}
		,bootBooxHeightAdjustment: function() {
			if (deviceType != 'phone') {
				$(document).find(".bootboox-container").css({"height":$(window).height()-115});
				$(".bootboox-container").perfectScrollbar();
			}
		}
		,printModalPage: function(selector){
		    var $print = $(selector)
		        .clone()
		        .addClass('print-modal-content')
		        .removeClass('modal-dialog')
		        .prependTo('body');
		    window.print();
		    $print.remove();
		}
		,windowWidth: function() {
			return $(window).width(); 
	
		}
		,windowHeight: function() {
			return $(window).height();
		}
      	,init: function () { 

      		// Initiate live clock
      		if ($("#live_datetime").length) {
      			window.liveDateTime('live_datetime');
      		}

      		// Initiate code editor
      		this.codeEditor();
			
			// Initiate date picker
      		this.datePicker();

      		// Initiate time picker
      		this.timePicker();

      		// inititate select2
      		this.select2();

      		// Initiate customer checkbox
      		// this.customCheckbox();

      		// Initiate beautiful bootstrap modal animation
      		this.modalAnimation();

			// Scrollbar
			$("#side-panel, .dashboard-widget, .scrolling-list, .dropdown-menu").perfectScrollbar();
			var t = setInterval(function() {
		        if ($(".scrolling-list").length) {
		            $(".scrolling-list").perfectScrollbar();
		            clearInterval(t);
		        }
		    }, 500);

			//Notification options
			window.toastr.options = {
			  "closeButton": true,
			  "debug": false,
			  "newestOnTop": false,
			  "progressBar": false,
			  "positionClass": "toast-bottom-left",
			  "preventDuplicates": true,
			  "onclick": null,
			  "showDuration": "300",
			  "hideDuration": "1000",
			  "timeOut": "5000",
			  "extendedTimeOut": "1000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			};

			// Expand collapse supplier stock products
			$(".supplier_title").on("click", function () {
				$(this).hasClass("active") ? $(this).removeClass("active") : $(this).addClass("active");
			    var panel = $(this).data("panel");
			    $("#"+panel).toggle("fast");
			});

			// Generate random number
		  	$(".random_num").click(function(){
		    	$(this).parent(".input-group").children("input").val(storeApp.generateCardNo(8));
		  	});

		  	// Generate random card no
		  	$(".random_card_no").click(function(){
		    	$(this).parent(".input-group").children("input").val(storeApp.generateCardNo(16));
		  	});
		  	if ($(".random_card_no").length > 0) {
			  	setTimeout(function() {
				    $(".random_card_no").trigger("click");
				}, 1000);
		  	}

		  	// Filter box
		  	$("#show-filter-box").on("click", function(e) {
		        e.preventDefault();
		        $("#filter-box").slideDown("fast");
		        $("body").toggleClass("overlay");
		    });

		    $("#close-filter-box").on("click", function(e) {
		        e.preventDefault();
		        $("#filter-box").slideUp('fast');
		        $("body").toggleClass("overlay");
		    });

		    // Generate gift card no.
		    $('#genNo').click(function(){
		        var no = generateCardNo();
		        $(this).parent().parent('.input-group').children('input').val(no);
		        return false;
		    });
		}
   };
}(window.jQuery));

window.jQuery(window).on("load", function () {

	window.jQuery.fn.extend({
	  	flash: function (color, time) {
	       var ele = this;
		    window.jQuery("html, body").animate({
		        scrollTop: ele.offset().top - 100
		    }, 500);
		    var originalColor = ele.css("background");
		    ele.css("background", color);
		    setTimeout(function () {
		      ele.css("background", originalColor);
		    }, time);
	   	},
	});

	$("#logout").on("click", function(e) {
		e.preventDefault();
		window.swal({
          title: "Logout!",
          text: "Do you want to logout?",
          icon: "warning",
          buttons: true,
          dangerMode: false,
        })
        .then(function(willDelete) {
            if (willDelete) {
                window.location = baseUrl+'/admin/logout.php';
            }
        });
	});

	var reqUrl;
	var status = '';
	var id = 0;
	$(document).delegate(".onoffswitch-small-checkbox", "click", function(e) 
	{
		reqUrl = $(this).data("url");
		console.log(reqUrl); 
        if($(this).prop('checked')) {
            status = 'chacked';
            id = $(this).parent().attr("id");
        } else {
            status = 'unchacked';
            id = $(this).parent().attr("id");
        }

        if((status != '' || status != null) && (id !='')) {
        	$.ajax({
				type: 'POST',
                url: reqUrl,
                data: "action_type=UPDATESTATUS&id=" + id + "&status=" + status,
                dataType: "JSON",
				beforeSend: function() {
					//$(element).button('loading');
				},
				complete: function() {
					//$(element).button('reset');
				},
				success: function(response) {
					toastr.success(response.msg);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					toastr.error(JSON.parse(xhr.responseText).errorMsg, "Error!");
				}
			});
        }
    });


	var preHref, href;
	$('[data-toggle="scrolling-sidebar"]').on("click", function(e) {
	    e.preventDefault();
	    href = $(this).attr('href');
	    if ($('body').hasClass('scrolling-sidebar-open-body')) {
	      $('.scrolling-sidebar-bg, .scrolling-sidebar').width(0);
	      $('.scrolling-sidebar').removeClass('scrolling-sidebar-open');
	      $('.scrolling-sidebar-mask').removeClass('mask');
	      $('body').removeClass('scrolling-sidebar-open-body');
	    } else {
	      var width = $(this).data('width');
	      $('.scrolling-sidebar-bg, .scrolling-sidebar').width(width);
	      $('.scrolling-sidebar').addClass('scrolling-sidebar-open');
	      $('.scrolling-sidebar-mask').addClass('mask');
	      $('body').addClass('scrolling-sidebar-open-body');
	      if (preHref != href && href != '#') {
	        $.ajax({
	            type: 'GET',
	            url: href,
	            data: '',
	            dataType: 'html',
	            success: function (res) {
	              if (res != 'error') {
	                preHref = href;
	                $('.scrolling-sidebar').html(res);
	              }
	            }
	        });
	      }
	    }
	});
	$('.scrolling-sidebar-mask').on('click', function(e) {
	    if ($('body').hasClass('scrolling-sidebar-open-body')) {
	      $('.scrolling-sidebar-bg, .scrolling-sidebar').width(0);
	      $('.scrolling-sidebar').removeClass('scrolling-sidebar-open');
	      $('.scrolling-sidebar-mask').removeClass('mask');
	      $('body').removeClass('scrolling-sidebar-open-body');
	    }
	});


	// Initiate storeApp
	storeApp.init();
});

// Toggling browser full screen
function toggleFullScreenMode () {
    if ((document.fullScreenElement && document.fullScreenElement !== null) ||
            (!document.mozFullScreen && !document.webkitIsFullScreen)) {
        if (document.documentElement.requestFullScreen) {
            document.documentElement.requestFullScreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullScreen) {
            document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
    }
}

// Callback functions
function save_template_content_data(id, content) 
{
	var template_id = $("#"+id).data("id");
	var passData = {
	'template_id': template_id,
	'content': content,
	};
	$.ajax({
		url: window.baseUrl+"/_inc/ajax.php?type=UPDATEPOSTEMPALTECONTENT",
		dataType: "JSON",
		type: "POST",
		data: passData,
		beforeSend: function() {
		  // $(element).button('loading');
		},
		complete: function() {
		  // $(element).button('reset');
		},
		success: function(res) {
		  // alert(res.msg);
		  window.toastr.success(res.msg, "Success!");
		},
		error: function(xhr, ajaxOptions, thrownError) {
		  window.toastr.error(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText,"Error!");
		}
	});
	return true;
}

// Callback functions
function save_template_css_data(id, content) 
{
	var template_id = $("#"+id).data("id");
	var passData = {
	'template_id': template_id,
	'content': content,
	};
	$.ajax({
		url: window.baseUrl+"/_inc/ajax.php?type=UPDATEPOSTEMPALTECSS",
		dataType: "JSON",
		type: "POST",
		data: passData,
		beforeSend: function() {
		  // $(element).button('loading');
		},
		complete: function() {
		  // $(element).button('reset');
		},
		success: function(res) {
		  // alert(res.msg);
		  window.toastr.success(res.msg, "Success!");
		},
		error: function(xhr, ajaxOptions, thrownError) {
		  window.toastr.error(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText,"Error!");
		}
	});
	return true;
}

function printContent(eleID, settings)
{
	var title = settings.title ? settings.title : store.name;
	var cssLink = settings.cssLink ? settings.cssLink : '';
	var headline = settings.headline ? '<h2 style="padding:5px;margin:0;background:#ddd;color:#000;font-size:2.5rem;">'+settings.headline+'</h2>' : '';
	var width = 750;
	var height = 650;
	if (settings.screenSize == "fullScreen") {
		width = storeApp.windowWidth();
		height = storeApp.windowHeight();
	}
	if (settings.screenSize == "halfScreen") {
		width = parseFloat(storeApp.windowWidth())/2;
		height = storeApp.windowHeight();
	}
	var settings = "width="+width+",height="+height+",top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes";
	var DocumentContainer = document.getElementById(eleID);
	var html = '<html><head>'+
	'<title>'+title+'</title>'+
	'<link type="text/css" href="../assets/itsolution24/cssmin/main.css" type="text/css" rel="stylesheet">'+
	cssLink+
	'<style type="text/css">@media print{a,.no-print,.modal-open.wrapper,.main-footer,.view-link,.dataTables_length,.dataTables_filter{display:none!important;}.box{border-top: none!important;}.box-header.with-border {border-bottom: none;}}.close,.btn{display:none!important}.print-btn{position:fixed;bottom:0;width:100%;height:30px;z-index:1251;background:#81ECFF;line-height:30px;text-align:center;cursor:pointer;}</style>'+
	'<script src="../assets/itsolution24/jsmin/main.js" type="text/javascript"></script>'+
	'</head><body style="background:#ffffff;">'+
	headline+
	DocumentContainer.innerHTML+
	// '<div class="print-btn no-print" onClick="window.print();"><span class="fa fa-fw fa-print"></span> Print Now</div>'+
	'</body></html>';
 
    var WindowObject = window.open("", "PrintWindow", settings);
    WindowObject.document.writeln(html);
    WindowObject.document.close();
    WindowObject.focus();
    
    myDelay = setInterval(checkReadyState, 10);
    function checkReadyState() {
        if (WindowObject.document.readyState == "complete") {
            clearInterval(myDelay);
            WindowObject.focus(); // necessary for IE >= 10

            WindowObject.print();
            WindowObject.close();
        }
    }
    return true;
}