<div class="container contactform center" id="home" style="min-height: calc(100% - 270px); margin-top: 40px;">
 <div class="animation fadeInUp">
 
  <?php foreach($duomenys as $row){?>
	<form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/v_keitimas/<?php echo $row->V_ID ?>" class="row"> 
		<div class="col-sm-6 col-sm-offset-3 col-xs-12 tableborder"> 
		
			<h4 class="text-center" style="margin-top: inherit;">Varžybų kūrimas/redagavimas</h4>	  
			<label style="width: 30%;">Pavadinimas:  </label><input type="text" name="pavadinimas" value="<?php echo $row->V_pavadinimas; ?>" required style="padding: 10.2px; width:70%;"><br/>
			<?php echo form_error('pavadinimas'); /*validation error*/?>
			
			<label style="width: 30%;">Pradžios data:  </label><input type="datetime-local" name="pr_data" id="pr_data" style="width: 70%; text-align: center; padding: 0;" value = "<?php echo date("Y-m-d\TH:i:s", strtotime($row->V_pradzia))?>">
			<?php echo form_error('pr_data'); /*validation error*/?>
			
			<label style="width: 30%;">Varžybų trukmė (h:min):  </label>
			<input type="number" name="tr_h" id="tr_h" min="0" max="800" onchange="TikrintiTrukme(this);" style="padding: 10.2px; width: 30%; text-align: center;" value="<?php echo substr($row->V_trukme,0,-6); ?>"> :
			<input type="number" name="tr_min" id="tr_min" min="0" max="59" onchange="TikrintiTrukme(this);" style="padding: 10.2px; width: 20%; text-align: center;" value="<?php echo substr(substr($row->V_trukme,-5),0,-3); ?>">
			<!--<input type="time" value="<?php echo $row->V_trukme; ?>" name="trukme" 
						style="width: 30%; text-align: center; padding: 0; height: 36.6px;">-->
			<?php echo form_error('trukme'); /*validation error*/?>
			<div class="alert alert-danger collapse" id="netinkamaTrukme">
				<strong>Klaida!</strong> Netinkama varžybų trukmė - turi vykti ne trumpiau 30 min. ir ne daugiau 800 valandų.
			</div>
			</br></br>	
				
			<label>Uždaviniai:</label></br>
				
				<table class="table table-hover">
				<thead>
				<tr>
					<th style="width: 10%;">Nr.</th>
					<th style="width:70%">Pavadinimas</th>
					<th style="width:20%; text-align: center;">Šalinti</th>
				</tr>
				</thead>
				<?php
					$j=1;
					foreach($uzdaviniai as $uzdavinys)
					{?>					
					<tr >
						<td><?php echo $j; ?>.</td>
						<td><?php echo $uzdavinys->U_pavadinimas; ?></td>
						<td style="text-align: center; color: darkred;"><a href="<?php echo base_url();?>index.php/Registruoti_naudotojai/u_nebepriskyrimas_varzyboms<?php echo "/".$row->V_ID."/".$uzdavinys->U_ID; ?>" style="color: #cc2727;">X</a></td>
					</tr>
					<?php
						$j = $j+1;
					}
					?>
	  
			 </table>
			 
			<button type="button" class="btn btn-primary btn-dif" data-toggle="modal" data-vid=<?php echo $row->V_ID; ?> data-target="#myModal" style="width: 50%; padding: 5 15; float: initial; margin-left: 25%;">Pridėti uždavinį</button>
		  </br>
			<label style="width: 30%;">Paruoštos:    </label>
				<div style="display: inline;">
					<input type="radio" style="width: 5%; margin-left: 40px;" name="ar_paruostas" value="1" onchange="arParuostos(this);" <?php if($row->V_paruostos == 1) {echo 'checked';}?>><label>Taip</label>   
					<input type="radio" style="width: 5%; margin-left: 40px;"name="ar_paruostas" value="0" <?php if($row->V_paruostos == 0) {echo 'checked';}?>><label>Ne</label><br>
				</div>	
			<?php echo form_error('ar_paruostas'); /*validation error*/?>
			
			<button type="submit" class="btn btn-primary" style="width: 100%;">Kurti/Saugoti</button>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#arTrinti">Naikinti varžybas</button>
			<a href="javascript:window.history.go(-1);" class="btn btn-primary btn-dif" >Atšaukti</a>
		</div> 
	</form>
 </div>
	<?php }; ?>
  
</div>



<!-- Uždavinių pasirinkimo modal-->
<div class="modal fade" id="myModal" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Uždavinio/ų pasirinkimas</h4>
        </div>
        <div class="modal-body">
		
		<input class="form-control" id="myInput" type="text" placeholder="Ieškoti uždavinio..">
		
          <table class="table table-hover">
			<thead>
			<tr>
				<th>Nr.</th>
				<th>Pavadinimas</th>
				<th>   </th>
			</tr>
			</thead>
			<tbody id="myTable">
			<?php
			$i = 1;
			foreach ($pasirinkimui as $row)
			{
				echo "<tr>";
				echo "<td>".$i.".</td>";
				echo "<td>".$row->U_pavadinimas."</td>";
				echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/u_priskyrimas_varzyboms/".$this->uri->segment('3')."/".$row->U_ID."\">Pasirinkti</a></td>";
				echo "</tr>"; 
				$i=$i+1;
			}; ?>
			</tbody>
		 </table>
        </div>
		
		<script>		
		$(document).ready(function(){
		$("#myInput").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#myTable tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		});
		</script>
		
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" style="float:right;">Atšaukti</button>
        </div>
      </div>
      </div>
      </div>
	  
	  
	  <!-- Modal Patvirtinti varžybų trynimui-->
<div class="modal fade" id="arTrinti" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Varžybų trynimo patvirtinimas</h4>
        </div>
        <div class="modal-body">
		
		<p>Ar tikrai norite šias varžybas ištrinti visiems laikams ?</p>
		
        </div>
		
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" style="margin-bottom: 0px;">Atšaukti</button>
          <a href="<?php echo base_url()."index.php/Registruoti_naudotojai/v_trinimas/".$this->uri->segment('3'); ?>" class="btn btn-primary" style="width:49%">Ištrinti</a>
		  
        </div>
      </div>
      </div>
 </div>
 
 <script>
 function arParuostos(obj) {
	if($(obj).is(":checked")){
		
	trukme1 = document.getElementById("tr_h").value;
	trukme2 = document.getElementById("tr_min").value;
	pradziosData = document.getElementById("pr_data").value;
	console.log(pradziosData);
	if(trukme1>0 || trukme2>29)
	{
		document.getElementById("tr_h").style.borderColor="#ccc";
		document.getElementById("tr_min").style.borderColor="#ccc";
		document.getElementById("netinkamaTrukme").classList.remove("show");
		
	}
	else 
	{
		document.getElementById("tr_h").style.borderColor="red";
		document.getElementById("tr_min").style.borderColor="red";
		document.getElementById("netinkamaTrukme").classList.add("show");
		
	}
	//console.log('true');
 }} 
 //PRANESIMO RODYMAS DĖL NETINKAMOS TRUKMES
 function TikrintiTrukme(obj) {
		
	trukme1 = document.getElementById("tr_h").value;
	trukme2 = document.getElementById("tr_min").value;
	
	if(trukme1>0 || trukme2>29)
	{
		document.getElementById("tr_h").style.borderColor="#ccc";
		document.getElementById("tr_min").style.borderColor="#ccc";
		document.getElementById("netinkamaTrukme").classList.remove("show");
		
	}
	else 
	{
		document.getElementById("tr_h").style.borderColor="red";
		document.getElementById("tr_min").style.borderColor="red";
		document.getElementById("netinkamaTrukme").classList.add("show");
		
	}
	//console.log('true');
 }
 </script>