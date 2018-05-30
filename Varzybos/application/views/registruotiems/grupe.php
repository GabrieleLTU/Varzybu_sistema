<div class="container contactform center" id="home" style="min-height: calc(100% - 200px);">

	<?php
		if(!$yra_narys)
		{
			?>
			<div class="col-sm-8 col-sm-offset-2 col-xs-12 tableborder"> 
				<h4 class="text-title">Komanda "<?php echo $gr_pavadinimas?>"</h4><br>
				<div class="  wowload fadeInUp animated" style="padding: 15px; margin-bottom: 20px; visibility: visible; animation-name: fadeInUp;">
				<button type="button" class="btn btn-primary btn-dif glyphicon glyphicon-search" style="width: auto; float:right; padding:6px 10px; margin-top:-1; margin-right:5px;" data-toggle="collapse" data-target="#paieska"></button>
					<div id="paieska" class="collapse" style="width:100%;">
						<input class="form-control" id="ieskoti_nario" type="text" placeholder="Ieškoti komandos nario..." style="width:100%; float: left;">
					</div>
				<div style="width:100%; overflow: auto; max-height: calc(60%); ">	
				<table class="table table-hover">
				
					<thead>
						<tr>
							<th style="width: 10%;">  </th>
							<th>Nario naudotojo vardas</th>
						</tr>
					</thead>
					<tbody id="nariai">
					<?php
					$i = 1;
						foreach ($nariai as $narys)
						{
							?>
							<tr>
								<td style="text-align: center;"><?php echo $i;?></td>
								<td><a href="<?php echo base_url().'index.php/Registruoti_naudotojai/kito_naudotojo_profilis/'.$narys->N_ID;?>"><?php echo $narys->Naud_vardas;?></a></td>
							</tr>
							<?php
							$i++;
						}; 
					?>
					</tbody>	
				</table>	
				</div>	
			<a href="javascript:window.history.go(-1);" class="btn btn-primary btn-dif" style="width: 49%; float:right;" >Atšaukti</a>
					</div>
			</div>
			<?php
		}
		else
		{
	?>
	
	<div class="col-sm-8 col-sm-offset-2 col-xs-12 tableborder"> 
	<h4 class="text-title">Komanda "<?php echo $gr_pavadinimas?>" <a type="button" data-toggle="modal" data-target="#PavKeitimoModal" style="font-size: 14px;"><i class="fa fa-pencil"></i></a></h4>
		<br>
		<?php echo form_error('naudotojo_vardas');	/*validation error*/?>
		<div class="  wowload fadeInUp animated" style="padding: 15px; margin-bottom: 20px; visibility: visible; animation-name: fadeInUp;">
		<button type="button" class="btn btn-primary" style="width: auto; float: right; text-transform: none; padding: 6px 51px; margin-top: 0;" data-toggle="modal" data-target="#addNariModal">Pridėti  narį</button>
		<button type="button" class="btn btn-primary btn-dif glyphicon glyphicon-search" style="width: auto; float:right; padding:6px 10px; margin-top:-1; margin-right:5px;" data-toggle="collapse" data-target="#paieska"></button>
			<div id="paieska" class="collapse" style="width:100%;">
				<input class="form-control" id="ieskoti_nario" type="text" placeholder="Ieškoti komandos nario..." style="width:100%; float: left;">
			</div>
		<div style="width:100%; overflow: auto; max-height: calc(60%); ">	
		<table class="table table-hover">
		
			<thead>
				<tr>
					<th style="width: 10%;">  </th>
					<th>Nario naudotojo vardas</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="nariai">
			<?php
			$i = 1;
				foreach ($nariai as $narys)
				{
					?>
					<tr>
						<td style="text-align: center;"><?php echo $i;?></td>
						<td><a href="<?php echo base_url().'index.php/Registruoti_naudotojai/kito_naudotojo_profilis/'.$narys->N_ID;?>"><?php echo $narys->Naud_vardas;?></a></td>
						<th><a href="<?php echo base_url().'index.php/Registruoti_naudotojai/trinti_nari/'.$this->uri->segment('3').'/'.$narys->N_ID;?>"><i class="fa fa-close"></i></a></th>
					</tr>
					<?php
					$i++;
				}; 
			?>
			</tbody>	
		</table>
		</div>
			<a  class="btn btn-primary btn-dif" style="width: auto; float: right; text-transform: none; padding: 6px 41px; margin-top: 0; margin-bottom: inherit;"  href="<?php echo base_url().'index.php/Registruoti_naudotojai/mano_grupiu_sarasas/';?>">Grįžti į komandų sąrašą</a>
		</div>
	</div>		  
<?php }?>
</div>
<!-- Naudotojo paieska-->		
<script>		
		$(document).ready(function(){
		$("#ieskoti_nario").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#nariai tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		});
</script>
<!-- Nario pridėjimo modal-->
<div class="modal fade" id="addNariModal" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Naujo komandos nario pridėjimas</h4>
        </div>
        <div class="modal-body">		
			<form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/prideti_nari/<?php echo $this->uri->segment('3');?>"> 
				 
				<label> Naudotojo vardas:  </label>
				<input type="text" name="naudotojo_vardas" class="standart" value="<?php echo set_value('naudotojo_vardas'); ?>" required><br/>
				<?php echo form_error('naudotojo_vardas'); /*validation error*/?>
				
        </div>
		
        <div class="modal-footer">
		  <button type="submit" class="btn btn-primary" ><i class="submit"></i>Pridėti</button>
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal">Atšaukti</button>
        </div>
		</form>
      </div>
      </div>
</div>

<!-- Grupės pavadinimo keitimo modal-->
<div class="modal fade" id="PavKeitimoModal" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Komandos "<?php echo $gr_pavadinimas;?>" pavadinimo keitimas</h4>
        </div>
        <div class="modal-body">		
			<form method="post" action="<?php echo base_url()."index.php/Registruoti_naudotojai/keisti_grupes_pavadinima/".$this->uri->segment('3');?>"> 
				 
				<label>Naujas komandos pavadinimas:  </label>
				<input type="text" name="naujas_pavadinimas" class="standart" value="<?php echo set_value('naujas_pavadinimas'); ?>" required><br/>
				<input type="hidden" name="gr_id" id="gr_id" class="standart"/>
				<?php echo form_error('naujas_pavadinimas'); /*validation error*/?>
				
        </div>
		
        <div class="modal-footer">
			<button type="submit" class="btn btn-primary">Keisti pavadinimą</button>
			<button type="button" class="btn btn-primary btn-dif" data-dismiss="modal">Atšaukti</button>  
        </div>
		</form>
      </div>
      </div>
</div>

<div class="modal hide" id="addBookDialog">
 <div class="modal-header">
    <button class="close" data-dismiss="modal">×</button>
    <h3>Modal header</h3>
  </div>
    <div class="modal-body">
        <p>some content</p>
        <input type="text" name="bookId" id="bookId" value=""/>
    </div>
</div>

