<? if ($this->id){ ?>
	<div class="block">
		<div class="head head4">
			<script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>
			<!-- VK Widget -->
			<div id="vk_groups_<?=$this->divid()?>"></div>
			<script type="text/javascript">
				VK.Widgets.Group("vk_groups_<?=$this->divid()?>", {mode: 0, width: "<?=CHtml::encode($this->width)?>", height: "<?=CHtml::encode($this->height)?>", color1: '<?=CHtml::encode($this->color1)?>', color2: '<?=CHtml::encode($this->color2)?>', color3: '<?=CHtml::encode($this->color3)?>'}, <?=CHtml::encode($this->id)?>);
			</script>
		</div>
	</div>
<? } ?>
