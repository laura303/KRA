{extends "licence/base.tpl"}

{block "content"}
	<h2>Licence Details:</h2>
	{if $licence->latest_payment() && !$licence->latest_payment()->is_notified}
		<div class="notice">
			Your next renewal date is on {$licence->latest_payment()->expiry_date|date_format:'%d/%m/%Y at %I:%M %p'}   
		</div>
		<a href="{#BASE_URL#}/licences/{$type_slug}/{$licence->id}/notification/close/">close</a>
	{/if}
	<p>
		Licence Owner: <b>{$licence->user->fullname}</b>
	</p>
	{block "data-view"}
	{/block}
	<p>
		Status: <b>{$licence->status|capitalize}</b>
	</p>
	<p>
	{if $request->user->belongsToGroups('admin')}
		{if !$licence->status}
			<form action="{#BASE_URL#}/admin/licences/{$type_slug}/{$licence->id}/approve/" method="POST">
				<input type="submit" value="Approve Licence"/>
			</form>
			<form action="{#BASE_URL#}/admin/licences/{$type_slug}/{$licence->id}/reject/" method="POST">
				<input type="submit" value="Reject Licence"/>
			</form>
		{/if}
		{if $licence->status == 'approved'}
			<form action="{#BASE_URL#}/admin/licences/{$type_slug}/{$licence->id}/issue/" method="POST">
				<input type="submit" value="Issue Licence"/>
			</form>
		{/if}
		{if $licence->status == 'issued'}
			<form action="{#BASE_URL#}/admin/licences/{$type_slug}/{$licence->id}/revoke/" method="POST">
				<input type="submit" value="Revoke Licence"/>
			</form>
		{/if}
		{if $licence->status == 'revoked'}
			<form action="{#BASE_URL#}/admin/licences/{$type_slug}/{$licence->id}/issue/" method="POST">
				<input type="submit" value="Re-Issue Licence"/>
			</form>
		{/if}
	{/if}
	{if ($licence->status == 'issued' || $licence->status == 'approved') & $licence->paying_now_is_reasonable()}
		<button	onclick="window.location='{#BASE_URL#}/admin/licences/{$type_slug}/{$licence->id}/renew/'">
			Re-new Licence
		</button>
	{/if}
	{if $licence->latest_payment_date()}
		<button	onclick="window.location='{#BASE_URL#}/admin/licences/{$type_slug}/{$licence->id}/e-slip/'">
			E-Slip
		</button>
	{/if}
	</p>
	<br/>
	<br/>
{/block}
