{extends "base.tpl"}

{block "body"}
	<h2>User Dashboard</h2>
	<div class="renew">
		<span>My Current Licences</span>
		<ul>
			{foreach $licences as $licence}
				<li>{$licence->licence_number}</li>
			{foreachelse}
				<li>No approved applications or active Licences</li>
			{/foreach}
		</ul>
	</div>
	<div class="applications">
		<span>My Applications</span>
		<ul>
			{foreach $applications as $application}
				<li>{$application->licence_number}</li>
			{foreachelse}
				<li>No applications yet</li>
			{/foreach}
		</ul>
	</div>
{/block}