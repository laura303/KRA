{extends "licence/base.tpl"}

{block "content"}
	<div class="c-elements">		
		<div class="box-element">
			<div class="box-head-light">Apply for {$readable_type|capitalize} Licence<a href="" class="collapsable"></a></div>
			<div class="box-content no-padding">
				<form method="POST" action="." class="i-validate" novalidate="novalidate" enctype="multipart/form-data">
					<input type="hidden" name="licence[type]" value="{$db_type}" />
					<fieldset>
						{block 'form-items'}
						{/block}
					</fieldset>
					<section>
						<div class="clearfix"></div>
				        <input type="submit" name="submit" id="" class="i-button no-margin" value="Save">
				        <div class="clearfix"></div>
				    </section>
				</form>
			</div>
		</div>
	</div>
{/block}
