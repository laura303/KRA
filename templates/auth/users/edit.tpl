{extends "common.tpl"}

{block "content"}
	<h2>Edit User</h2>
	<form method="POST" action=".">
			<input type="hidden" name="user[type]" value="user" />
			<fieldset>
				<section>
					<div class="section-left-s">
						<label for="id_username">Username:</label>
					</div>
					<div class="section-right">
						<input type="text" class="i-text required" name="user[username]" value="{$user->username}" />
					</div>
					<div class="clearfix"></div>
				</section>
				<section>
					<div class="section-left-s">
						<label for="id_email">Email:</label>
					</div>
					<div class="section-right">
						<input id="id_email" class="i-text required" type="text" name="user[email]" value="{$user->email}" />
					</div>
					<div class="clearfix"></div>
				</section>
				<section>
					<div class="section-left-s">
						<label for="id_is_active">Is Active:</label>
					</div>
					<div class="section-right">
						<input id="id_is_active" class="i-text required" type="checkbox" name="user[is_active]" value="{$user->is_active}"/>
					</td>
					<div class="clearfix"></div>
				</section>
				<section>
					<div class="section-left-s">
						<label for="id_groups">Groups:</label>
					</div>
					<div class="section-right">
						<select class="i-select multiple" multiple name="groups[]" size="{$groups|@count}">
							{foreach $groups as $group}
								<option value="{$group->id}"
									{if isset($related_groups[$group->id])}
										selected="selected"
									{/if}
								>{$group->name}</option>
							{/foreach}
						</select>
					</td>
					<div class="clearfix"></div>
				</section>
				<section>
					<div class="clearfix"></div>
			        <input type="submit" name="submit" id="" class="i-button no-margin" value="Save">
			        <div class="clearfix"></div>
			    </section>
			</fieldset>
		</table>
	</form>
{/block}