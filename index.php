<!DOCTYPE html>
<html>

	<head>
		<title>Doodle Password</title>
		<link rel="stylesheet" type="text/css" href="doodleStyle.css">



	</head>
	<body>
		<script>
			var agreed = false;

			function goToRegister() {
				if (agreed) {
					window.location.href = "register.php";
				}
			}

			function changeAgree() {
				agreed = !agreed; // change agreed

				var button = document.getElementById('getStarted');
				if (agreed) {
					// enable button
					console.log("enabled")
					button.className = button.className.replace(/\bdisabled\b/, "");
				} else {
					// disable button
					button.className += "disabled";
				}
			}

			document.body.onload = function() {
				console.log("I loaded!");
				var checkbox = document.getElementById('agreeBox');
				checkbox.checked = false;
			}
		</script>


		<div id="titleBar">
			<div class="blue left"><img class="titleImg" src="doodle.svg"></div>
			<span id="vs">VS</span>
			<div class="red right"><img class="titleImg" src="password.svg"></div>
		</div>

		<div id="inside">
			<div class="liteBox">
				<h1>The Doodle Project</h1>
				<p>
					Don’t like passwords? Try out a Doodle Password and see which one you prefer.
					You doodle on a picture and that is your graphical password.
					All you have to do is redraw your doodle to login.
				</p>

				<h2>Tutorial</h2>
				<p> Please take the tutorial if this is your first time or if you want to learn more about Doodle Passwords! </p>
				<a href="tutorial.php"><button type="button">Let's Go!</button></a>
			</div>

			<div class="liteBox consent">
				<h3>Purpose</h3>
				<p>
					The purpose of this research is to determine if a Doodle Password can feasibly replace text passwords and determine this from the user’s perspective.
					Information gathered will provide valuable information for research to indicate the acceptance of Doodle Passwords.
					The information can also be used to improve the Doodle Password.
				</p>

				<h3>How this project works</h3>
				<p>
					<ol>
						<li>The next page will guide you to register using an email address, Doodle Password, and text password.</li>
						<li>You will be emailed in one week to login using the same credentials.</li>
						<li>After you login, a short survey will be provided to see how you feel about Doodle Passwords compared to text passwords.</li>
						<li>That's it!</li>
					</ol>
				</p>

				<h3>Time</h3>
				<p>
					Tutorial and Registration: 5 - 15 minutes <br>
					Logging in and Survey: 5 - 15 minutes
				</p>

				<h3>Risks and Benefits</h3>
				<p>
					There are no anticipated risks or benefits associated with this study.
				</p>

				<h3>Confidentiality</h3>
				<p>
					The only sensitive information collected is your <b>email address</b> which is stored on a personal server with limited access.
					Nobody can log into it from the Internet. The email address is stored in a database that is protected by another set of login credentials.

					<b>Your email will not be shared or given out.</b> It will also not be used in any report or publication.
					All other information (doodle password, survey information, etc.) is subject for analysis or use for research.
				</p>

				<h3>This is Voluntary</h3>
				<p>
					Participating in this study is <b>completely voluntary</b>.
					There is no penalty or loss of benefit for choosing not to participate. <br>
					<b>You can withdraw from the study at any time</b> with no penalty or consequence.
				</p>

				<h3>Questions?</h3>
				<p>
					If you have any questions, you may contact me (Oliver) by email at <a href="mailto:mpl934@mocs.utc.edu">mpl934@mocs.utc.edu</a>
						or my faculty advisor (Dr. Yang) at <a href="mailto:li-yang@utc.edu">li-yang@utc.edu</a>.
						<br><br>
					If you have any additional concerns about your rights as a participant in this research study, you may contact Dr. Amy Doolittle,
						the Chair of the Human Subjects Committee, Institutional Review Board at (423) 425-5563 or by email at <a href="amy-doolittle@utc.edu">amy-doolittle@utc.edu</a>.
					Additional contact information is available at <a href="http://www.utc.edu/irb">www.utc.edu/irb</a>.
				</p>
			</div>


			<div class="liteBox">
				<h2>Ready?</h2>
				<br>
				<input id="agreeBox" onClick="changeAgree();" type="checkbox" unchecked><label> I agree to participate in this research and assure that I am at least 18 years of age. </label>
				<br><br>
				<button id="getStarted" type="button" class="disabled" onClick="goToRegister();">Get Started!</button>
			</div>
		</div>
	</body>
</html>
