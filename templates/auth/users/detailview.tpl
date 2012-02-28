{extends "common.tpl"}

{block "content"}
	<h2>User: {$user->username}</h2>
	<p>
		Username: {$user->username}
	</p>
	<p>
		Email: {$user->email}
	</p>
	<p>
		<div><h4>Driver Licences</h4></div>
		<div>
			<table class="i-table fullwidth">
				<tr>
					<th>Id</th>
					<th>Applicant Name</th>
					<th>Applicant Address</th>
					<th>Licence Number</th>
					<th>Driving Test Date</th>
					<th>Driving Test Place</th>
				</tr>
				{foreach $user->ownLicence as $licence}
					<tr>
						<td>{$licence->id}</td>
						<td>{$licence->applicant_name}</td>
						<td>{$licence->address}</td>
						<td>{$licence->licence_number}</td>
						<td>{$licence->driving_test_date}</td>
						<td>{$licence->driving_test_place}</td>
						<td>
							<a href="{#BASE_URL#}/licences/{$licence->type}/{$licence->id}/">R</a> |
							<a href="{#BASE_URL#}/licences/{$licence->type}/{$licence->id}/edit/">U</a> |
							<a href="{#BASE_URL#}/licences/{$licence->type}/{$licence->id}/delete/">D</a>
						</td>
					</tr>
				{/foreach}
			</table>
		</div>
	</p>
{/block}