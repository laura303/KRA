{extends "common.tpl"}

{block "content"}
<div class="box-element">
	<div class="box-head-light">Drivers Licences<a href="" class="collapsable"></a></div>
	<div class="box-content no-padding">
		<table class="i-table fullwidth">
			<thead>
				<tr>
					<th>Mpesa Code</th>
					<th>Message</th>
					<th>Renewal Duration</th>
					<th>Expiry Date</th>
					<th>Amount Paid</th>
				</tr>
			</thead>
			{foreach $payments as $payment}
				<tr>
					<td><center>{$payment->mpesa_code}</center></td>
					<td><center>{$payment->message}</center></td>
					<td><center>{$payment->renewal_duration} Years</center></td>
					<td><center>{$payment->expiry_date|date_format:"d/m/Y"}</center></td>
					<td><center>{$payment->amount_paid}</center></td>
				</tr>
			{/foreach}
		</table>
	</div>
</div>
{/block}