{extends 'base.tpl'}
{block 'title'}Authentication{/block}

{block "body-container"}
	{if isset($errors)}
	    <div id="errors">
	    	{$errors}
	    </div>
	{else if isset($message)}
        <div id="success">
           <p>{$message}</p>
        </div>
    {/if}
    <div id="login-container" style="">
		<div id="login" class="i-box">
			<div class="login-title">
				<h1>KRA</h1>
			</div>
	        <form name="login-form" id="login-form" method="POST" action="." novalidate="novalidate">
				<fieldset>
					<section>
						<input class="i-text required" type="text" name="username" placeholder="Username">
					</section>
					<section>
						<input class="i-text required" type="text" name="fullname" placeholder="Full Name">
					</section>
					<section>
						<input class="i-text required" type="text" name="email" placeholder="E-Mail">
					</section>
					<section>
						<input class="i-text required" type="password" name="password" placeholder="Password">
					</section>
					<section>
						<input class="i-text required" type="password" name="passwordc" placeholder="Confirm Password">
					</section>
				</fieldset>
				<a href="{#BASE_URL#}/forgot-password/">Forgot your password?</a>
				<a href="{#BASE_URL#}/register/">Login</a>
				<input class="i-button" type="submit" value="Login">
		    </form>
		</div>
	</div>
{/block}