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
		<div class="box-element">
			<div class="box-head-light box-head-icon">
				Admin Quick actions<a href="" class="collapsable"></a>
			</div>
			<div class="box-content">
				<ul class="actions">
					<li>
						<div>
							<a href="{#BASE_URL#}/applications/drivers-licence/" class="">Drivers Licence</a><span>add licences</span>
						</div>
					</li>
					<li>
						<div>
							<a href="{#BASE_URL#}/applications/motor-vehicle/" class="">Motor Vehicle</a><span>add licences</span>
						</div>
					</li>
					<li>
						<div>
							<a href="{#BASE_URL#}/applications/driving-instructor/" class="">Driving Instructor</a><span>add licences</span>
						</div>
					</li>
					<li>
						<div>
							<a href="{#BASE_URL#}/applications/psv-licences-badges/" class="">PSV Licences</a><span>add licences</span>
						</div>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
{/block}