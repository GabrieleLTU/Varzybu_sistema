<div class="container contactform center" id="home" style="min-height: calc(100% - 200px);">

	<div class="col-sm-10 col-sm-offset-1 col-xs-12 tableborder"> 
	
	<h4 class="text-title">Naudotojams suteiktos teisės</h4>
	<?php
				if(isset($message))
				{
					 if($message=="istrinta")
					{
							?>
							<div class="alert alert-info alert-dismissible">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								Pasirinkta naudotojo teisė ištrinta.
							 </div>
							<?php
					} 
					else if($message=="sukurta")
					{
							?>
							<div class="alert alert-success alert-dismissible">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								Nauja naudotojo teisė sukurta.
							 </div>
							<?php
					}
				}
			?>
		
		<div class="  wowload fadeInUp animated" style="padding: 15px; margin-bottom: 20px; visibility: visible; animation-name: fadeInUp;">
		
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#kurtiTeiseModal" style="width: auto; float: right; text-transform: none; padding: 6px 51px; margin-top: 0;">Suteikti naują teisę</button>		
		<button type="button" class="btn btn-primary btn-dif glyphicon glyphicon-search" style="width: auto; float:right; padding:6px 10px; margin-top:-1; margin-right:5px;" data-toggle="collapse" data-target="#paieska"></button>
			<div id="paieska" class="collapse" style="width:100%;">
				<input class="form-control" id="ieskoti_naudotojo" type="text" placeholder="Ieškoti naudotojo vardu.." style="width:100%; float: left;">	
			</div>	
		<div style="width:100%; overflow: auto; max-height: calc(60%); ">	
		<table class="table table-hover" >
		
			<thead>
				<tr>
					<th>Kam suteikta</th>
					<th>Teisė redaguoti (ir trinti):</th>
					<th>Leidžiama kurti naują</th>
					<th>Kas suteikė</th>
					<th style="width: 15%; text-align: center;">Teisė suteikta</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="teises">
			<?php
				foreach ($visos_t as $viena):?>
					<tr>
						<td><?php echo $viena->Kam_suteike_vardas;?></td>
						<td>
							<?php
								if(is_null($viena->Objekto_id))
								{
									echo "Tik naudotojo sukurtus/as ".$viena->Koks_objektas;
								}
								else if($viena->Objekto_id==0)
								{
									echo "Visus/as ".$viena->Koks_objektas;
								}
								else
								{
									echo "Tik ".$viena->Koks_objektas." \"".$viena->Objekto_pavadinimas."\"";
								}
							?>
						</td>
						<td style="text-align: center;"><?php if($viena->Leisti_kurti==1){echo "taip";}else{echo "ne";}?></td>
						<td><?php echo $viena->Kas_suteike_vardas;?></td>
						<td><?php echo $viena->Galioja_nuo;?></td>
						<td><a href="http://localhost:8080/Varzybos/index.php/Registruoti_naudotojai/teisiu_naikinimas/<?PHP echo $viena->T_ID; ?>">X</a></td>
					</tr>
					<?php endforeach;  
			?>
			</tbody>	
		</table>
		</div>
		</div>
	</div>		  
</div>
<!-- Naudotojo paieska-->		
	  <script>		
		$(document).ready(function(){
		$("#ieskoti_naudotojo").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#teises tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		});
		</script>
		

<!-- Naujos teises kurimo modal-->
<div class="modal fade" id="kurtiTeiseModal" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Teisės suteikimas</h4>
        </div>
        <div class="modal-body">		
         <form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/teisiu_kurimas" 
		enctype="multipart/form-data">  
		<label >Kam (naudotojo vardas):</label>
			<input type="text" min=1 max=25 id="kam_vardas" name="kam_vardas" class="standart" value="<?php echo set_value('kam_vardas'); ?>" required>
		<?php echo form_error('kam_vardas'); /*validation error*/?>
		<br>	
		<label >Teisė redaguoti:</label>
			<select name="objektas" onchange="pasirinktas();" id="objektas" class="standart" >
				<option value="0" >Pasirinkite</option>			
				<option value="u_1">Vieną uždavinį</option>
				<option value="u_v">Visus uždavinius</option>
				<option value="v_1">Vienas varžybas</option>
				<option value="v_v">Visas varžybas</option>
				<option value="u_n">Tik pačio naudotojo sukurtus uždavinius</option>
				<option value="v_n">Tik pačio naudotojo sukurtas varžybas</option>
			</select>
			<br>
			<label id="o_id_lable" style="display: none;">Objekto pavadinimas:  </label>
				<select name="v_id" id="v_id" class="standart"  style="display: none;">
					<option value="0" >Pasirinkite</option>	
					<?php
						foreach($v_sarasas as $row)
						{
							echo "<option value='".$row->id."'>".$row->pavadinimas."</option>";
						}
					?>
				</select>
				<select name="u_id" id="u_id" class="standart" style="display: none;">
					<option value="0" >Pasirinkite</option>	
					<?php
						foreach($u_sarasas as $row)
						{
							echo "<option value='".$row->id."'>".$row->pavadinimas."</option>";
						}
					?>
				</select>		
			<?php echo form_error('u_id'); /*validation error*/?>
		<label id="l_kurti" style="margin-top: 10px; width:30%">Leisti kurti:</label>
			<div style="display: inline;">
				<input type="radio" style="width: 5%; margin-left: 40px;" id="l_k" name="leisti_kurti" value="1" ><label>Taip</label>   
				<input type="radio" style="width: 5%; margin-left: 40px;" id="nl_k" name="leisti_kurti" value="0" checked><label>Ne</label><br>
			</div>
			
        </div>
		
        <div class="modal-footer">
			<button type="submit" class="btn btn-primary" >Suteikti teisę</button>
			<button type="button" class="btn btn-primary btn-dif" data-dismiss="modal">Atšaukti</button>
          
        </div>
      </div></form>
      </div>
</div>		

<script type="text/javascript">
//Modal'e pagal objekto pasirinkima
function pasirinktas() {
	value = document.getElementById("objektas").value;
    if(value=='u_1' || value=='v_1')
		{
			document.getElementById("o_id_lable").style.display = "-webkit-inline-box";
			document.getElementById((value.charAt(0)=='u'? "u_id" : "v_id")).style.display = "unset";
			document.getElementById((value.charAt(0)=='u'? "v_id" : "u_id")).style.display = "none";	
						
			document.getElementById("nl_k").checked = true;
		}
	else
		{
			document.getElementById("o_id_lable").style.display = "none";			
			document.getElementById("v_id").style.display = "none";	
			document.getElementById("u_id").style.display = "none";
			//document.getElementById("l_k").checked = (value=='v_n'||value=='u_n');
			//document.getElementById("nl_k").checked = (value.charAt(2)=='v');
			//document.getElementById("l_k").disabled = (value=='v_n'||value=='u_n');
			//document.getElementById("nl_k").disabled = (value=='v_n'||value=='u_n');			
			//document.getElementById("l_k").checked = (value=='v_n'||value=='u_n');
						
			//document.getElementById("nl_k").checked = (value.charAt(2)=='v');
		}
		
	value.charAt(0)=='u'?
	document.getElementById("l_kurti").innerHTML  = "Leisti kurti naują uždavinį?" : document.getElementById("l_kurti").innerHTML  = "Leisti kurti naujas varžybas?";	
	
	//console.log(value);
};
</script>