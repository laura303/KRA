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
					<th>Vehicle type</th>
					<th>Vehicle make</th>
					<th>Body type</th>
					<th>Year</th>
					<th>Tare Weight</th>
				</tr>
			</thead>
			{foreach $licences as $licence}
				<tr>
					<td>{$licence->id}</td>
					<td>{$licence->motor_vehicle_type}</td>
					<td>{$licence->make}</td>
					<td>{$licence->body_type}</td>
					<td>{$licence->year_of_manufacture}</td>
					<td>{$licence->tare_weight} Kg</td>
					<td>
						<a href="{#BASE_URL#}/licences/{$type}/{$licence->id}/">View</a>
						{if $request->user->belongsToGroups('admin')}
							|
							<a href="{#BASE_URL#}/admin/licences/{$type}/{$licence->id}/edit/">Edit</a> |
							<a href="{#BASE_URL#}/admin/licences/{$type}/{$licence->id}/delete/">Delete</a>
						{/if}
					</td>
				</tr>
			{/foreach}
		</table>
	</div>
</div>
{/block}