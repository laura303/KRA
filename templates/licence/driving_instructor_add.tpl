{extends "licence/add.tpl"}

{block 'form-items'}
	<section>
		<div class="section-left-s"><label for="id_driving_test_place">Certificate of Competence:</label></div>
		<div class="section-right"><input type="file" class="i-text required"name="certififcate_of_competence" value="{$licence->certififcate_of_competence}" /></div>
		<div class="clearfix"x></div>
	</section>
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
		<div class="section-left-s"><label for="id_driving_test_place">Passport Photo:</label></div>
		<div class="section-right"><input type="file" class="i-text required"name="passport_photo" value="{$licence->passport_photo}" /></div>
		<div class="clearfix"x></div>
	</section>
{/block}