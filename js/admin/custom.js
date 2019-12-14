

// masonry
			$(document).ready(function(){ 
				$('.dashboard-container').masonry({
					"gutter": 0,
				}); 
			});


// menu
			$(document).ready(function(){

				$('.left-sidebar > ul > li > a').click(function() {
					var checkElement = $(this).next();	

					if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
						checkElement.slideUp('fast');
					}

					if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
						$('.left-sidebar ul ul:visible').slideUp('fast');
						checkElement.slideDown('fast');
					}

					if (checkElement.is('ul')) {
						return false;
					} else {
						return true;	
					}		
				});

			});


// fancybox
			$(document).ready(function() {
				$(".file-upload-console, .file-upload-news").fancybox({
					maxWidth:500,
					maxHeight:320,
					fitToView:false,
					width:'70%',
					height:'70%',
					autoSize:false,
					closeClick:false,
					openEffect:'none',
					closeEffect:'none',
					tpl: {
						closeBtn:'<a title="Закрыть окно" class="fancybox-item fancybox-close file-upload-close" href="javascript:;"></a>'
					},
					helpers: {
						overlay: {
							closeClick:false
						}
					},
					padding:'0',
				});
			});



