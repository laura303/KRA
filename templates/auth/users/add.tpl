{extends "common.tpl"}

{block "content"}

	<h2>Add User</h2>
	<form method="POST" action=".">
		<input type="hidden" name="user[type]" value="user" />
		<p>
			<label for="id_username">Username:</label>
			<input type="text" name="user[username]" value="" />
		</p>
		<p>
			<label for="id_username">Full Name:</label>
			<input type="text" name="user[fullname]" value="" />
		</p>
		<p>
			<label for="id_password">Password:</label>
			<input id="id_password" type="password" name="user[password]" value="" />
		</p>
		<p>
			<label for="id_email">Email:</label>
			<input id="id_email" type="text" name="user[email]" value="" />
		</p>
		<p>
			<label for="id_is_active">Is Active:</label>
			<input id="id_is_active" type="checkbox" name="user[is_active]"/>
		</p>
		<p>
			<label for="id_groups">Groups:</label>
			<select multiple name="groups[]" size="{$groups|@count}">
				{foreach $groups as $group}
					<option value="{$group->id}">{$group->name}</option>
				{/foreach}
			</select>
			
		</p>
		<p>
			<input type="submit" value="Add">
		</p>
	</form>
{/block}