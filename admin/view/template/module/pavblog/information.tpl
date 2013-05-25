<?php  echo $header;  ?>
 <div id="content">
	  <div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	  </div>
	  <?php if ($error_warning) { ?>
	  <div class="warning"><?php echo $error_warning; ?></div>
	  <?php } ?>
		<div class="box">
		   
		   <div class="toolbar"><?php require( dirname(__FILE__).'/toolbar.tpl' ); ?></div>
		  
			<div class="heading">
			  <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
				<div class="buttons">
				  <a onclick="$('#form').submit();" class="button"><?php echo $this->language->get("button_save"); ?></a>
				</div>  
			</div>
			
			<div class="content">
					
						<div id="tab-support">
					
					<h4>Управление Pavo Blogs </h4>
					<p><i>
					С гордостью сообщаем о готовящемся релизе Pav Blogs Module, version 1.0. 
					Для Opencart 1.5.5.1. 
				
					
					</i>
					</p>
					
					<h4>Информация о модуле</h4>
					<div>
						<p class="pavo-copyright">Это бесплатный модуль  Opencart ,распространяемый под лицензией GPL/V2. Powered by <a href="http://www.pavothemes.com" title="PavoThemes - Opencart Theme Clubs">PavoThemes</a></p>
					</div>
					<h4>Supports</h4>
					<div>
						Присоединяйтесь к нам в <b>twitter </b>или  в <b>facebook </b>для того,что бы быть в курсе всех обновлений!
						<ul>
							<li><a href="http://www.pavothemes.com">Форум</a></li>
							<li><a href="http://www.pavothemes.com">Тикеты</a></li>
							<li><a href="http://www.pavothemes.com">Контакты</a></li>
							<li><a href="http://opencartforum.ru/user/18938-tom/">Русский перевод Юрий TOM</a></li>
							<li>Email: <a href="mailto:pavothemes@gmail.com">pavothemes@gmail.com</a> </li>
							<li>Skype автора: hatuhn</li>
							<li><a href="">YouTuBe</a></li>
						</ul>
					</div>
					
					<h4>Проверьте обновление</h4>
					<ul>
						<li><a href="http://www.pavthemes.com/updater/?product=pavblog&list=1" title="PavoThemes - Opencart Themes Club">Проверить</a></li>
					</ul>
				</div>
				
			</div>
		</div>	
 </div>
  
 <script type="text/javascript">
	$(".pavhtabs a").tabs();
	$(".pavmodshtabs a").tabs();
	function __submit( val ){
		$("#action_mode").val( val );
		$("#form").submit();
	}
	
 </script>
<?php echo $footer; ?>