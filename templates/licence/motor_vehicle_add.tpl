{extends "licence/add.tpl"}

{block 'form-items'}
	<section>
		<div class="section-left-s"><label>Motor Vehicle Type:</label></div>
		<input type="text" class="i-text required" class="text" name="licence[motor_vehicle_type]"  value="{$licence->motor_vehicle_type}"/>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label>Motor Vehicle Make:</label></div>
		<input type="text" class="i-text required" class="text" name="licence[make]"  value="{$licence->make}"/>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label>Body type:</label></div>
		<input type="text" class="i-text required" class="text" name="licence[body_type]"  value="{$licence->body_type}"/>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label>Year of Manufacture:</label></div>
		<select name="licence[year_of_manufacture]">
			<option value="">Select Year:</option>
			<option value="2004">2004</option>
			<option value="2005">2005</option>
			<option value="2006">2006</option>
			<option value="2007">2007</option>
			<option value="2008">2008</option>
			<option value="2009">2009</option>
			<option value="2010">2010</option>
			<option value="2011">2011</option>
			<option value="2012">2012</option>
		</select>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label>Tare Weight:</label></div>
		<input type="text" class="i-text required" class="text" name="licence[tare_weight]"  value="{$licence->tare_weight}"/>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label>Number of Axles:</label></div>
		<input type="text" class="i-text required" class="text" name="licence[number_of_axles]"  value="{$licence->number_of_axles}"/>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label>Value:</label></div>
		<input type="text" class="i-text required" class="text" name="licence[value]"  value="{$licence->value}"/>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label>Principal Body Colour:</label></div>
		<input type="text" class="i-text required" class="text" name="licence[body_colour]"  value="{$licence->body_colour}"/>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label>New Vehicle:</label></div>
		<input type="checkbox" class="text" name="licence[new]"  value="{$licence->new}"/>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label>Previously Registered:</label></div>
		<input type="checkbox" class="text" name="licence[registered]"  value="{$licence->registered}"/>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label>Country:</label></div>
		<input type="text" class="i-text required" class="text" name="licence[country]"  value="{$licence->country}"/>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label>Reg No:</label></div>
		<input type="text" class="i-text required" class="text" name="licence[reg_no]"  value="{$licence->reg_no}"/>
		<div class="clearfix"></div>
	</section>
{/block}
