{extends "licence/add.tpl"}

{block 'form-items'}
	<section>
		<div class="section-left-s"><label for="id_driving_test_place">Driving Licence:</label></div>
		<div class="section-right"><input type="file" class="i-text required"name="driving_licence" value="{$licence->driving_licence}" /></div>
		<div class="clearfix"x></div>
	</section>
	<section>
		<div class="section-left-s"><label for="id_driving_test_place">National Id:</label></div>
		<div class="section-right"><input type="file" class="i-text required"name="national_id" value="{$licence->national_id}" /></div>
		<div class="clearfix"x></div>
	</section>
	<section>
		<div class="section-left-s"><label for="id_driving_test_place">Certificate of Good conduct:</label></div>
		<div class="section-right"><input type="file" class="i-text required"name="certificate_of_good_conduct" value="{$licence->national_id}" /></div>
		<div class="clearfix"x></div>
	</section>
{/block}