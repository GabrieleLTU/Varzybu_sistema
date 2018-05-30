<div class="container contactform center" id="home" style="min-height: calc(100% - 270px); margin-top: 40px;">
		<!-- Kontaktai pateikiami tik administratoriui -->
		<?php if($this->session->userdata['logged_in']['Yra_administratorius']){?>
			<div class="col-sm-8 col-sm-offset-2 col-xs-12 tableborder"> 
			<h4 class="text-title">Žinutės</h4><br>
			
			<div class="tab-content tableborder" style="width: 30%;float: left; padding: 10px; height: 55%; max-height: 55%;
    overflow: auto;">
				
				<table class="table table-hover">
				<thead>
				<tr>
					<th style="text-align: center;">Kontaktai</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="text-align: center;"><a href="<?php echo base_url();?>index.php/Registruoti_naudotojai/zinutes" >Nauja žinutė</a></td>
				<tr>
				<?php
				foreach ($kontaktai as $naud)
				{
					echo "<tr>";
					echo "<td>
						<a href=\"".base_url()."index.php/Registruoti_naudotojai/zinutes/".$naud->N_ID."\">".$naud->Naud_vardas;
						if($naud->neperskaityta>0){echo "  <span class=\"badge\">".$naud->neperskaityta."</span>";}
					echo "</a></td>";
					echo "</tr>";
				}; ?>
				</tbody>
				</table>
			</div>	
			<div style="width: 70%; float: left; padding: 10px;">			
				<?php
				if(is_null($this->uri->segment('3')))
				{ ?>
					<h5 class="text-title" style=" padding: 0px; font-size: large;">Nauja žinutė</h5><br>
					<form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/nauja_zinute">
					
					<label style="margin-left: 40px;">Kam (naudotojo vardas):</label>
					<input  name="n_vard" style="width:49%; margin-left: 40px; padding: 6px;" value="<?php echo set_value('n_vard'); ?>">
					
					<!-- Naujos zinutes laukas -->
						<textarea name="zinute" placeholder="Žinutė... " style="width:92%; max-width:92%; min-width:92%; margin-left: 40px;"></textarea>
						<?php echo form_error('zinute'); /*validation error*/?>
						<button type="submit" class="btn btn-primary btn-small" style="margin-bottom:10px;">Siųsti žinutę</button>
					</form>
				<?php }
				else {?>
				<h5 class="text-title" style="text-align: center; padding: 0px; font-size: large;"> Susirašinėjate su: <?php echo $su_kuo; ?></h5>
				<div class="tab-content tableborder" style="width: 95%;float: right; padding: 10px; max-height: 40%;  overflow: auto;  margin-top:15px;">
				<?php //if(isset($susirasinejimas))
				if(isset($susirasinejimas))
				{
					foreach($susirasinejimas as $pranesimas)
					{
						if($pranesimas->Gavejo_id == $this->session->userdata['logged_in']['Naudotojo_id'] || ($this->session->userdata['logged_in']['Yra_administratorius']? $pranesimas->Gavejo_id == 0 : false))
						{
							?>
							<div style="margin-bottom: 10px;float: left; background: #8cb5de8a;padding: inherit; border-radius: 1.3em; border-bottom-left-radius: unset; width:70%;">
								<?php 
								echo "".$su_kuo.":<br>";
								echo $pranesimas->Zinute;?>
							<p style="font-size: smaller; text-align: right;margin-bottom: 0px;"><?php echo $pranesimas->Kada; ?></p>
							</div>
							<?php
						}
						else
						{
							?>
							<div style="margin-bottom: 10px;float: right; background: #f8f8f8;padding: inherit; border-radius: 1.3em; border-bottom-right-radius: unset; width:70%; margin-bottom: 10px;">
								Jūs:<br><?php echo $pranesimas->Zinute;?>
								<p style="font-size: smaller; text-align: right;margin-bottom: 0px;"><?php echo $pranesimas->Kada; ?></p>
							</div>
							
							<?php 
						}
					}
				}
			?>	
				<!-- Naujos zinutes laukas -->
				</div>
				<div style="float: right; width:95%">
				<form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/siusti_zinute<?php if(!is_null($this->uri->segment('3'))){echo "/".$this->uri->segment('3')."";}?>">
					<textarea name="zinute" placeholder="Žinutė... " style="width:100%; max-width:100%; min-width:100%"></textarea>
					<?php echo form_error('zinute'); /*validation error*/?>
					<button type="submit" class="btn btn-primary btn-small" style="margin-bottom:10px;">Siųsti žinutę</button>
				</form>
				
				</div>
				<?php
					}			
				
				echo "</div></div>";
		}
		else 
		{
			?>
			<div class="col-sm-8 col-sm-offset-2 col-xs-12 tableborder"> 
				<h4 class="text-title">Žinutės administratoriui</h4><br>
				<div class="tab-content tableborder" style="width: 100%;float: right; padding: 10px; max-height: 40%;  overflow-y: scroll; overscroll-behavior: contain contain;">
			
			<?php
			
			//if(isset($susirasinejimas))
			if(!is_null($susirasinejimas))
			{
				foreach($susirasinejimas as $pranesimas)
				{
					if($pranesimas->Gavejo_id == $this->session->userdata['logged_in']['Naudotojo_id'] || ($this->session->userdata['logged_in']['Yra_administratorius']? $pranesimas->Gavejo_id == 0 : false))
					{
						?>
						<div style="float: left; background: #8cb5de8a;padding: inherit; border-radius: 1.3em; border-bottom-left-radius: unset; width:70%; margin-bottom: 10px;">
							<?php 
							echo "".$su_kuo.":<br>";
							echo $pranesimas->Zinute;?>
							<p style="font-size: smaller; text-align: right;margin-bottom: 0px;"><?php echo $pranesimas->Kada; ?></p>
						</div><br>
						<?php
					}
					else
					{
						?>
						<div style="float: right; background: #f8f8f8;padding: inherit; border-radius: 1.3em; border-bottom-right-radius: unset; width:70%; margin-bottom: 10px;">
							Jūs:<br><?php echo $pranesimas->Zinute;?>
							<p style="font-size: smaller; text-align: right;margin-bottom: 0px;"><?php echo $pranesimas->Kada; ?></p>
						</div>
						<?php
					}
				}echo "</div>";
			}
		?>	
			<!-- Naujos zinutes laukas -->
			<div style="float: right; width:100%">
			<form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/siusti_zinute<?php if(!is_null($this->uri->segment('3'))){echo "/".$this->uri->segment('3')."";}?>">
				<textarea name="zinute" placeholder="Žinutė... " maxlength="300" style="width:100%; max-width:100%; min-width:100%"></textarea>
				
				<!--<p style="float:right;" id="likoSimboliu">300</p><p style="float:right; margin-left:60%;">Liko simbolių: </p>-->
				<?php echo form_error('zinute'); /*validation error*/?>
				<button type="submit" class="btn btn-primary btn-small" style="margin-bottom:10px;">Siųsti žinutę</button>
			</form>
			</div>
		<?php } ?>
	</div>
	
</div>
<!--
<script>
//oninput="LikoSimboliu(this);"
function LikoSimboliu(obj) {
	value = $('#likoSimboliu').text();
	if(value>0) value -=1;	
	console.log(value);
	$('#likoSimboliu').text(value);
	document.click();
}
</script>
-->