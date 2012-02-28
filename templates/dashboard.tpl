{extends "common.tpl"}

{block "content"}
	<div class="c-overview">
		{if $request->user->belongsToGroups('admin')}
			<div class="bredcrumbs">
				<ul>
					<li>
						<a href="#">dashboard</a>
					</li>
					<li>
						<a href="#">overview</a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="box-element">
				<div class="box-head-light box-head-icon">
					Admin Quick actions<a href="" class="collapsable"></a>
				</div>
				<div class="box-content">
					<ul class="actions">
						<li>
							<div>
								<a href="{#BASE_URL#}/admin/users/" class="">Users</a><span>edit users</span>
							</div>
						</li>
						<li>
							<div>
								<a href="{#BASE_URL#}/applications/" class="">Applications</a><span>new applications</span>
							</div>
						</li>
						<li>
							<div>
								<a href="{#BASE_URL#}/admin/users/" class="">Licences</a><span>edit licences</span>
							</div>
						</li>
						<li>
							<div>
								<a href="{#BASE_URL#}/admin/users/" class="">Payments</a><span>view statistics</span>
							</div>
						</li>
						<li>
							<div>
								<a href="#" class="">Revenue</a><span>view collection</span>
							</div>
						</li>
						<li>
							<div>
								<a href="#" class="">Reports</a><span>view reports</span>
							</div>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="box-element">
				<div class="box-head-light box-head-icon info-16">
					Notification board<a href="" class="collapsable"></a>
				</div>
				<div class="box-content">
					<ul class="notifications">
						<li>
							<div>
								321<span>total visitors</span><span class="green">+13%</span>
							</div>
						</li>
						<li>
							<div>
								321<span>total visitors</span><span class="red">-7%</span>
							</div>
						</li>
						<li>
							<div>
								321<span>total visitors</span><span class="green">+13%</span>
							</div>
						</li>
						<li>
							<div>
								321<span>total visitors</span><span class="grey">0%</span>
							</div>
						</li>
						<li>
							<div>
								321<span>total visitors</span><span class="green">-2%</span>
							</div>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
			</div>
		{else}
			<div class="bredcrumbs">
				<ul>
					<li>
						<a href="{#BASE_URL#}/">home</a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="box-element">
				<div class="box-head-light box-head-icon">
					<a href="" class="collapsable"></a>
					Licences
				</div>
				<div>
					<ul>
						{foreach $licences as $licence}
							<li>Licence #{$application->licence_number}</li>
						{foreachelse}
							<li>No Licences Approved yet</li>
						{/foreach}
					</ul>
				</div>
			</div>
			<div class="box-element">
				<div class="box-head-light box-head-icon">
					<a href="" class="collapsable"></a>
					Applications
				</div>
				<div>
					<ul>
						{foreach $applications as $application}
							<li>Application #{$application->id}</li>
						{foreachelse}
							<li>No Applications Made</li>
						{/foreach}
					</ul>
				</div>
			</div>
		{/if}
		<div class="clearfix"></div>
	</div>
{/block}