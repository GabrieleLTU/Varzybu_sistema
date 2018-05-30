<div class="container contactform center" id="home" style="min-height: calc(100% - 200px);">

	<div class="col-sm-10 col-sm-offset-1 col-xs-12 tableborder"> 
	<?php if($yra_admin) { ?>
		<h4 class="fadeInDown animated text-title">Komandų sąrašas</h4><br>
		<div class="fadeInUp animated  table-morepm">
		<button type="button" class="btn btn-primary btn-dif glyphicon glyphicon-search" style="width: auto; float:right; padding:6px 10px; margin-top:-1; margin-right:5px;" data-toggle="collapse" data-target="#paieska"></button>
			<div id="paieska" class="collapse" style="width:100%;">
				<input class="form-control" id="ieskoti_grupes" type="text" placeholder="Ieškoti komandos pavadinimu..." style="width:100%; float: left;">
			</div>
		<div style="width:100%; overflow: auto; max-height: calc(60%);">
		<table class="table table-hover">
		
			<thead>
				<tr>
					<th style="width: 10%;">  </th>
					<th>Komandos pavadinimas</th>
				</tr>
			</thead>
			<tbody id="grupes">
			<?php
			$i = 1;
				foreach ($grupes as $grupe)
				{
					?>
					<tr>
						<td style="text-align: center;"><?php echo $i;?></td>
						<td><a href="<?php echo base_url().'index.php/Registruoti_naudotojai/grupes_profilis/'.$grupe->G_ID;?>"><?php echo $grupe->G_pavadinimas;?></a></td>
						
					</tr>
					<?php
					$i++;
				}; 
			?>
			</tbody>	
		</table>		
		</div></div>
	<?php } 
	else { ?>
	<h4 class="fadeInUp animated text-title">Komandos, kurioms priklausote, sąrašas</h4><br>
		<?php 
			//if(!is_null(form_error('pavadinimas'))){
			//	echo "<br><div class=\"alert alert-danger collapse show\" >".form_error('pavadinimas')."
			//				</div>";}
							echo form_error('pavadinimas');
					/*validation error*/?>
		<div class="fadeInUp animated  table-morepm">
		<button type="button" class="btn btn-primary" style="width: auto; float: right; text-transform: none; padding: 6px 51px; margin-top: 0;" data-toggle="modal" data-target="#rolesModal">Kurti komandą</button>
		<button type="button" class="btn btn-primary btn-dif glyphicon glyphicon-search" style="width: auto; float:right; padding:6px 10px; margin-top:-1; margin-right:5px;" data-toggle="collapse" data-target="#paieska"></button>
			<div id="paieska" class="collapse" style="width:100%;">
				<input class="form-control" id="ieskoti_grupes" type="text" placeholder="Ieškoti komandos pavadinimu..." style="width:100%; float: left;">
			</div>
		<div style="width:100%; overflow: auto; max-height: calc(60%);">
		<table class="table table-hover">		
			<thead>
				<tr>
					<th style="width: 10%;">  </th>
					<th>Komandos pavadinimas</th>
					<th style="width:10%"></th>
				</tr>
			</thead>
			<tbody id="grupes">
			<?php
			$i = 1;
			if(sizeof($grupes)==0)
				{
					?>
					<tr>
						<td colspan="3" style="text-align:center;" >Jūs nepriklausote nei vienai komandai.</td>
					</tr>
					<?php 
				}
				else
				{ 
					foreach ($grupes as $grupe)
					{
						?>
						<tr>
							<td style="text-align: center;"><?php echo $i;?></td>
							<td><a href="<?php echo base_url().'index.php/Registruoti_naudotojai/grupes_profilis/'.$grupe->G_ID;?>"><?php echo $grupe->G_pavadinimas;?></a></td>
							<td>
								<a href="<?php echo base_url().'index.php/Registruoti_naudotojai/trinti_grupe/'.$grupe->G_ID;?>"><i class="fa fa-trash"></i></a>
								
							</td>
						</tr>
						<?php
						$i++;
					}
				}				
			?>
			</tbody>	
		</table>		
		</div></div>
	<?php } ?>
	</div>		  
</div>

<!-- Naudotojo paieska-->		
<script>		
		$(document).ready(function(){
		$("#ieskoti_grupes").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#grupes tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		});
</script>

<!-- Grupės kūrimo modal-->
<div class="modal fade" id="rolesModal" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Naujos komandos kūrimas</h4>
        </div>
        <div class="modal-body">		
			<form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/kurti_grupe"> 
				 
				<label>Komandos pavadinimas:  </label>
				<input type="text" name="pavadinimas" value="<?php echo set_value('pavadinimas'); ?>" required 
				style="width: 100%; padding: 1em; border: 1px solid #ccc; margin-bottom: 1em; border-radius: 0; outline: none;"><br/>
				<?php echo form_error('pavadinimas'); /*validation error*/?>
				
        </div>
		
        <div class="modal-footer">
		 <button type="submit" class="btn btn-primary">Kurti</button>
         <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal">Atšaukti</button>
		  
        </div>
		</form>
      </div>
      </div>
</div>