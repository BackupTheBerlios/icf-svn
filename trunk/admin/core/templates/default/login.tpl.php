<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
	<HEAD>
		<TITLE><?php echo $this->text["icf"]?> - <?php echo $this->text["login"]?></TITLE>
		<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
		<STYLE type=text/css>@import url( <?php echo $this->templatePath?>/styles/admin_login.css );</STYLE>

		<SCRIPT language=javascript type=text/javascript>
			function setFocus() 
			{
				document.loginForm.username.select();
				document.loginForm.username.focus();
			}
		</SCRIPT>

		<META content="MSHTML 6.00.2800.1276" name=GENERATOR>
	</HEAD>

	<BODY onload="setFocus()">
			
		<div id="wrapper">
		    <div id="header">
				<div id="mambo"><img src="<?php echo $this->templatePath?>/images/icf.png" alt="IDEA Content Framework Logo" /></div>
		    </div>
		</div>
		<DIV id=ctr align=center>
			<DIV class=login>
				<DIV class=login-form>
					<FORM id="loginForm" name="loginForm" action="login.php" method="post">
						<DIV class=form-block>
							<DIV class=inputlabel><?php echo $this->text["user"]?></DIV>
							<DIV><INPUT class="inputbox" size="15" name="username" value="<?php echo $this->controllerData["username"]?>"></DIV>
							<DIV class=inputlabel><?php echo $this->text["pwd"]?></DIV>
							<DIV><INPUT class="inputbox" type="password" size="15" id="password" name="password" value="<?php echo $this->controllerData["password"]?>"></DIV>
							<DIV align=left><INPUT class=button type=submit value=<?php echo $this->text["login"]?> name=submit></DIV>
						</DIV>
						
						<input type="hidden" name="method" id="method" value="login" />
					</FORM>
				</DIV>
			<DIV class=login-text>
				<DIV class=ctr><IMG height=64 alt=security src="<?php echo $this->templatePath?>/images/security.png" width=64></DIV>
				<P><?php echo $this->text["welcome"]?></P>
				<P><?php echo $this->text["inputdata"]?></P>
			</DIV>

			<DIV class=clr><?php echo $this->controllerData["loginfailed"]?></DIV>
		</DIV>
		
		<DIV id=break></DIV>
		<NOSCRIPT><?php echo $this->text["jswarning"]?></NOSCRIPT>	
		
		<DIV class=footer align=center>
			<DIV align=center><?php echo $this->text["copyright"]?></DIV>
			<DIV align=center><a href="http://sf.net/icf"><?php echo $this->text["icf"]?></a> <?php echo $this->text["license"]?></DIV>
		</DIV>
	</BODY>
</HTML>
