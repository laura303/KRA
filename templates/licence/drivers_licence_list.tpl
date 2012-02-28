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
						<td>{$licence->driving_test_date}</td>
						<td>{$licence->driving_test_place}</td>
						<td>
							<a href="{#BASE_URL#}/licences/{$licence->id}/">R</a> |
							<a href="{#BASE_URL#}/admin/licences/{$type}/{$licence->id}/edit/">U</a> |
							<a href="{#BASE_URL#}/admin/licences/{$type}/{$licence->id}/delete/">D</a>
						</td>
					</tr>
				{/foreach}
			</table>
		</div>
	</div>
{/block}