<script src="https://apis.google.com/js/platform.js" async defer></script>
<meta name="google-signin-client_id" content="276286950734-dppmokdpiu9375f33ieqaifkkrtafr5a.apps.googleusercontent.com">

<div id="home" class="container contactform center" style="min-height: calc(100% - 270px);
    margin-top: 40px;">
  <div class="row wowload fadeInLeftBig "> 
	
      <div class="col-sm-6 col-sm-offset-3 col-xs-12 tableborder">   
		<h4 class="text-center  wowload fadeInUp" style="margin-top: inherit;">Prisijungimas</h4>
		<?php
			if(isset($b))
			{
				if($b=="error")
				{
					echo "<div class=\"alert alert-danger collapse show\" >
		<strong>Klaida!</strong> Netinkamas naudotojo vardas arba slaptažodis.
		</div>";
				}
			}
		?>	
        <?php echo form_open('Neregistruoti_naudotojai/prisijungimas'); ?>
		<label>Naudotojo vardas:  </label><input type="text" name="Naudotojo_vardas" required>
		<?php echo form_error('Naudotojo_vardas'); /*validation error*/?>
		<label>Slaptažodis:  </label><input type="password" name="Slaptazodis" required>
		</br>
        <input type="submit" value="Prisijungti" class="btn btn-primary" name="submit"/></br>
		
		<a data-toggle="modal" data-target="#primintiSlapt" style="float:right;width: 100%;    text-align: right;">Pamiršote slaptažodį?</a>
		<?php echo form_close(); ?>
		<br>
		<div style="margin-bottom: 30px; height: fit-content;">
		<hr style="width: 40%; float: left;">
		<p style=" width: 20%; float: left; text-align: center; margin-top: 10px; margin-bottom:0px;">ARBA</p>
		<hr style="width: 40%; float: right;">
		</div>
		<br>
		<div class="g-signin2" data-onsuccess="onSignIn" style="text-align: -webkit-center;"></div>
		<form method="post" action="<?php echo base_url();?>index.php/Neregistruoti_naudotojai/prisijungimas_su_elPastu"> 
		<input type="email" name="epastas"  id="epastas" style="display:none"/>		
        <button type="submit" class="btn btn-primary" id="prijungtiPastu" style="display:none"> Prisijungti</button>
		</form>
      </div>
  </div>
</div>

	  <!-- Modal Slaptažodžio priminimui-->
<div class="modal fade" id="primintiSlapt" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Naujo slaptažodžio gavimas</h4>
        </div>
        <div class="modal-body">		
		<form method="post" action="<?php echo base_url();?>index.php/Neregistruoti_naudotojai/slaptazodzio_keitimas"><!--slaptKeitimo_zinute">--> 
			<p>Įveskite savo naudotojo vardą ir gausite naują slaptožodį el.paštu.</p>
			<label> Naudotojo vardas:  </label>
			<input type="text" name="naudotojo_vardas" 
					value="<?php echo set_value('naudotojo_vardas'); ?>" 
					style="width: 100%; padding: 1em; border: 1px solid #ccc;
							margin-bottom: 1em; border-radius: 0; outline: none;" required><br/>
			<?php echo form_error('naudotojo_vardas'); /*validation error*/?>
			
        </div>
		
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" style="margin-bottom: 0px;">Atšaukti</button>
		  <button type="submit" class="btn btn-primary" style="width:49%"><i class="submit"></i>Priminti</button>
        </div>
		</form>
      </div>
    </div>
 </div>
 <!--PRISIJUNGIMAS SU GOOGLE-->
 <script>
 function onSignIn(googleUser) {
  var profile = googleUser.getBasicProfile();
  //console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
  //console.log('Name: ' + profile.getName());
  //console.log('Image URL: ' + profile.getImageUrl());
  //console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
  
  document.getElementById("epastas").value = profile.getEmail();
  document.getElementById("prijungtiPastu").click();
  //window.location = "http://localhost:8080/Varzybos//index.php/Neregistruoti_naudotojai/prisijungimas_su_elPastu";
  
  signOut();
  }
 </script>
 <!--<a href="#" onclick="signOut();">Sign out</a>-->
<script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
  }
</script>