{*
	Below is a very simple example of how to process a lost password request
	We'll deal with a request in two stages, confirmation or deny then proccess
	
	This file handles 3 tasks.
	
	1. Construct new request.
	2. Confirm request. - Generate new password, update the db then email the user
	3. Deny request. - Close the request
*}
{extends 'base.tpl'}
{block 'title'}Authentication{/block}

{block "body-container"}
    {if isset($errors)} <div id="errors"> {$errors} </div> {/if}
    {if isset($success_message)} <div id="success"> <p>{$success_message}</p> </div> {/if}
    
    <div id="login-container" style="">
		<div id="login" class="i-box">
			<div class="login-title">
				<h1>KRA - Forgot password</h1>
			</div>
			{if isset($errors)} <div id="errors">{$errors}</div> {/if}
			<form name="login-form" id="login-form" method="POST" action="." novalidate="novalidate">
				<fieldset>
					<section>
						<input class="i-text required" type="text" name="username" placeholder="Username">
					</section>
					<section>
						<input class="i-text required" type="password" name="password" placeholder="Password">
					</section>
				</fieldset>
				<input class="i-button" type="submit" value="Login">
			</form>
		</div>
    </div>
{/block}