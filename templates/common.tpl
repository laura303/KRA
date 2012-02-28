{extends "base.tpl"}

{block "body-container"}
	<div id="container" style="">
		<header>
			<a id="logo"></a>
			<div id="main-navigation">
				<ul>
					<li>
						<a href="#dashboard" class="active" id="dashboard-m"><span class="dashboard-32" title="Dashboard area">Home</span></a>
					</li>
					<li>
						<a href="#elements" id="elements-m"><span class="files-32" title="Elements area">Menu</span></a>
					</li>
					<li>
						<a href="#forms" id="forms-m"><span class="forms-32" title="Forms area">Services</span></a>
					</li>
					<li>
						<a href="#file" id="file-m"><span class="file-32" title="File manager area">Feedback</span></a>
					</li>
					<li>
						<a href="#charts" id="charts-m"><span class="charts-32" title="Charts area">Brochures</span></a>
					</li>
				</ul>
			</div>
			<div id="profile">
				<div id="user-data">
					Welcome: {$request->user->getFullName() || $request->user->username}
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
							<a href="{#BASE_URL#}/auth/logout/" id="logout" class="logout-16 tt-top-center">logout</a>
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
							<li>
								<a href="{#BASE_URL#}/orders/undelivered/" class="tt-top-center">7</a><span>undelivered orders</span>
							</li>
							<li>
								<a href="{#BASE_URL#}/orders/new/" class="blue tt-top-center">12</a><span>new orders</span>
							</li>
							<li>
								<a href="{#BASE_URL#}/orders/delivered/" class="green tt-top-center">3</a><span>delivered orders</span>
							</li>
						</ul>
						<a class="comment-16 tt-top-center" id="show-modal">modal</a>
						<a class="info-16 tt-top-center" id="add-notify">notifications</a>
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