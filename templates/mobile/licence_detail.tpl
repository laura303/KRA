{extends "mobile/base.html"}

{block "menu"}
	<li>
		<a href="{#BASE_URL#}/mobile/">Home</a> | 
		<a href="{#BASE_URL#}/auth/logout/">Logout</a>
	</li>
{/block}

{block "content"}
	{if $licence->latest_payment() && !$licence->latest_payment()->is_notified}
		<div class="notice">
			Your next renewal date is on {$licence->latest_payment()->expiry_date|date_format:'%d/%m/%Y at %I:%M %p'}   
		</div>
		<br/>
	{/if}
	{block "data-view"}
		<p>
			<b>Applicant Name:</b> {$licence->applicant_name}
		</p>
		<p>
			<b>Applicant Address:</b> {$licence->address}
		</p>
		<p>
			<b>Licence Number:</b> {$licence->licence_number}
		</p>
		<p>
			<b>Certificate of Competence Number:</b> {$licence->certificate_of_competence}
		</p>
		<p>
			<b>Driving Test Date:</b> {$licence->driving_test_date}
		</p>
		<p>
			<b>Driving Test Place:</b> {$licence->driving_test_place}
		</p>
		<p>
			<b>Status:</b> <i>{$licence->status|capitalize}</i>
		</p>
	{/block}
	<p>
		{if ($licence->status == 'issued' || $licence->status == 'approved') & $licence->paying_now_is_reasonable()}
			<button	onclick="window.location='{#BASE_URL#}/mobile/licences/{$licence->id}/renew/'">
				Re-new Licence
			</button>
		{/if}
	</p>
{/block}
