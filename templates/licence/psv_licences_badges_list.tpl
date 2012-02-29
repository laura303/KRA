{extends "licence/base.tpl"}

{block "content"}
<br/>
	{if !$request->user->belongsToGroups('admin')}
		<a class="button" href="{#BASE_URL#}/licences/{$type}/apply/">Apply for Licence</a>
	{/if}
<br/>
<br/>
<div class="box-element">
    <div class="box-head-light">Users<a href="" class="collapsable"></a></div>
    <div class="box-content no-padding">
			<table class="i-table fullwidth">
				<thead>
					<tr>
						<th>Id</th>
						<th>Applicant Name</th>
						<th>Applicant Address</th>
						<th>Licence Number</th>
						<th>Driving Test Date</th>
						<th>Driving Test Place</th>
					</tr>
				</thead>
			{foreach $licences as $licence}
				<tr>
					<td>{$licence->id}</td>
					<td>{$licence->applicant_name}</td>
					<td>{$licence->address}</td>
					<td>{$licence->licence_number}</td>
						<td>{$licence->driving_test_date}</td>
					<td>{$licence->driving_test_place}</td>
					<td>
						<a href="{#BASE_URL#}/licences/{$type}/{$licence->id}/">View</a>
						{if $request->user->belongsToGroups('admin')}
							|
							<a href="{#BASE_URL#}/licences/{$type}/{$licence->id}/edit/">Update</a> |
							<a href="{#BASE_URL#}/licences/{$type}/{$licence->id}/delete/">Delete</a>
						{/if}
					</td>
				</tr>
			{/foreach}
		</table>
	</div>
</div>
{/block}
