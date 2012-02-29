{extends "common.tpl"}

{block "content"} <H2>Users:</H2>
<a href="{#BASE_URL#}/admin/users/add/">Add User</a>
<div class="box-element">
    <div class="box-head-light">Users<a href="" class="collapsable"></a></div>
    <div class="box-content no-padding">
		<table class="i-table fullwidth">
			<thead>
				<tr>
					<td>Id</td>
					<td>Name</td>
					<td>Email</td>
					<td>Actions</td>
				</tr>
			</thead>
			<tbody>
				{foreach $users as $user}
					<tr class="dark">
						<td>{$user->id}</td>
						<td>{$user->username}</td>
						<td>{$user->email}</td>
						<td>
							<a href="{#BASE_URL#}/admin/users/{$user->id}/">View</a>
							{if $request->user->belongsToGroups('admin')}
								|
								<a href="{#BASE_URL#}/admin/users/{$user->id}/edit/">Edit</a> |
								<a href="{#BASE_URL#}/admin/users/{$user->id}/delete/">Delete</a>
							{/if}
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
{/block}