{extends "base.tpl"}

{block "title"}500{/block}

{block "body-container"}
	<div id="error-page-container" style="">
	    <h1>500 :(</h1>
	    <hr>
	    <span>Oops! Something went wrong. Internal server error!</span>
	    <a href="/#dashboard">Back to dashboard</a>
	    {$long_message}
	</div>
{/block}
