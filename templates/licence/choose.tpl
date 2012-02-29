{extends "licence/base.tpl"}

{block "content"}
	<div class="c-overview">
		<div class="bredcrumbs">
			<ul>
				<li>
					<a href="#">applications</a>
				</li>
			</ul>
			<div class="clearfix"></div>
		</div>
		{if $request->user->belongsToGroups('admin')}
			<div class="box-element">
				<div class="box-head-light box-head-icon">
					Admin Quick actions<a href="" class="collapsable"></a>
				</div>
				<div class="box-content">
					<ul class="actions">
						<li>
							<div>
								<a href="{#BASE_URL#}/applications/drivers-licence/" class="">Drivers Licence</a><span>view licences</span>
							</div>
						</li>
						<li>
							<div>
								<a href="{#BASE_URL#}/applications/motor-vehicle/" class="">Motor Vehicle</a><span>view licences</span>
							</div>
						</li>
						<li>
							<div>
								<a href="{#BASE_URL#}/applications/driving-instructor/" class="">Instructor</a><span>view licences</span>
							</div>
						</li>
						<li>
							<div>
								<a href="{#BASE_URL#}/applications/psv-licences-badges/" class="">PSV Licences</a><span>view licences</span>
							</div>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
			</div>
		{else}
			<div class="box-element">
				<div class="box-head-light box-head-icon">
					Applications actions<a href="" class="collapsable"></a>
				</div>
				<div class="box-content">
					<ul class="actions">
						<li>
							<div>
								<a href="{#BASE_URL#}/licences/drivers-licence/apply/" class="">Drivers Licence</a><span>apply for licence</span>
							</div>
						</li>
						<li>
							<div>
								<a href="{#BASE_URL#}/licences/motor-vehicle/apply/" class="">Motor Vehicle</a><span>apply for licence</span>
							</div>
						</li>
						<li>
							<div>
								<a href="{#BASE_URL#}/licences/driving-instructor/apply/" class="">Instructor</a><span>apply for licence</span>
							</div>
						</li>
						<li>
							<div>
								<a href="{#BASE_URL#}/licences/psv-licences-badges/apply/" class="">PSV Licences</a><span>apply for licence</span>
							</div>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
			</div>
		{/if}
		<div class="clearfix"></div>
	</div>
{/block}