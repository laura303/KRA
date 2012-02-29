{extends "licence/detailview.tpl"}

{block "data-view"}
	<p>
		Applicant Name: {$licence->applicant_name}
	</p>
	<p>
		Applicant Address: {$licence->address}
	</p>
	<p>
		Licence Number: {$licence->licence_number}
	</p>
	<p>
		Certificate of Competence Number: {$licence->certificate_of_competence}
	</p>
	<p>
		Driving Test Date: {$licence->driving_test_date}
	</p>
	<p>
		Driving Test Place: {$licence->driving_test_place}
	</p>
{/block}
