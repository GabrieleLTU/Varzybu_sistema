<div class="container contactform center" id="home" style="min-height: calc(100% - 270px); margin-top: 40px;">
 
	<div class="container col-sm-10 col-sm-offset-1 col-xs-12">
		<div class="tableborder" style="padding:inherit;">    
			<h4 class="fadeInDown animated text-title" style="margin-bottom:18px;">Vykstančios ir būsimos varžybos</h4>		  
			<div style="width:100%; overflow: auto;" class=" FadeInUp animated">
			<table class="table table-hover">
				<thead>
				<tr>
					<th>Pavadinimas</th>
					<th>Pradžia</th>
					<th>Trukmė</th>
					<th colspan="2">Dalyvauti/Registruotis</th>
				</tr>
				</thead>
				
				<?php
				if(sizeof($vykstancios)==0 && sizeof($busimos)==0)
				{
					?>
					<tr>
						<td colspan="5" style="text-align:center; " >Šiuo metu nėra vykdomų ir organizuojamų naujų varžybų.</td>
					</tr>
					<?php 
				}
				else{ 
				foreach ($vykstancios as $row)
				{
					echo "<tr class=\"success\" >";
					echo "<td style='vertical-align: middle; text-align: center;'>".$row->V_pavadinimas."</td>";
					echo "<td style='vertical-align: middle; text-align: center;'>".$row->V_pradzia." h</td>";
					echo "<td style='vertical-align: middle; text-align: center;'>".$row->V_trukme." h</td>";
					echo "<td style='vertical-align: middle; text-align: center;'>Vyksta varžybos<br>
								<a href='".base_url()."index.php/Neregistruoti_naudotojai/v_tur_lentele/".$row->V_ID."'>Turnyrinė lentelė</a>
						  </td>";
					echo "<td style='vertical-align: middle;'><i class=\"fa fa-user\"></i> x".$row->Naud."</td>";
					echo "</tr>";
				}; 
				
				foreach ($busimos as $row2)
				{
					echo "<tr>";
					echo "<td style='vertical-align: middle; text-align: center;'>".$row2->V_pavadinimas."</td>";
					echo "<td style='vertical-align: middle; text-align: center;'>".$row2->V_pradzia." h</td>";					
					echo "<td style='vertical-align: middle; text-align: center;'>".$row2->V_trukme." h</td>";
					echo "<td style='text-align:center; vertical-align: middle;'><a data-toggle=\"modal\" data-target=\"#Pranesimasapiereg\">Vyksta registracija</a></td>";
					if($row2->Naud>0)
						{
							echo "<td style='vertical-align: middle;'><i class=\"fa fa-user\"></i> x".$row2->Naud."</td>";
						}
						else
						{
							echo "<td style='vertical-align: middle;'><i class=\"fa fa-user\"></i> x0</a></td>";
						}
					echo "</tr>";
				};
				 }
				?>
	  
			 </table>
      </div></div>
	  <div class="tableborder" style="padding:inherit;">
		<h4 style="text-align:center; margin-top:25px">Įvykusios varžybos</h4>	
		<div style="width:100%; overflow: auto;">
        <table class="table table-hover">
			<thead>
			<tr>
				<th>Pavadinimas</th>
				<th>Pradžia</th>
				<th>Trukmė</th>
				<th>Rezultatai</th>
				<th>Dalyvavo</th>
			</tr>
			</thead>
			  
			<?php
			
			foreach ($ivykusios as $row)
			{
				echo "<tr>";
				echo "<td><a data-toggle=\"modal\" data-target=\"#Pranesimasapiereg\">".$row->V_pavadinimas."</a></td>";
				echo "<td>".$row->V_pradzia." h</td>";
				echo "<td>".$row->V_trukme." h</td>";
				echo "<td><a href='".base_url()."index.php/Neregistruoti_naudotojai/v_tur_lentele/".$row->V_ID."'>Turnyrinė lentelė</a></td>";
				echo "<td style=\"text-align: center;\"><i class=\"fa fa-user\"></i> x".$row->Naud."</td>";
				echo "</tr>";
			};
			?>
		
		 </table>
	  </div>
	  </div>
	</div>
	  
</div>


<!-- Modal-->
<div class="modal fade" id="Pranesimasapiereg" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Reikalingas prisijungimas</h4>
        </div>
        <div class="modal-body">
		<p>Tai gali padaryti tik prisijungęs naudotojas.</p>
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
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal">Uždaryti</button>
        </div>
      </div>
      </div>
      </div>