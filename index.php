<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="content/css/reset.css">
		<link rel="stylesheet" type="text/css" href="content/css/index-layout.css">				
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>

		<title>sayHello.</title>
	</head>
	<body>
		<div id="topBar"></div>
		<div id="container">
			<div id="leftSection"><h1 id="logo"><a href="./">sayHELLO.</a></h1><br />
				<div id="sideMenu">
				<ul>
					<li><a href="#">Inbox</a></li>
					<li><a href="#">Outbox</a></li>
				</ul>
				</div>
			</div>

			<div id="mainSection">
				<h3 id="audioListHeader"><a href="#">Audio inbox</a></h3>
				<div id="recordingList">
					<div class="recordingDiv">
						<p class="from">From: <a href="#" class="username">killingfloor</a></p>
						<span class="remove">
							<span class="deleteRecInbox">X</span>
						</span>
						<p class="recordingTime">2013-03-07 09:23:26</p>
						<div><audio src="/sayhello/recs/1_20130307102326.wav" controls></audio></div>
					</div>
					<div class="recordingDiv">
						<p class="from">From: <a href="#" class="username">killingfloor</a></p>
						<span class="remove">
							<span class="deleteRecInbox">X</span>
						</span>
						<p class="recordingTime">2013-02-22 10:47:22</p>
						<div><audio src="/sayhello/recs/1_20130222114722.wav" controls></audio></div>
					</div>
				</div>
			</div>


			<div id="rightSectionWrapper">
				<div id="rightSection">
					<div id="recorderContainer">

					<div id="recorderTime">
					  Time: <span id="time">00:00</span>
					</div>
					<div id="levelbase">
					  <div id="levelbar"></div>
					</div>
					<div id="recorderLevel">
					  Level: <span id="level"></span>
					</div>  
					<div id="recorderStatus">
					  Status: <span id="status"></span>
					</div>

					<button id="record">Record</button>
					<button id="stop">Stop/Play</button>
					
					</div>
					<div id="shareToUsernameContainer">
						<input type="text" id="shareToUsername" value="username" />
						<button id="send">Send recording</button>
					</div>
				</div>
			</div>


		</div>
		<script src="/sayhello/js/jRecorder.js"></script>
		<script src="/sayhello/js/init.js"></script>
		<script src="/sayhello/js/recInit.js"></script>
		<script src="/sayhello/js/recEvent.js"></script>
	</body>
</html>