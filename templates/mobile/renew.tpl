{extends "mobile/base.html"}

{block "menu"}
	<li>
		<a href="{#BASE_URL#}/mobile/">Home</a> | 
		<a href="{#BASE_URL#}/auth/logout/">Logout</a>
	</li>
{/block}

{block "content"}
	<h1>Renewal Payment:</h1>
	<form method="POST" action=".">
		<input type="hidden" name="payment[type]" value="payment" />
		<p>
			<label>Licence Number:</label> {$licence->licence_number}
		</p>
		<p>
			<label>Licence Owner:</label> {$licence->applicant_name}
		</p>
		<p>
			<label>M-Pesa Code:</label>
			<input name="payment[mpesa_code]" class="text"/>
		</p>
		<p>
			<label>Renewal Duration:</label>
			<select name="payment[renewal_duration]">
				<option value="0">Select:</option>
				<option value="1">1 Year</option>
				<option value="3">3 Years</option>
			</select>
		</p>
		<input type="submit" value="Submit Payment"/>
	</form>
{/block}