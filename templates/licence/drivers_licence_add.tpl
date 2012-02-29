{extends "licence/add.tpl"}

{block 'form-items'}
	<section>
		<div class="section-left-s"><label for="id_driving_test_place">Applicant Address:</label></div>
		<div class="section-right"><input type="text" class="i-text required"name="licence[address]" value="{$licence->address}" /></div>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label for="id_licence_number">Licence Number:</label></div>
		<div class="section-right"><input type="text" class="i-text required"name="licence[licence_number]" value="{$licence->licence_number}" /></div>
		<div class="clearfix"></div>
	</section>
	<!--<section>
		<div class="section-left-s"><label for="id_certificate_of_competence">Certificate of Competence Number:</label></div>
		<div class="section-right"><input type="file" class="i-text required"name="certificate_of_competence" value="{$licence->certificate_of_competence}" /></div>
		<div class="clearfix"></div>
	</section>-->
	<section>
		<div class="section-left-s"><label for="id_driving_test_date">Driving Test Date:</label></div>
		<div class="section-right"><input type="text" class="i-text i-datepicker required hasDatepicker" name="licence[driving_test_date]" value="{$licence->driving_test_date}" /></div>
		<div class="clearfix"></div>
	</section>
	<section>
		<div class="section-left-s"><label for="id_driving_test_place">Driving Test Place:</label></div>
		<div class="section-right">
			<input type="text" class="i-text required"name="licence[driving_test_place]" value="{$licence->driving_test_place}" />
		</div>
		<div class="clearfix"></div>
	</section>
{/block}