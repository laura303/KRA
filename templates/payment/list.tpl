{extends "common.tpl"}

{block "content"}
	<H2>Payments:</H2>
	<table>
		<tr>
			<th>Id</th>
			<th>Actions</th>
		</tr>
		{foreach $payments as $payment}
			<tr>
				<td>{$payment->id}</td>
				<td>
					<a href="{#BASE_URL#}/payments/{$payment->id}/">R</a> |
					<a href="{#BASE_URL#}/payments/edit/{$payment->id}/">U</a> |
					<a href="{#BASE_URL#}/payments/delete/{$payment->id}/">D</a>
				</td>
			</tr>
		{/foreach}
	</table>
{/block}