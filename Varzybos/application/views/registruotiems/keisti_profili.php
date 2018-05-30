<div class="container contactform center" id="home" style="min-height: calc(100% - 200px);">

 <form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/keisti_profili" class="row wowload fadeInLeftBig"> 
        <div class="col-sm-6 col-sm-offset-3 col-xs-12 tableborder">   
		<h4 class="text-center  wowload fadeInUp" style="margin-top: inherit;">Profilio keitimas</h4>	
			
			<?php 
				 foreach ($duomenys as $row)
				{ 
					if(isset($b))
			{
				if($b=="error")
				{
					echo "<div class=\"alert alert-danger collapse show\" >
					<strong>Klaida!</strong> Neteisingas slaptažodis.
					</div>";
				}
			}
				?>
					
					<label>Naudotojo vardas: &nbsp </label> <?php echo $row->Naud_vardas; ?>
					
					</br><label>Vardas:  </label><input type="text" name="vardas" value="<?php echo $row->Vardas; ?>">
					<?php echo form_error('vardas'); /*validation error*/?>
					
					<label>Pavardė: </label><input type="text" name="pavarde" value="<?php echo $row->Pavarde; ?>">
					<?php echo form_error('pavarde'); /*validation error*/?>
					
					<label>El. pašto adresas:  </label><input type="email" name="el_pastas" value="<?php echo $row->E_pastas; ?>">
					<?php echo form_error('el_pastas'); /*validation error*/?>
					<label>Statusas:  </label>
					<select name="statusas" onchange="pasirinktas();" id="statusas" style="padding:5px" >
						<option value="0" >Pasirinkite</option>
						<option value="Mokinys/ė" <?php if($row->Statusas == "Mokinys/ė"){echo " selected";}?>>Mokinys/ė</option>
						<option value="Studentas/ė" <?php if($row->Statusas == "Studentas/ė"){echo " selected";}?>>Studentas/ė</option>
						<option value="Kita" <?php if($row->Statusas == "Kita"){echo " selected";}?>>Kita</option>
					</select>
					<?php 
						if($row->Statusas == "Mokinys/ė")
							{?>
								<label id="kas" style="visibility: visible; padding-left:5px;">Klasė:</label> 
								<input type="number" min=1 max=12 id="mok" name="mok" value="<?php echo $row->Klase; ?>" style="visibility: vissible; padding: 6px; width: 55px;">
								<input type="text" id="stud" name="stud"  style="visibility: hidden; width: 30%; padding: 5px;">
					<?php	} 
						else {
								if($row->Statusas == "Studentas/ė")
								{	?>
								<label id="kas" style="visibility: visible; padding-left:5px;">Grupė:</label> 
								<input type="number" min=1 max=12 id="mok" name="mok"  style="visibility: hidden; padding: 6px; width: 55px;">
								<input type="text" id="stud" name="stud" value="<?php echo $row->Grupe; ?>" style="visibility: vissible; width: 30%; padding: 5px;">
					<?php
								}
								else
								{?>
									<label id="kas" style="visibility: hidden; padding-left:5px;"></label>
									<input type="number" min=1 max=12 id="mok" name="mok" style="visibility: hidden; padding: 6px; width: 55px;">
									<input type="text" id="stud" name="stud" style="visibility: hidden; width: 30%; padding: 5px;">
								<?php }								
							}
					?>
					<?php echo form_error('statusas'); /*validation error*/?>
					</br>
					<!--<button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Keisti</button>-->
					<label>Įveskite slaptažodį patvirtinimui:  </label> <input type="password" name="slaptazodis" required size="50" minlength="6">	
					<?php echo form_error('slaptazodis'); /*validation error*/?>
					
					
				<?php }				
			?>		
		
		<button type="submit" class="btn btn-primary"></i>Saugoti</button>
		<a href="javascript:window.history.go(-1);" class="btn btn-primary btn-dif" >Atšaukti</a>
      </div>
	  </form>
</div>  

<script type="text/javascript">
function pasirinktas() {
	value = document.getElementById("statusas").value;
    if(value=="Mokinys/ė")
		{
			document.getElementById("stud").style.visibility = "hidden";
			document.getElementById("mok").style.visibility = "visible";
			document.getElementById("kas").style.visibility = "visible";
			document.getElementById("kas").innerHTML = " Klasė: ";
		}
	else if(value=="Studentas/ė")
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
}
</script>