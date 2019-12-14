<!-- Админка -->
<? if ($type == 'tinymce_admin'){ ?>
<script type="text/javascript">
tinymce.init({
	language: "ru",
	selector: "form textarea",
	theme: "modern",
	skin: "<?php echo $skin ?>",
	plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime media nonbreaking save table directionality",
			"paste textcolor colorpicker textpattern"
	],
	menubar: false,
	toolbar1: "fullscreen | undo redo | searchreplace | code | table | link image media upload",
	toolbar2: "bold italic underline strikethrough | forecolor | fontsizeselect | fontselect | alignleft aligncenter alignright | bullist numlist",
	image_advtab: true,
	media_alt_source: false,
	relative_urls : false,
	remove_script_host : false,
	style_formats: [
    {title: "Inline", items: [
        {title: "Bold", icon: "bold", format: "bold"},
        {title: "Italic", icon: "italic", format: "italic"},
        {title: "Underline", icon: "underline", format: "underline"},
        {title: "Strikethrough", icon: "strikethrough", format: "strikethrough"},
        {title: "Superscript", icon: "superscript", format: "superscript"},
        {title: "Subscript", icon: "subscript", format: "subscript"},
        {title: "Code", icon: "code", format: "code"}
    ]},
    {title: "Blocks", items: [
        {title: "Paragraph", format: "p"},
        {title: "Blockquote", format: "blockquote"},
        {title: "Div", format: "div"},
        {title: "Pre", format: "pre"}
    ]},
    {title: "Alignment", items: [
        {title: "Left", icon: "alignleft", format: "alignleft"},
        {title: "Center", icon: "aligncenter", format: "aligncenter"},
        {title: "Right", icon: "alignright", format: "alignright"},
        {title: "Justify", icon: "alignjustify", format: "alignjustify"}
    ]}
	],
	height : 200,
	width : '100%',
	setup : function(ed)
	{
		ed.addButton('spoiler', {
			image: '/images/tiny-spoiler-<?php echo $skin ?>.png',
			title: 'Спойлер',
			onclick: function() {
				ed.insertContent('<div class="forum-spoiler"><div class="button">Спойлер</div><div class="fs-container"><p><br/></p></div></div><br/>');
			}
		});
		ed.on('init', function(e) {
			ed.$('body').on('keyup', function(){
				ed.$('.forum-spoiler').each(function(){
					if (!ed.$('div', this).is('.button') || !ed.$('div', this).is('.fs-container'))
						ed.$(this).remove();
				});
				ed.$('.forum-quote').each(function(){
					if (!ed.$('div', this).is('.fq-data') || !ed.$('div', this).is('.fq-container'))
						ed.$(this).remove();
				});
			});
    });
		ed.addButton('upload', {
			image: '/images/tiny-attach-<?php echo $skin ?>.png',
			title: 'Загрузить файл',
			onclick: function() {
				window.open('/files', 'upload', 'width=420,height=400,resizable=no,scrollbars=no,status=no');
			}
		});
		ed.addButton('smiles', {
			title: 'Смайлы',
			image: '/images/tiny-smile-<?php echo $skin ?>.png',
			onclick: function() {
				$.fancybox({
					type: 'iframe',
					href: '/site/page?view=smiles',
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
					padding:'0'
				});
			}
		});
	}
});
</script>
<? } ?>

<!-- Расширенный -->
<? if ($type=='tinymce_enlarge'){ ?>
<script type="text/javascript">
tinymce.init({
	language: "ru",
	selector: "form textarea",
	theme: "modern",
	skin: "<?php echo $skin ?>",
	plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime media nonbreaking save table directionality",
			"paste textcolor colorpicker textpattern"
	],
	menubar: false,
	toolbar1: "bold italic underline strikethrough | forecolor | fontsizeselect | fontselect | alignleft aligncenter alignright | bullist numlist | spoiler | smiles | link image media upload",
	image_advtab: true,
	media_alt_source: false,
	relative_urls : false,
	remove_script_host : false,
	style_formats: [
    {title: "Inline", items: [
        {title: "Bold", icon: "bold", format: "bold"},
        {title: "Italic", icon: "italic", format: "italic"},
        {title: "Underline", icon: "underline", format: "underline"},
        {title: "Strikethrough", icon: "strikethrough", format: "strikethrough"},
        {title: "Superscript", icon: "superscript", format: "superscript"},
        {title: "Subscript", icon: "subscript", format: "subscript"},
        {title: "Code", icon: "code", format: "code"}
    ]},
    {title: "Blocks", items: [
        {title: "Paragraph", format: "p"},
        {title: "Blockquote", format: "blockquote"},
        {title: "Div", format: "div"},
        {title: "Pre", format: "pre"}
    ]},
    {title: "Alignment", items: [
        {title: "Left", icon: "alignleft", format: "alignleft"},
        {title: "Center", icon: "aligncenter", format: "aligncenter"},
        {title: "Right", icon: "alignright", format: "alignright"},
        {title: "Justify", icon: "alignjustify", format: "alignjustify"}
    ]}
	],
	height : 200,
	width : '100%',
	setup : function(ed)
	{
		ed.addButton('spoiler', {
			image: '/images/tiny-spoiler-<?php echo $skin ?>.png',
			title: 'Спойлер',
			onclick: function() {
				ed.insertContent('<div class="forum-spoiler"><div class="button">Спойлер</div><div class="fs-container"><p><br/></p></div></div><br/>');
			}
		});
		ed.on('init', function(e) {
			ed.$('body').on('keyup', function(){
				ed.$('.forum-spoiler').each(function(){
					if (!ed.$('div', this).is('.button') || !ed.$('div', this).is('.fs-container'))
						ed.$(this).remove();
				});
				ed.$('.forum-quote').each(function(){
					if (!ed.$('div', this).is('.fq-data') || !ed.$('div', this).is('.fq-container'))
						ed.$(this).remove();
				});
			});
    });
		ed.addButton('upload', {
			image: '/images/tiny-attach-<?php echo $skin ?>.png',
			title: 'Загрузить файл',
			onclick: function() {
				window.open('/files', 'upload', 'width=420,height=400,resizable=no,scrollbars=no,status=no');
			}
		});
		ed.addButton('smiles', {
			title: 'Смайлы',
			image: '/images/tiny-smile-<?php echo $skin ?>.png',
			onclick: function() {
				$.fancybox({
					type: 'iframe',
					href: '/site/page?view=smiles',
					maxWidth:526,
					minHeight:428,
					scrollOutside:true,
					closeClick:false,
					openEffect:'none',
					closeEffect:'none',
					tpl: {
						closeBtn:'<a title="Закрыть окно" class="fancybox-item fancybox-close fancy-smile-close" href="javascript:;"></a>'
					},
					helpers: {
						overlay: null
					},
					padding:'0'
				});
			}
		});
	}
});
</script>
<? } ?>

<!-- Упрощенный -->
<? if ($type=='tinymce_simple'){ ?>
<script type="text/javascript">
tinymce.init({
	language: "ru",
	selector: "form textarea",
	theme: "modern",
	skin: "<?php echo $skin ?>",
	plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime media nonbreaking save table directionality",
			"paste textcolor colorpicker textpattern"
	],
	menubar: false,
	toolbar1: "bold italic | forecolor | fontsizeselect | smiles | link image upload",
	image_advtab: true,
	media_alt_source: false,
	relative_urls : false,
	remove_script_host : false,
	style_formats: [
    {title: "Inline", items: [
        {title: "Bold", icon: "bold", format: "bold"},
        {title: "Italic", icon: "italic", format: "italic"},
        {title: "Underline", icon: "underline", format: "underline"},
        {title: "Strikethrough", icon: "strikethrough", format: "strikethrough"},
        {title: "Superscript", icon: "superscript", format: "superscript"},
        {title: "Subscript", icon: "subscript", format: "subscript"},
        {title: "Code", icon: "code", format: "code"}
    ]},
    {title: "Blocks", items: [
        {title: "Paragraph", format: "p"},
        {title: "Blockquote", format: "blockquote"},
        {title: "Div", format: "div"},
        {title: "Pre", format: "pre"}
    ]},
    {title: "Alignment", items: [
        {title: "Left", icon: "alignleft", format: "alignleft"},
        {title: "Center", icon: "aligncenter", format: "aligncenter"},
        {title: "Right", icon: "alignright", format: "alignright"},
        {title: "Justify", icon: "alignjustify", format: "alignjustify"}
    ]}
	],
	height : 200,
	width : '100%',
	setup : function(ed)
	{
		ed.addButton('upload', {
			image: '/images/tiny-attach-<?php echo $skin ?>.png',
			title: 'Загрузить файл',
			onclick: function() {
				window.open('/files', 'upload', 'width=420,height=400,resizable=no,scrollbars=no,status=no');
			}
		});
		ed.addButton('smiles', {
			title: 'Смайлы',
			image: '/images/tiny-smile-<?php echo $skin ?>.png',
			onclick: function() {
				$.fancybox({
					type: 'iframe',
					href: '/site/page?view=smiles',
					maxWidth:526,
					minHeight:428,
					scrollOutside:true,
					closeClick:false,
					openEffect:'none',
					closeEffect:'none',
					tpl: {
						closeBtn:'<a title="Закрыть окно" class="fancybox-item fancybox-close fancy-smile-close" href="javascript:;"></a>'
					},
					helpers: {
						overlay: null
					},
					padding:'0'
				});
			}
		});
	}
});
</script>
<? } ?>