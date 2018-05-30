<div class="container contactform center" id="home" style="min-height: calc(100% - 270px); margin-top: 0px;">

 <form method="post" action="<?php echo base_url();?>index.php/Neregistruoti_naudotojai/registracija" class="row wowload fadeInLeftBig"> 
        <div class="col-sm-6 col-sm-offset-3 col-xs-12 tableborder"> 
				
		<h4 class="text-center  wowload fadeInUp" style="margin-top: inherit;">Registracija</h4>
		<div>
			<div style="float: right;" class="g-signin2" data-onsuccess="onSignIn" style="text-align: -webkit-center;" data-width="33px"></div>
			<p style="width: max-content; float: right; margin: 6px;">Pildyti duomenis naudojat</p>
		</div>
				
        <label style="width:100%;">Naudotojo vardas:  </label><input type="text" name="naudotojo_vardas" value="<?php echo set_value('naudotojo_vardas'); ?>" required>
		<?php echo form_error('naudotojo_vardas'); /*validation error*/?>
        <label>Vardas:  </label><input type="text" name="vardas" id="vardas" value="<?php echo set_value('vardas'); ?>" required>
		<?php echo form_error('vardas'); /*validation error*/?>
        <label>Pavardė: </label><input type="text" name="pavarde" id="pavarde" value="<?php echo set_value('pavarde'); ?>" required>
		<?php echo form_error('pavarde'); /*validation error*/?>
		<label>El. pašto adresas:  </label><input type="email" name="el_pastas" id="el_pastas" value="<?php echo set_value('el_pastas'); ?>" required>
		<?php echo form_error('el_pastas'); /*validation error*/?>
		<label>Slaptažodis:  </label><input type="password" name="slaptazodis" id="slaptazodis" onchange="arsutampa();" minlength="8" size="50" required>
		<?php echo form_error('slaptazodis'); /*validation error*/?>
		<div class="alert alert-danger collapse" id="nesutampa">
		<strong>Klaida!</strong> Nesutampa slaptažodžiai.
		</div>
		<label>Pakartokite slaptažodį:  </label>
		<input type="password" name="slaptazodis2" id="slaptazodis2" onchange="arsutampa();" style="border-color: default" required>
		<?php echo form_error('slaptazodis2'); /*validation error*/?>
		<label>Statusas:  </label>
		<select name="statusas" onchange="pasirinktas();" id="statusas" style="padding:5px">
			<option value="0" >Pasirinkite</option>
			<option value="Mokinys/ė">Mokinys/ė</option>
			<option value="Studentas/ė">Studentas/ė</option>
			<option value="Kita">Kita</option>
		</select>
		<label id="kas" style="visibility: hidden; padding-left:5px;"></label>
		<input type="number" min=1 max=12 id="mok" name="mok" value="<?php echo set_value('mok'); ?>"style="visibility: hidden; padding: 6px; width: 55px;">
		<input type="text" id="stud" name="stud" value="<?php echo set_value('stud'); ?>" style="visibility: hidden; width: 30%;
    padding: 5px;">
		<?php echo form_error('statusas'); /*validation error*/?>
		</br>
        <button type="submit" class="btn btn-primary" style="width:100%; margin-bottom:15px;"> Registruotis</button>
		
		<!--<div class="fb-login-button" data-max-rows="1" data-size="medium" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="true"></div>
		<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/lt_LT/sdk.js#xfbml=1&autoLogAppEvents=1&version=v2.12&appId=340533513134197';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="g-signin2" data-onsuccess="onSignIn"></div>-->
      </div>
  </form>
</div>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<meta name="google-signin-client_id" content="276286950734-dppmokdpiu9375f33ieqaifkkrtafr5a.apps.googleusercontent.com">
<script type="text/javascript">
function pasirinktas() {
	value = document.getElementById("statusas").value;
    if(value=='Mokinys/ė')
		{
			document.getElementById("stud").style.visibility = "hidden";
			document.getElementById("mok").style.visibility = "visible";
			document.getElementById("kas").style.visibility = "visible";
			document.getElementById("kas").innerHTML = " Klasė: ";
		}
	else if(value=='Studentas/ė')
		{
			document.getElementById("stud").style.visibility = "visible";
			document.getElementById("mok").style.visibility = "hidden";
			document.getElementById("kas").style.visibility = "visible";
			document.getElementById("kas").innerHTML = " Grupės kodas: ";
		}
	else
		{
			document.getElementById("stud").style.visibility = "hidden";
			document.getElementById("mok").style.visibility = "hidden";
			document.getElementById("kas").style.visibility = "hidden";
		}
	//console.log(value);
};
function arsutampa() {
	value1 = document.getElementById("slaptazodis").value;
	value2 = document.getElementById("slaptazodis2").value;
	
	if(value1==value2)
	{
		document.getElementById("slaptazodis2").style.borderColor="#ccc";
		document.getElementById("nesutampa").classList.remove("show");
	}
	else 
	{
		if(!value2 || !value1){}
		else{
		document.getElementById("slaptazodis2").style.borderColor="red";
		document.getElementById("nesutampa").classList.add("show");
		}
	}
	//console.log('true');
}
</script>
<!--GET DATA FROM GOOGLE-->
 <script>
 function onSignIn(googleUser) {
  var profile = googleUser.getBasicProfile();
  //console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
  //console.log('Name: ' + profile.getName());
  //console.log('Image URL: ' + profile.getImageUrl());
  //console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
  
  document.getElementById("vardas").value = profile.getGivenName();
  document.getElementById("pavarde").value = profile.getFamilyName();
  document.getElementById("el_pastas").value = profile.getEmail();
  //document.getElementById("prijungtiPastu").click();
  //window.location = "http://localhost:8080/Varzybos//index.php/Neregistruoti_naudotojai/prisijungimas_su_elPastu";
  
  signOut();
  }
  
  function add_data(googleUser) {
  var profile = googleUser.getBasicProfile();
  //console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
  //console.log('Name: ' + profile.getName());
  //console.log('Image URL: ' + profile.getImageUrl());
  //console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
  
  document.getElementById("vardas").value = profile.getGivenName();
  //document.getElementById("vardas").style.display = "none";
  document.getElementById("pavarde").value = profile.getFamilyName();
  document.getElementById("el_pastas").value = profile.getEmail();
  //document.getElementById("prijungtiPastu").click();
  //window.location = "http://localhost:8080/Varzybos//index.php/Neregistruoti_naudotojai/prisijungimas_su_elPastu";
  
  signOut();
  }
 </script>
 <!--<a href="#" onclick="signOut();">Sign out</a>-->
<script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
     // console.log('User signed out.');
    });
  }
</script>