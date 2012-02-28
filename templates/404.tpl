{extends "base.tpl"}

{block "title"}404{/block}

{block "body-container"}
	<div id="error-page-container" style="">
	    <h1>404 :(</h1>
	    <hr>
	    <span>Oops! Something went wrong. Requested page not found!</span>
	    <a href="/#dashboard">Back to dashboard</a>
	    {$long_message}
	</div>
{/block}
