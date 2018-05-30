<div id="home" class="container contactform center" style="min-height: calc(100% - 200px);">
  <div > 
	
      <div class="col-sm-8 col-sm-offset-2 col-xs-12 tableborder fadeInUp animated"> 
		<h4 class="text-center  wowload fadeInUp" style="margin-top: inherit;">Naudotojo duomenys</h4>	  
        
			<?php 
				if(!isset($kito))
			{//pačio naudotojo profilis
			
				foreach ($n_duomenys as $duomenys)
				{
				echo "<label>Naudotojo vardas:  </label> ".$duomenys->Naud_vardas;
				echo "</br><label>Vardas:  </label> ".$duomenys->Vardas;
				echo "</br><label>Pavardė: </label> ".$duomenys->Pavarde;
				echo "</br><label>El. pašto adresas:  </label> ".$duomenys->E_pastas;
				if($duomenys->Statusas == "Studentas/ė")
					{
						echo "<br><label>Statusas:  </label> Studentas/ė (".$duomenys->Grupe.")";
					}
				else if($duomenys->Statusas == "Mokinys/ė")
					{
						echo "<br><label>Statusas:  </label> Mokinys/ė (".$duomenys->Klase.")";
					}
				else {echo "<br><label>Statusas:  </label> Kita";}
				
				echo "<br><label>Naudotojo reitingo taskai:  </label> ";
				if(is_null($duomenys->Reitingo_taskai)){echo "nėra";}else{echo $duomenys->Reitingo_taskai;}
				$datetime = new DateTime($duomenys->Registracijos_data);				
				echo "<br><label>Naudotojas nuo </label> ".$datetime->format('Y-m-d');
				} 
				?>
				<br><br>
				<a class="btn btn-primary" href="<?php echo base_url();?>index.php/Registruoti_naudotojai/keisti_profili"> Keisti profilio duomenis</a>
				<a class="btn btn-primary" data-toggle="modal" data-target="#KeistiSlapt" style="float:right;"> Keisti slaptažodį</a>
		</div>
		<?php } 
			else//ziurimi kito naudotojo duomenys
			{
				foreach ($n_duomenys as $duomenys)
				{
				echo "<label>Naudotojo vardas:  </label> ".$duomenys->Naud_vardas;
				if($this->session->userdata['logged_in']['Yra_administratorius'])
				{
					if($duomenys->Yra_administratorius == 1){echo " (administratorius)";}else {echo " (ne administratorius)";}
					echo "</br><label>Vardas:  </label> ".$duomenys->Vardas;
					echo "</br><label>Pavardė: </label> ".$duomenys->Pavarde;
					echo "</br><label>El. pašto adresas:  </label> ".$duomenys->E_pastas;
				}
				if($duomenys->Statusas == "Studentas/ė")
					{
						echo "</br><label>Statusas:  </label> Studentas/ė (".$duomenys->Grupe.")";
					}
				else if($duomenys->Statusas == "Mokinys/ė")
					{
						echo "</br><label>Statusas:  </label> Mokinys/ė (".$duomenys->Klase.")";
					}
				else {echo "</br><label>Statusas:  </label> Kita";}
				
				echo "<br><label>Naudotojo reitingo taskai:  </label> ";
				if(is_null($duomenys->Reitingo_taskai)){echo "nėra";}else{echo $duomenys->Reitingo_taskai;}
				$datetime = new DateTime($duomenys->Registracijos_data);				
				echo "</br><label>Naudotojas nuo </label> ".$datetime->format('Y-m-d');
				}
				if($this->session->userdata['logged_in']['Yra_administratorius'])
				{
					?>
					<br>
					<?php 
					if($this->session->userdata['logged_in']['Naudotojo_id']!=$duomenys->N_ID) 
					{ ?>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rolesModal">Keisti rolę</button>
					<a href="javascript:window.history.go(-1);" class="btn btn-primary btn-dif">Atšaukti</a>
					<?php
					}
				}
				else
				{
				?>				
					<br>
					<a class="btn btn-primary btn-dif" href="javascript:window.history.go(-1);">Atšaukti</a>
				<?php
				}
	 	?></div><?php 
			}
			?>
	  
  </div>
</div>

<!-- Naudotojo rolės keitimo modal-->
<div class="modal fade" id="rolesModal" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Naudotojo rolė</h4>
        </div>
        <div class="modal-body">		
         Dabar naudotojas 
		 <?php 
			if($duomenys->Yra_administratorius==0)
			{echo " nėra administratorius. Keisti į administratoriaus rolę?";}
			else {echo " yra administratorius. Keisti panaikinant administratoriaus rolę?";}			
		?>		
        </div>
		
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" STYLE="margin-bottom: 0;">Atšaukti</button>
		  <a href="<?php echo base_url()."index.php/Registruoti_naudotojai/keisti_role/".$duomenys->N_ID ?>" class="btn btn-primary">Keisti</a>
        </div>
      </div>
      </div>
</div>

	  <!-- Modal Slaptažodžio keitimas-->
<div class="modal fade" id="KeistiSlapt" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Slaptažodžio keitimas</h4>
        </div>
        <div class="modal-body">		
		<form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/keisti_slaptazodi/true"> 
			<label> Naujas slaptažodis:  </label>
				<input type="password" name="slaptazodis" id="slaptazodis" 
						value="<?php echo set_value('slaptazodis'); ?>" 
						style="width: 100%; padding: 1em; border: 1px solid #ccc;
								margin-bottom: 1em; border-radius: 0; outline: none;" minlength="6" size="50" required><br/>
				<?php echo form_error('slaptazodis'); /*validation error*/?>
			<div class="alert alert-danger collapse" id="nesutampa">
			<strong>Klaida!</strong> Nesutampa slaptažodžiai.
			</div>
			<label>Pakartokite naują slaptažodį:  </label>
				<input type="password" name="slaptazodis2" id="slaptazodis2" onchange="arsutampa();" style="width: 100%; padding: 1em; border: 1px solid #ccc;
								margin-bottom: 1em; border-radius: 0; outline: none;" required>
				<?php echo form_error('slaptazodis2'); /*validation error*/?>
			<label> Esamas (senas) slaptažodis:  </label>
			<input type="password" name="senslaptazodis" 
					value="<?php echo set_value('senslaptazodis'); ?>" 
					style="width: 100%; padding: 1em; border: 1px solid #ccc;
							margin-bottom: 1em; border-radius: 0; outline: none;" minlength="6" size="50" required><br/>
			<?php echo form_error('senslaptazodis'); /*validation error*/?>
        </div>
		
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" style="margin-bottom: 0px;">Atšaukti</button>
		  <button type="submit" class="btn btn-primary"><i class="submit"></i>Keisti slaptažodį</button>
        </div>
		</form>
      </div>
    </div>
 </div>
 <script>
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
