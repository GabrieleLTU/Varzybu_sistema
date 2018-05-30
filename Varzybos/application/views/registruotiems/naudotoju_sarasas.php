<div class="container contactform center"  id="home" style="min-height: calc(100% - 200px);">

	<div class="col-sm-10 col-sm-offset-1 col-xs-12 tableborder"> 
	<?php
		if(isset($V_id)){?><h4  class="fadeInDown animated text-title">Varžybų dalyvių sąrašas</h4>
						   <h6 style="text-align:center;">Naudotojai pateikiami pagal reitingą mažėjimo tvarka.</h6><?php }
		else if(isset($pavadinimas)){?><h4  class="fadeInDown animated text-title"><?php echo $pavadinimas;?></h4>
								  <h6 style="text-align:center;">Naudotojai pateikiami pagal datą, kada išsprendė uždavinį. Pirmas naudotojas - paskutinis išsprendęs uždavinį.</h6><?php }
		else{ ?><h4  class="fadeInDown animated text-title">Naudotojų sąrašas pagal reitingą</h4><?php }
	?>
		
		<div class="fadeInUp animated  table-morepm">
		
		<?php 
			if($this->session->userdata['logged_in']['Yra_administratorius'])
			{
				?>
				<a type="button" class="btn btn-primary" style="width: auto; float: right; text-transform: none; padding: 6px 51px; margin-top: 0;" href="http://localhost:8080/Varzybos/index.php/Registruoti_naudotojai/visu_grupiu_sarasas">
				Komandų sąrašas</a>
				<?php
			}?>
		
				
				
		<button type="button" class="btn btn-primary btn-dif glyphicon glyphicon-search" style="width: auto; float:right; padding:6px 10px; margin-top:-1; margin-right:5px;" data-toggle="collapse" data-target="#paieska"></button>
			<div id="paieska" class="collapse" style="width:100%;">
				<input class="form-control" id="ieskoti_naudotojo" type="text" placeholder="Ieškoti naudotojo vardu.." style="width:100%; float: left;">	
			</div>	
		<div style="width:100%; overflow: auto; max-height: calc(60%); ">
		<table class="table table-hover">
		
			<thead>
				<tr>
					<th style="width: 10%;">  </th>
					<th>Naudotojo vardas</th>
					<th style="width: 20%; text-align: center;">Reitingo taškai</th>
				</tr>
			</thead>
			
			<tbody id="naudotojai">
			<?php
			$i = 1;
				foreach ($naudotojai as $naudotojas)
				{
					if(!isset($naudotojas->G_id))
					{ ?>
						<tr>
							<td style="text-align: center;"><?php echo $i;?>.</td>
							<td><a href="<?php echo base_url().'index.php/Registruoti_naudotojai/kito_naudotojo_profilis/'.$naudotojas->N_ID?>"><?php echo $naudotojas->Naud_vardas;?></a></td>
							<td style="text-align: center;"><?php echo round($naudotojas->Reitingo_taskai,2)==0?" ":round($naudotojas->Reitingo_taskai,2);?></td>
						</tr>
						<?php 
					}
					else
					{ ?>
						<tr>
							<td style="text-align: center;"><?php echo $i;?></td>
								<?php
									if(IS_NULL($naudotojas->G_id))
									{
										echo '<td><a href="'.base_url().'index.php/Registruoti_naudotojai/kito_naudotojo_profilis/'.$naudotojas->N_ID.'">'.$naudotojas->Naud_vardas.'</a></td>';
									}
									ELSE
									{
										echo '<td><a href="'.base_url().'index.php/Registruoti_naudotojai/grupes_profilis/'.$naudotojas->G_id.'">'.$naudotojas->Naud_vardas.'</a></td>';
									}
								?>
							<td style="text-align: center;">-</td>
						</tr>
					<?php 
					}
					$i++;
				}; 
			?>
			</tbody>
			
		</table>
		</div>
		</div>
		<?php 
			if($this->session->userdata['logged_in']['Yra_administratorius'] && isset($V_id))
			{ 
				?>
		<a type="button" class="btn btn-primary btn-dif" style="width: auto; float: right; text-transform: none; padding: 6px 51px; margin-top: 0;" data-toggle="modal" data-target="#Siuntimui" >Saugoti CSV formatu</a>
			<?php }; ?>
	</div>		  
</div>

<!-- Naudotojo paieska-->		
 <script>		
		$(document).ready(function(){
		$("#ieskoti_naudotojo").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#naudotojai tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		});
</script>

<?php 
			if($this->session->userdata['logged_in']['Yra_administratorius'])
			{ ?>
	  <!-- Modal Naudotojų sąrašo parsisiuntimui-->
<div class="modal fade" id="Siuntimui" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Slaptažodžio keitimas</h4>
        </div>
        <div class="modal-body">		
		<form method="post" action="<?php echo base_url(); ?>index.php/Registruoti_naudotojai/siustiCSV<?php echo isset($V_id)? "/".$V_id:""; ?>"> 
			<label> Pasirinkite, ką norite gauti atsiųstame dokumente:  </label><br>
			
			  <input type="checkbox" name="n_vardas" value="Naud_vardas" style="margin: 0px 10px;">Naudotojo vardas/ komandos pavadinimas<br>
			  <input type="checkbox" name="v_p" value="Vardas, Pavarde" style="margin: 0px 10px;">Vardas ir pavardė<br>
			  <input type="checkbox" name="e_pastas" value="E_pastas" style="margin: 0px 10px;">el. pašto adresas<br>
			  <input type="checkbox" name="statusas" value="Statusas" style="margin: 0px 10px;">Statusas (studentas/mokinys/kita)<br>
		
        </div>
		
        <div class="modal-footer">
		  <button type="submit" class="btn btn-primary"><i class="submit"></i>Parsiųsti failą</button>
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal">Uždaryti</button>
		  </div>
		</form>
      </div>
    </div>
 </div>
			<?php } ?>