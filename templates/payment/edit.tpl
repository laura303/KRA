{extends "common.tpl"}

{block "content"}
	<form method="POST" action=".">
		<input type="hidden" name="vehicle[type]" value="vehicle" />
		<p>
			<label for="id_number_plate">Number Plate:</label>
			<input type="text" name="vehicle[number_plate]" value="{$vehicle->number_plate}" />
		</p>
		<p>
			<label for="id_date_purchased">Date Purchased:</label>
			<input type="text" name="vehicle[date_purchased]" value="{$vehicle->date_purchased}" />
		</p>
		<p>
			<label for="id_vehicle_type">Vehicle Type:</label>
			<input type="text" name="vehicle[vehicle_type]" value="{$vehicle->vehicle_type}" />
		</p>
		<p>
			<label for="id_details">Details:</label>
			<textarea name="vehicle[details]">{$vehicle->details}</textarea>
		</p>
		<p>
			<input type="submit" value="Edit">
		</p>
	</form>
{/block}