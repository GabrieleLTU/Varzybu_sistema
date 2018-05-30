<div class="container contactform center">
<h2 class="text-center  wowload fadeInUp"> ... </h2>

	<?php
		if($ar_admin){
			
			?>
			<a type="button" class="btn btn-primary" style="float: right; width:25%" 
			href="<?php echo base_url();?>index.php/Registruoti_naudotojai/v_kurimas">
			Kurti varžybas</a>
		<?php }?>
	 
      <div class="col-sm-8 col-sm-offset-3 col-xs-12 tableborder">    
		<h4 style="text-align:center; margin-top:25px">Rengiamos varžybos</h4>	  
        <table class="table table-hover">
			<thead>
			<tr>
				<th>Pavadinimas</th>
				<th>Pradžia</th>
				<th>Trukmė</th>
				<th>Registracija</th>
				<th> </th>
			</tr>
			</thead>
			
			<?php
			foreach ($vykstancios as $row)
			{
				echo "<tr style=\"color:green;\">";
				echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_vykdymas/".$row->V_ID."\">".$row->V_pavadinimas."</a></td>";
				echo "<td>".$row->V_pradzia." h</td>";
				echo "<td>".$row->V_trukme." h</td>";
				echo "<td href=\"".base_url()."index.php/Registruoti_naudotojai/v_vykdymas/".$row->V_ID."\">Dalyvauti</td>";
				echo "<td >x".$row->Naud."</td>";
				echo "</tr>";
			}; 
			
			foreach ($busimos as $row2)
			{
				echo "<tr>";
				echo "<td><a>".$row2->V_pavadinimas."</a></td>";
				echo "<td>".$row2->V_pradzia." h</td>";
				echo "<td>".$row2->V_trukme." h</td>";
				echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/registruoti_varzyboms/".$row2->V_ID."\">Registruotis</a></td>";
				echo "<td>x".$row2->Naud."</td>";
				echo "</tr>";
			};
			?>
  
		 </table>
      </div>
	  
	  <div class="col-sm-8 col-sm-offset-3 col-xs-12 tableborder">    
		<h4 style="text-align:center; margin-top:25px">Įvykusios varžybos</h4>	  
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
				echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_vykdymas/".$row->V_ID."\">".$row->V_pavadinimas."</a></td>";
				echo "<td>".$row->V_pradzia." h</td>";
				echo "<td>".$row->V_trukme." h</td>";
				echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_tur_lentele/".$row->V_ID."\">Turnyrinė lentelė</a></td>";
				echo "<td style=\"text-align: center;\">x".$row->Naud."</td>";
				echo "</tr>";
			};
			?>
		
		 </table>
      </div>
			  
			<?php
			
			if($ar_admin){
			
				echo "
				<div class=\"col-sm-8 col-sm-offset-3 col-xs-12 tableborder\">    
				<h4 style=\"text-align:center; margin-top:25px\">Neparuoštos varžybos</h4>	  
				<table class=\"table table-hover\">
				<thead>
				<tr>
					<th>Pavadinimas</th>
					<th>Pradžia</th>
					<th>Trukmė</th>
					<th>   </th>
				</tr>
				</thead>";
			
				foreach ($neparuostos as $row)
				{
					echo "<tr>";
					echo "<td>".$row->V_pavadinimas."</td>";
					echo "<td>".$row->V_pradzia." h</td>";
					echo "<td>".$row->V_trukme." h</td>";
					echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_keitimas/".$row->V_ID."\">Redaguoti</a></td>";
					echo "</tr>";
				};
				
				echo "</table>
      </div> ";
			}			?>
		
		  
</div>