{extends "base.tpl"}

{block "body"}
	<H2>Vehicles:</H2><a href="{#BASE_URL#}/vehicles/add/" class="button">Add</a>
	<table>
		<tr>
			<th>Id</th>
			<th>Number plate</th>
			<th>Vehicle Type</th>
			<th>Date Purchased</th>
			<th>Details</th>
			<th>Actions</th>
		</tr>
		{foreach $vehicles as $vehicle}
			<tr>
				<td>{$vehicle->id}</td>
				<td>{$vehicle->number_plate}</td>
				<td>{$vehicle->vehicle_type}</td>
				<td>{$vehicle->date_purchased}</td>
				<td>{$vehicle->details}</td>
				<td>
					<a href="{#BASE_URL#}/vehicles/{$vehicle->id}/">R</a> |
					<a href="{#BASE_URL#}/vehicles/edit/{$vehicle->id}/">U</a> |
					<a href="{#BASE_URL#}/vehicles/delete/{$vehicle->id}/">D</a>
				</td>
			</tr>
		{/foreach}
	</table>
{/block}