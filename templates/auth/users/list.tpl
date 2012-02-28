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
						<td><a href="{#BASE_URL#}/admin/users/{$user->id}/">{$user->username}</a></td>
						<td>{$user->email}</td>
						<td></td>
					</tr>
				{/foreach}
			</tbody>
		</table>
{/block}