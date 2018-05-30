<div id="home" class="container contactform center" style="min-height: calc(100% - 270px); margin-top:40px;">

	<?php foreach($varzybos as $vienos_v){?>
	<form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/registruoti_varzyboms/<?php echo $vienos_v->V_ID; ?>" class="row wowload fadeInLeftBig"> 
		<div class="col-sm-6 col-sm-offset-3 col-xs-12 tableborder">   
			<h4 class="text-center  wowload fadeInUp" style="margin-top: inherit;" >Registracija varžyboms  „<?php echo $vienos_v->V_pavadinimas; ?>“</h4>
			</br>
			
			
			
			<label style="margin-bottom: 15px;">Nurodykite, kaip dalyvausite:</label>
			</br>		
			<select name="statusas" id="statusas" style="width: 100%;
    padding: 1em; border: 1px solid #ccc; margin-bottom: 1em; border-radius: 0; outline: none;
}" >
				<optgroup label="Dalyvauti kaip naudotojas(-a):">
				<option value="Naudotojas"><?php echo $n_vardas; ?></option>
				</optgroup>
				<?php if(isset($n_grupes))
						{?>
					<optgroup label="Dalyvauti kaip komanda:"><?php
							foreach($n_grupes as $grupe)
							{
								echo "<option value=\"".$grupe->G_ID."\">".$grupe->G_pavadinimas."</option>";
							}
							echo "</optgroup>";
						}?>
			</select>
			<?php echo form_error('statusas'); /*validation error*/?>
			<br>
			<div id="paaiskinimas">
			Varžybose galite dalyvauti:<br>
			<ul>
				<li>kaip naudotojas (pasirinkite savo naudotojo vardą);
				<li>kaip komanda (pasirinkite komandos pavadinimą).
			</ul>
			</div>
			<br>
			<button type="submit" class="btn btn-primary">Registruotis</button>
			<a href="javascript:window.history.go(-1);" class="btn btn-primary btn-dif" >Atšaukti</a>
		</div>
	</form> 
	<?php } ?>
</div>  
