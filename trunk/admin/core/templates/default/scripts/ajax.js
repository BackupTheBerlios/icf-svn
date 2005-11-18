function getComboSelectedValue(cb)
{
	return cb.options[cb.selectedIndex].value;
}

function getComboSelectedText(cb)
{
	return cb.options[cb.selectedIndex].text;
}

function getComboSelectedOption(cb)
{
	return cb.options[cb.selectedIndex];
}

function comboClear(cb)
{
	for (m=cb.options.length-1;m>0;m--)
	{
		cb.options[m]=null;
	}
}

function ajaxExec(url, handler)
{
	icfHTTP.open('get', url);
	icfHTTP.onreadystatechange = handler; 
	icfHTTP.send(null);
}