{extends "mobile/base.html"}

{block "menu"}{/block}

{block "content"}
    <div id="login-container" style="">
		<div id="login" class="i-box">
			<div class="login-title">
				<h1>KRA</h1>
			</div>
			{if isset($errors)} <div id="errors">{$errors}</div> {/if}
			<form name="login-form" id="login-form" method="POST" action="." novalidate="novalidate">
				<p>
					<input class="i-text required" type="text" name="username" placeholder="Username">
				</p>
				<p>
					<input class="i-text required" type="password" name="password" placeholder="Password">
				</p>
				<input class="i-button" type="submit" value="Login">
			</form>
		</div>
	</div>
{/block}
