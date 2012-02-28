{extends "licence/base.tpl"}

{block "content"}
	<div class="box-element">
        <div class="box-head-light">CSS markup for the form above<a href="" class="collapsable"></a></div>
        <div class="box-content no-padding">
			<table class="i-table fullwidth">
				<thead>
					<tr>
						<th>Id</th>
						<th>Applicant Name</th>
						<th>Applicant Address</th>
						<th>Licence Number</th>
						<th>Certificate of Competence Number</th>
						<th>Driving Test Date</th>
						<th>Driving Test Place</th>
					</tr>
				</thead>
				{foreach $licences as $licence}
					<tr>
						<td>{$licence->id}</td>
						<td>{$licence->applicant_name}</td>
						<td>{$licence->address}</td>
						<td>{$licence->licence_number}</td>
						<td>{$licence->certificate_of_competence}</td>
						<td>{$licence->driving_test_date}</td>
						<td>{$licence->driving_test_place}</td>
						<td>
							<a href="{#BASE_URL#}/licences/{$licence->id}/">R</a> |
							<a href="{#BASE_URL#}/licences/edit/{$licence->id}/">U</a> |
							<a href="{#BASE_URL#}/licences/delete/{$licence->id}/">D</a>
						</td>
					</tr>
				{/foreach}
			</table>
		</div>
{/block}