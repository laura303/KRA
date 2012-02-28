{extends "common.tpl"}

{block "content"}
	{if isset($edit_success)}
		{$edit_success}
	{/if}
	<p>
		Id: {$vehicle->id}
	</p>
	<p>
		Number Plate: {$vehicle->number_plate}
	</p>
	<p>
		Vehicle Type: {$vehicle->vehicle_type}
	</p>
	<p>
		Date Purchased: {$vehicle->date_purchased}
	</p>
	<p>
		Details: {$vehicle->details}
	</p>
{/block}