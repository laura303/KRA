{extends "licence/detailview.tpl"}

{block "data-view"}
		<p>
			Motor Vehicle Type:
			{$licence->motor_vehicle_type}
		</p>
		<p>
			Motor Vehicle Make:
			{$licence->make}
		</p>
		<p>
			Body type:
			{$licence->body_type}
		</p>
		<p>
			Year of Manufacture: {$licence->year_of_manufacture}
		</p>
		<p>
			Tare Weight: {$licence->tare_weight}
		</p>
		<p>
			Number of Axles: {$licence->number_of_axles}
		</p>
		<p>
			Value: {$licence->value}
		</p>
		<p>
			Principal Body Colour: {$licence->body_colour}
		</p>
		<p>
			New Vehicle: {$licence->new}
		</p>
		<p>
			Previously Registered: {$licence->registered}
		</p>
		<p>
			Country: {$licence->country}
		</p>
		<p>
			Reg No: {$licence->reg_no}
		</p>
{/block}
