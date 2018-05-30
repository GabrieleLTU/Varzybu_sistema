<!--Neregistruotiems-->

<div class="container contactform center" id="home" style="min-height: calc(100% - 270px); margin-top: 40px;">
     
      <div class="col-sm-8 col-sm-offset-2 col-xs-12 tableborder">    
		<h4 style="text-align:center; margin-top:25px">Uždaviniai</h4>	  
        <table class="table table-hover">
			<thead>
			<tr>
				<th>Nr.</th>
				<th style="width:70%">Pavadinimas</th>
				<th>Išsprendė naudotojų</th>
			<?php
			$i = 1;
			foreach ($uzdaviniai as $row)
			{
				echo "<tr>";
				echo "<td>".$i.".</td>";
				echo "<td><a href=\"".base_url()."index.php/Neregistruoti_naudotojai/uzdavinys/".$row->U_ID."\">".$row->U_pavadinimas."</a></td>";
				echo "<td style=\"text-align: center;\"><i class=\"fa fa-user\"></i> x".$row->Issprende."</td>";
				echo "</tr>";
				$i=$i+1;
			}; ?>
			
		 </table>
      </div>
    <a href="#home" class="gototop "><i class="fa fa-angle-up  fa-3x"></i></a>
</div>