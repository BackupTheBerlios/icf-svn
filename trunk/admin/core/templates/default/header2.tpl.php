<html>

	<head>
		<title><?php echo $this->text["icf"]?> - <?php echo $this->pageTitle?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		
		<!-- Various stylesheets -->
		<link rel="stylesheet" href="<?php echo $this->templatePath?>/styles/template_css.css" type="text/css">		
		<link rel="stylesheet" href="<?php echo $this->templatePath?>/styles/theme.css" type="text/css">
		
		<!-- Menu -->
		<script language="javascript">var cmThemeOfficeBase="<?php echo $this->templatePath?>/images/"</script>
		<script language="JavaScript" src="<?php echo $this->templatePath?>/scripts/menu/JSCookMenu.js" type="text/javascript"></script>
		<script language="JavaScript" src="<?php echo $this->templatePath?>/scripts/menu/theme.js" type="text/javascript"></script>
		<script language="JavaScript" src="<?php echo $this->templatePath?>/scripts/menu/mambojavascript.js" type="text/javascript"></script>


		<!-- ActiveWidgets stylesheet and scripts -->
		<script language="javascript" src="<?php echo $this->basePath?>/includes/activewidgets/lib/grid.js" type="text/javascript"></script>
		<link href="<?php echo $this->basePath?>/includes/activewidgets/styles/xp/grid.css" rel="stylesheet" type="text/css"></link>
	</head>

	<body>
		<div id="wrapper">
		    <div id="header">
				<div id="mambo"><img src="<?php echo $this->templatePath?>/images/icf.png" alt="IDEA Content Framework Logo" /></div>
		    </div>
		</div>
		<input type="hidden" name="returnToViewHidden" id="returnToViewHidden" value="<?php echo $this->returnToView?>" />