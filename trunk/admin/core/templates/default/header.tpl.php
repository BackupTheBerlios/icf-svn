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

		<!-- Tree -->
		<link rel="stylesheet" href="<?php echo $this->templatePath?>/styles/dhtmlXTree.css" type="text/css">
		<script language="JavaScript" src="<?php echo $this->templatePath?>/scripts/dhtmlXCommon.js" type="text/javascript"></script>
		<script language="JavaScript" src="<?php echo $this->templatePath?>/scripts/dhtmlXTree.js" type="text/javascript"></script>

		<!-- import the calendar script -->
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $this->templatePath?>/scripts/calendar/calendar-mos.css" title="green" />
		<script type="text/javascript" src="<?php echo $this->templatePath?>/scripts/calendar/calendar.js"></script>

		<!-- import the calendar language module -->
		<script type="text/javascript" src="<?php echo $this->templatePath?>/scripts/calendar/lang/calendar-es-AR.js"></script>
		
		<!-- tinyMCE -->
		<script type="text/javascript" src="<?php echo $this->templatePath?>/scripts/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>

		<script language="javascript" type="text/javascript">
			tinyMCE.init({
				theme : "advanced",
				language : "es",
				mode : "specific_textareas",	
				// insertlink_callback : "customInsertLink",
				// insertimage_callback : "customInsertImage",
				// save_callback : "customSave",
				external_image_list_url : "externalList.php",
				content_css : "/estilos/home.css",
				extended_valid_elements : "a[href|target|name|title|onclick]",
				plugins : "table,advimage",
//				theme_advanced_buttons2_add : "fontselect,fontsizeselect, forecolor, backcolor",
				//theme_advanced_buttons3_add_before : "tablecontrols,separator, fullscreen",
				//invalid_elements : "a",
				//theme_advanced_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1", // Theme specific setting CSS classes

				theme_advanced_buttons1 : "fontselect,fontsizeselect, separator, forecolor,backcolor, bullist,numlist,separator, link,unlink,anchor,separator,image,cleanup, code", 

				theme_advanced_buttons2 : "bold, italic, underline, tablecontrols, separatorhr,removeformat,visualaid,separator,sub,sup,separator,help", 
				theme_advanced_buttons3 : "",

				debug : false
			});

			// Custom insert link callback, extends the link function
			function customInsertLink(href, target) {
				var result = new Array();

				alert("customInsertLink called href: " + href + " target: " + target);

				result['href'] = "http://www.sourceforge.net";
				result['target'] = '_blank';

				return result;
			}

			// Custom insert image callback, extends the image function
			function customInsertImage(src, alt, border, hspace, vspace, width, height, align) 
			{
				var modalWindow = window.open("selectImage.php", null, "dialog=yes,modal=yes,width=400,height=400,resizable=yes");
				
				var result = new Array();
				
				result['src'] = "logo.jpg";
				result['alt'] = "test description";
				result['border'] = "2";
				result['hspace'] = "5";
				result['vspace'] = "5";
				result['width'] = width;
				result['height'] = height;
				result['align'] = "right";

				return result;
			}

			// Custom save callback, gets called when the contents is to be submitted
			function customSave(id, content) {
				alert(id + "=" + content);
			}
		</script>
		<!-- /tinyMCE -->

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