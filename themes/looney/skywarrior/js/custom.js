			$(document).ready(function() {
				$(".fancy-smile").fancybox({
					maxWidth:506,
					minHeight:428,
					scrollOutside:false,
					closeClick:false,
					openEffect:'none',
					closeEffect:'none',
					tpl: {
						closeBtn:'<a title="Закрыть окно" class="fancybox-item fancybox-close fancy-smile-close" href="javascript:;"></a>'
					},
					helpers: {
						overlay: null
					},
					padding:'0',
				});
			});