{extends "common.tpl"}

{block "content"}
	<div class="btn-box">
		<div class="content">
			<H2>Results:</H2>
			<ul>
				{foreach $results as $result}
				    <li><a href="{#BASE_URL#}{if isset($result->username)}/admin/users/{$result->id}/{else}/licences/motor-vehicle/{$result->id}/{/if}">{if isset($result->username)}{$result->username}{else}{$result->licence_number}{/if}</a></li>
				{foreachelse}
					<li>No Results found</li>
				{/foreach}
			</ul>
		</div>
	</div>
{/block}