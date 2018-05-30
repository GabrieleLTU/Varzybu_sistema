<!--Registruotiems-->
<div class="container contactform center" id="home" style="min-height: calc(100% - 200px); margin-top:20px;">

<?php  
 if($rodyti){?>
     <!--Yra admin arba turi teisę keisti/kurti varžybas-->
	 
	 <div class="container col-sm-10 col-sm-offset-1 col-xs-12 FadeInUp animated">
  
	  <ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#varzybos">Vykdomos ir pasibaigusios varžybos</a></li>
		<li><a data-toggle="tab" href="#kuriamos_v">Kuriamos varžybos</a></li>
	  </ul>

	  <div class="tab-content" >
		<div id="varzybos" class="tab-pane active" 
		style="padding:15px; border:1px solid lightgray; border-top:none; margin-bottom: 20px;">		
		<div class="tableborder" style="padding:inherit;">    
			<h4 class="fadeInDown animated text-title" style="margin-bottom:18px;">Vykstančios ir būsimos varžybos</h4><br>	
			<div style="width:100%; overflow: auto;">
			<table class="table table-hover">
				<thead>
				<tr>
					<th>Pavadinimas</th>
					<th>Pradžia</th>
					<th>Trukmė</th>
					<th>Būsena</th>
					<th></th>
				</tr>
				</thead>
				
				<?php	
				if(sizeof($vykstancios)==0 && sizeof($busimos)==0)
				{
					?>
					<tr>
						<td colspan="5" style="text-align:center;" >Šiuo metu nėra vykdomų ar būsimų varžybų.</td>
					</tr>
					<?php 
				}
				else
				{ 				
					foreach ($vykstancios as $row)
					{
						echo "<tr class=\"success\" >";
						echo "<td style='vertical-align: middle; text-align: center;'>
							<a href=\"".base_url()."index.php/Registruoti_naudotojai/v_apzvalga/".$row->V_ID."\">".$row->V_pavadinimas."</a>";
								if($yra_admin){
								 echo "<a href=\"".base_url()."index.php/Registruoti_naudotojai/v_keitimas/".$row->V_ID."\"> 
								<i class=\"fa fa-edit\"></i></a>"; } 
							
						echo "</td>";
						echo "<td style='vertical-align: middle; text-align: center;'>".$row->V_pradzia." h</td>";
						echo "<td style='vertical-align: middle; text-align: center;'>".$row->V_trukme." h</td>";
						echo "<td style='vertical-align: middle; text-align: center;'> Vyksta varžybos<br><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_tur_lentele/".$row->V_ID."\">Turnyrinė lentelė</a></td>";
						if($row->Naud>0)
						{
							echo "<td style='vertical-align: middle;'><a href=\"".base_url()."index.php/Registruoti_naudotojai/varzybu_dalyviu_sarasas/".$row->V_ID."\"><i class=\"fa fa-user\"></i> x".$row->Naud."</a></td>";
						}
						else
						{
							echo "<td style='vertical-align: middle;'><i class=\"fa fa-user\"></i> x0</a></td>";
						}
						echo "</tr>";
					}; 
					
					foreach ($busimos as $row2)
					{
						echo "<tr>";
						echo "<td style=\"text-align: center;\"><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_apzvalga/".$row2->V_ID."\">".$row2->V_pavadinimas." </a>";
						if($yra_admin){
								 echo "<a href=\"".base_url()."index.php/Registruoti_naudotojai/v_keitimas/".$row2->V_ID."\"> 
								<i class=\"fa fa-edit\"></i></a>"; }
						echo "</td>";
						echo "<td style='vertical-align: middle; text-align: center;'>".$row2->V_pradzia." h</td>";
						echo "<td style='vertical-align: middle; text-align: center;'>".$row2->V_trukme." h</td>";
						echo "<td style='vertical-align: middle; text-align: center;'> Vyksta registracija </td>";
						if($row2->Naud>0)
						{
							echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/varzybu_dalyviu_sarasas/".$row2->V_ID."\"><i class=\"fa fa-user\"></i> x".$row2->Naud."</a></td>";
						}
						else
						{
							echo "<td><i class=\"fa fa-user\"></i> x0</a></td>";
						}
						echo "</tr>";
					};
				}
				?>
	  
			 </table>
      </div></div>
	  <div class="tableborder" style="padding:inherit;">
		<h4 class="fadeInDown animated text-title" style="margin-bottom:18px;">Įvykusios varžybos</h4><br>
		<div style="width:100%; overflow: auto;">
        <table class="table table-hover">
			<thead>
			<tr>
				<th style="text-align: center;">Pavadinimas</th>
				<th style="text-align: center;">Pradžia</th>
				<th style="text-align: center;">Trukmė</th>
				<th style="text-align: center;">Rezultatai</th>
				<th style="text-align: center;">Dalyvavo</th>
			</tr>
			</thead>
			  
			<?php
			
			foreach ($ivykusios as $row)
			{
				echo "<tr>";
				echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_apzvalga/".$row->V_ID."\">".$row->V_pavadinimas."</a></td>";
				echo "<td>".$row->V_pradzia." h</td>";
				echo "<td>".$row->V_trukme." h</td>";
				echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_tur_lentele/".$row->V_ID."\">Turnyrinė lentelė</a></td>";
				echo "<td style=\"text-align: center;\"><a href=\"".base_url()."index.php/Registruoti_naudotojai/varzybu_dalyviu_sarasas/".$row->V_ID."\"><i class=\"fa fa-user\"></i> x".$row->Naud."</a></td>";
				echo "</tr>";
			};
			?>
		
		 </table>
	  </div>
	  </div>
		
		</div>
		<!--KURIAMOS varzybos skiltis-->
		<div id="kuriamos_v" class="tab-pane fade" style="padding:15px; border:1px solid lightgray; border-top:none; margin-bottom: 20px;">
			

		<div>  
			
			<h4 class="fadeInDown animated text-title" style="margin-bottom:18px;" >Kuriamos varžybos</h4><br>
			<?php if($kurti_nauja){?>
			<a type="button" class="btn btn-primary" 
				style="width: auto; float: right; text-transform: none; padding: 6px 51px; margin-top: 0;" href="<?php echo base_url();?>index.php/Registruoti_naudotojai/v_kurimas">
			Kurti varžybas</a><?php }?>
			<button type="button" class="btn btn-primary btn-dif glyphicon glyphicon-search" style="width: auto; float:right; padding:6px 10px; margin-top:-1; margin-right:5px;" data-toggle="collapse" data-target="#paieska"></button>
			<div id="paieska" class="collapse" style="width:100%;">
				<input class="form-control" id="ieskoti_kuriamu_v" type="text" placeholder="Ieškoti varžybų..." style="width:100%; float: left;">	
			</div>	
			<br>
			<div style="width:100%; overflow: auto;">
			 <table class="table table-hover">
					<thead>
					<tr>
						<th>Nr.</th>
						<th style="text-align: center;">Pavadinimas</th>
						<th style="text-align: center;">Pradžia</th>
						<th style="text-align: center;">Trukmė</th>
						<th>   </th>
					</tr>
					</thead>
					<tbody id="kuriamos_varzybos">
					<?php $i = 1;
						if(sizeof($neparuostos)==0)
				{
					?>
					<tr>
						<td colspan="5" style="text-align:center;" >Nėra kuriamų varžybų. <?php if($kurti_nauja){ echo " Sukurti naujas galite <a href='".base_url()."index.php/Registruoti_naudotojai/v_kurimas'>čia</a>"; };?></td>
					</tr>
					<?php 
				}
				else{ 
					foreach ($neparuostos as $row)
					{
						echo "<tr>";
						echo "<td>".$i.".</td>";
						echo "<td>".$row->V_pavadinimas."</td>";
						echo "<td>".$row->V_pradzia." h</td>";
						echo "<td>".$row->V_trukme." h</td>";
						echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_keitimas/".$row->V_ID."\">Redaguoti</a></td>";
						echo "</tr>";
						$i=$i+1;
					};
				}
					?>
					</tbody>
			 </table>
			
		</div>
		</div>
		
		</div>
	  </div>
	</div>
	 
     <!-- -->
 <?php }
 //NE ADMIN\\
	else {?>
		
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
					echo "<td style='vertical-align: middle; text-align: center;'><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_vykdymas/".$row->V_ID."\">".$row->V_pavadinimas."</a></td>";
					echo "<td style='vertical-align: middle; text-align: center;'>".$row->V_pradzia." h</td>";
					echo "<td style='vertical-align: middle; text-align: center;'>".$row->V_trukme." h</td>";
					echo "<td style='vertical-align: middle; text-align: center;'><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_vykdymas/".$row->V_ID."\">Dalyvauti</a></td>";
					echo "<td style='vertical-align: middle;'><a href=\"".base_url()."index.php/Registruoti_naudotojai/varzybu_dalyviu_sarasas/".$row->V_ID."\"><i class=\"fa fa-user\"></i> x".$row->Naud."</a></td>";
					echo "</tr>";
				}; 
				
				foreach ($busimos as $row2)
				{
					echo "<tr>";
					echo "<td style='vertical-align: middle; text-align: center;'>".$row2->V_pavadinimas."</td>";
					echo "<td style='vertical-align: middle; text-align: center;'>".$row2->V_pradzia." h</td>";					
					echo "<td style='vertical-align: middle; text-align: center;'>".$row2->V_trukme." h</td>";
					echo "<td style='text-align:center; vertical-align: middle;'><a href=\"".base_url()."index.php/Registruoti_naudotojai/registruoti_varzyboms/".$row2->V_ID."\" >Registruotis/<br>Atšaukti registraciją</a></td>";
					if($row2->Naud>0)
						{
							echo "<td style='vertical-align: middle;'><a href=\"".base_url()."index.php/Registruoti_naudotojai/varzybu_dalyviu_sarasas/".$row2->V_ID."\"><i class=\"fa fa-user\"></i> x".$row2->Naud."</a></td>";
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
      </div>
	  </div>
	  <div class="tableborder" style="padding:inherit;">
		<h4 class="fadeInDown animated text-title" style="margin-bottom:18px;">Įvykusios varžybos</h4>	  
        <div style="width:100%; overflow: auto;" class=" FadeInUp animated">
		<table class="table table-hover">
			<thead>
			<tr>
				<th style="text-align: center;">Pavadinimas</th>
				<th style="text-align: center;">Pradžia</th>
				<th style="text-align: center;">Trukmė</th>
				<th style="text-align: center;">Rezultatai</th>
				<th style="text-align: center;">Dalyvavo</th>
			</tr>
			</thead>
			  
			<?php
			
			foreach ($ivykusios as $row)
			{
				echo "<tr>";
				echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_apzvalga/".$row->V_ID."\">".$row->V_pavadinimas."</a></td>";
				echo "<td>".$row->V_pradzia." h</td>";
				echo "<td>".$row->V_trukme." h</td>";
				echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/v_tur_lentele/".$row->V_ID."\">Turnyrinė lentelė</a></td>";
				echo "<td style=\"text-align: center;\"><a href=\"".base_url()."index.php/Registruoti_naudotojai/varzybu_dalyviu_sarasas/".$row->V_ID."\"><i class=\"fa fa-user\"></i> x".$row->Naud."</a></td>";
				echo "</tr>";
			};
			?>
		
		 </table>
	  </div> </div>
		</div>	
	
	<?php };?>
	  
	  <!-- Paieska kuriamuose uzdaviniuose-->		
	  <script>		
		$(document).ready(function(){
		$("#ieskoti_kuriamu_v").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#kuriamos_varzybos tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		});
		</script>
	    
</div>

	  <!-- Modal Registracijos atšaukimui-->
<div class="modal fade" id="RegAtsaukti" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Registracijos keitimas</h4>
        </div>
        <div class="modal-body">		
		<form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/panaikinti_registracija/<?php echo $this->uri->segment('3');?>"> 
			<p><?php echo $openModal; ?></p>
			
        </div>
		
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" ><i class="submit"></i>Panaikinti registraciją</button>
		  <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" style="margin-bottom: 0px;">Gerai, tinka</button>
		  
        </div>
		</form>
      </div>
    </div>
 </div>
 
 <script>
$(window).on('load',function(){
		<?php if(isset($openModal)){ 
		echo"$('#RegAtsaukti').modal('show');";
		} else echo "$('#RegAtsaukti').hide();"; ?>

    });
</script>