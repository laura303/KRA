{extends "base.tpl"}

{block "body-container"}
	<div id="container" style="">
		<header>
			<a id="logo"></a>
			<div id="main-navigation">
				<ul>
					<li>
						<a href="{#BASE_URL#}/dashboard/" class="active" id="dashboard-m"><span class="dashboard-32" title="Dashboard area">Home</span></a>
					</li>
					<li>
						<a href="{#BASE_URL#}/applications/" id="elements-m"><span class="files-32" title="Elements area">Licences</span></a>
					</li>
					<li>
						<a href="{#BASE_URL#}/payments/" id="forms-m"><span class="forms-32" title="Forms area">Payments</span></a>
					</li>
				</ul>
			</div>
			<div id="profile">
				<div id="user-data">
					Welcome: {$request->user->username}
					<br>
					{$request->user->email}
				</div>
				<div class="clearfix"></div>
				<div id="user-notifications">
					<ul>
						<li>
							<a class="profile-16 tt-top-center"></a>
						</li>
						<li>
							<a href="{#BASE_URL#}/logout/" id="logout" class="logout-16 tt-top-center">logout</a>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</header>
		<div id="main" role="main">
			{block "main"}
				<div id="sub-navigation">
					{block "main"}
						<div id="navigation-search">
							<form method="GET" action="{#BASE_URL#}/search/">
								<input type="text" name="q" id="search" placeholder="Search" class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
							</form>
						</div>
						<ul>
						</ul>
						<div class="clearfix"></div>
					{/block}
				</div>
				<div id="main-container">
					{block "main-container"}
						<div id="body-section" style="">
							<div id="left-menu">
								{block "left-menu"}
									<ul>
										<li class="menu-trigger">
											<a href="#" class="overview-16" id="c-overview">Overview</a>
										</li>
									</ul>
								{/block}
								<div class="clearfix"></div>
							</div>
							<div id="content-container">
								{block "content-container"}
									<div id="content">
										{block "content"}
										{/block}
									</div>
								{/block}
							</div>
							<div class="clearfix"></div>
						</div>
					{/block}
				</div>
			{/block}
		</div>
		{block "footer"}
			<footer>
				<span>
				</span>
			</footer>
		{/block}
	</div>
	<!--! end of #container -->
{/block}