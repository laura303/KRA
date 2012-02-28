{extends "mobile/base.html"}

{block "menu"}
	<li>
		<a href="{#BASE_URL#}/mobile/">Home</a> | 
		<a href="{#BASE_URL#}/auth/logout/">Logout</a>
	</li>
{/block}

{block "content"}
	<form action="." method="GET">
		<input name="force_pay" type="hidden" value="1" />
		A Previous payment for the Renewal of this Licence <br />
		has not expired, If you proceed with this payment,<br /> 
		the previous pay Will be Replaced. <br />
		Are you sure? <br /><br />
		<input type="submit" value="Yes"/>
		<a href="{#BASE_URL#}/">No</a>
	</form>
{/block}