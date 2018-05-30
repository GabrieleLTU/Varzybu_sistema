<div class="container contactform center" id="home" style="min-height: calc(100% - 270px); margin-top: 40px;">

	<div class="col-sm-10 col-sm-offset-1 col-xs-12 tableborder"> 
			
		
		<?php foreach($varzybos as $vienos_v){?>
		
			<h4 class="text-title wowload fadeInUp animated" 
				>„<?php echo $vienos_v->V_pavadinimas.'“'; 
				if($rodyti && ($liko_laiko>"00:00:00"))
				{
					echo "<a href=\"".base_url()."index.php/Registruoti_naudotojai/v_keitimas/".$vienos_v->V_ID."\" style=\"font-size: 14px;\"> 
								<i class=\"fa fa-edit\" style=\"vertical-align: baseline;\"></i></a>";
			 	}?>
			</h4>
			<h5 class="text-center  wowload animated" style="display:<?php if($liko_laiko>"00:00:00"){echo 'true';}else{echo 'none';}?>">Iki varžybų pabaigos: <?php  echo $liko_laiko;?></h5>						
						
			<br>
			
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#uzdaviniai">Varžybų uždaviniai</a></li>
				<li><a data-toggle="tab" href="#t_lentele">Turnyrinė lentelė</a></li>
			</ul>
		
			<div class="tab-content" >
			  <div id="uzdaviniai" class="tab-pane fade in active" style="padding:15px; border:1px solid lightgray; border-top:none; margin-bottom: 20px;overflow: auto;
    max-height: calc(70%);">
					<h5 class="text-center  wowload animated" style="font-size: larger;">Varžybų uždaviniai:</h5> 
					<table class="table table-hover table-responsive">
						<tr style="background: #f8f8f8;">
							<th style="width: 5%;">Nr.</th>
							<th>Pavadinimas</th>
						</tr>
						<tbody>
						<?php
							$i=1;
						 foreach($uzdaviniai as $uzdavinys){ ?>
							<tr>
								<td><?php echo $i;?></td>
								<td><a href="<?php echo base_url();?>index.php/Registruoti_naudotojai/varzybu_uzdavinys/<?php echo $this->uri->segment('3').'/'.$uzdavinys->U_ID;?>"><?php echo $uzdavinys->U_pavadinimas;?></a></td>
							</tr>
						 <?php $i=$i+1; }?>
						</tbody>
					</table>			
				
			  </div>
			  <div id="t_lentele" class="tab-pane fade" style="padding:15px; border:1px solid lightgray; border-top:none; margin-bottom: 20px;">
					<h5 class="text-center  wowload animated" style="font-size: larger;">Turnyrinė lentelė</h5>
					<?php if(isset($paskutinis_sprendimas)) { 
					if(!is_null($paskutinis_sprendimas)){ ?>
		<table class="table table-bordered table-responsive" style=" width: 25%; float: right;">
			<tbody>
			<tr><th style="width: fit-content;padding-top: 1px;padding-bottom: 1px;font-size: small;">Paskutinis sprendimas:</th></tr>
			<tr>
				<td style="line-height: inherit; text-align: center; padding-top: 3px; padding-bottom: 3px; font-size: small;" 
					class="<?php if($paskutinis_sprendimas->Issprestas==1){echo "Success"; } 
								 else{echo "Danger"; }?>">
					<?php 
					/*
					echo array_search($paskutinis_sprendimas->U_ID, array_column($uzdaviniai, 'U_ID'));
					$a=array("red","green","blue");
					echo array_search("green",$a);*/
					echo $N_vardas." -> ".$U_nr;
					?> užd.
				</td>
			</tr>
			</tbody>
		</table>
		<br>
		<?php } }; ?>	
					<div style="width:100%; overflow: auto;  max-height: calc(70%);">
					<table class="table table-bordered table-responsive">
						<tr style="background: #f8f8f8;">
							<th style="width: 4%; vertical-align: middle;" rowspan="2">Vieta</th>
							<th style="width: 20%; vertical-align: middle;" rowspan="2">Dalyvio vardas</th>
							<th style="text-align: center;" colspan="<?php echo sizeof($uzdaviniai); ?>">Varžybų uždaviniai 
								<i class="fa fa-question-circle" data-toggle="modal" data-target="#uzdavRezSkaicPaaiskinimas"></i>
							</th>
							<th style="width: 10%; vertical-align: middle; text-align: center;" rowspan="2">Išspręstų uždavinių skaičius</th>
							<th style="width: 10%; vertical-align: middle;     text-align: center;" rowspan="2">Baudos taškai</th>
						</tr>
						<tr style="background: #f8f8f8;">							
							<?php $j=1; foreach($uzdaviniai as $u) {echo "<th style=\"text-align: center;\"><a href=\"".base_url()."index.php/Registruoti_naudotojai/varzybu_uzdavinys/".$this->uri->segment('3').'/'.$u->U_ID."\">".$j."</a></th>"; $j++;}?>
						</tr>
						<tbody>
						<?php
						$tisprende = array_fill(0, sizeof($uzdaviniai), 0);
						$sumuotaeilute = array_fill(0, sizeof($uzdaviniai), 0);
							$i=1;
						 foreach($ht_lentele as $dalyvis){ ?>
							<tr>
								<td><?php echo $i;?></td>
								<td>
									<?php if(IS_NULL($dalyvis->G_id))
											{
											echo "<a href=".base_url().'index.php/Registruoti_naudotojai/kito_naudotojo_profilis/'.$dalyvis->N_ID." > ".$dalyvis->Naud_vardas."<a/>";}
											else{echo "<a href=".base_url().'index.php/Registruoti_naudotojai/grupes_profilis/'.$dalyvis->G_id." > ".$dalyvis->G_pavadinimas."<a/>";}?>
								</td>
								<?php $usk=0;
								foreach($uzdaviniai as $u) 
									{ 
										$m = "_".$u->U_ID;
										if(($sprendimai_lentelei[$i-1]->$m)==1)
										{ 
											echo "<td style=\"color:#1e9d1e; text-align: center;\">+";
											if ($dalyvis->$m==1) {echo " </td>";}
											else { echo ($dalyvis->$m-1)."</td>";}
											$tisprende[$usk]++;
										}
										else if($dalyvis->$m>0)
										{ 
											echo "<td style=\"color:red; text-align: center;\">-";
											echo $dalyvis->$m."</td>";
										}
										else { echo "<td style=\"text-align: center;\">";
												echo $dalyvis->$m."</td>";}
										
										$sumuotaeilute[$usk]+=$dalyvis->$m;
										$usk++;
										//echo "<script>console.log( 'Debug Objects: " . $sumuotaeilute[$i-1]. "' );</script>";									
									}?>
								<td style="text-align: center;"><?php echo $dalyvis->issprende;?></td>
								<td style="text-align: center;"><?php echo $dalyvis->taskai;?></td>
							</tr>
						 <?php $i=$i+1; }?>
						 <tr><td colspan="<?php echo 4+sizeof($uzdaviniai);?>" style="background: #f8f8f8;"></td></tr>
						 <tr>
							<th colspan="2" style="text-align:right; width:27%; font-size: small; vertical-align: bottom;
						padding-bottom: 0; padding-top: 8px;">Teisingai išsprendė: </th>
							<?php 
								foreach($tisprende as $uzdavinys) 
								  { ?>
								  <td style="text-align:center; font-size: small; color:#1e9d1e; 
											 vertical-align: bottom; padding-bottom: 0;">
											 <?php echo $uzdavinys; ?>
								</td>
							<?php } ?>
							<td></td>	<td></td>							
						 </tr>
						 <tr>
							<th colspan="2" style="text-align:right; font-size: small; padding-top: 0; ">Viso sprendimų priduota: </th>
							<?php 
								foreach($sumuotaeilute as $uzd) 
								  { ?>
								  <td style="text-align:center; font-size: small; padding-top: 0;"><?php echo $uzd; ?></td>
							<?php } ?>
							<td></td><td></td>							
						 </tr>
						</tbody>
					</table>
				</div>		
			  </div>
			</div>
		<?php }?>
	</div>		  
</div>
	  <!-- Uzdaviniu rezultatu pateikimo paaiskimimo modal-->
<div class="modal fade" id="uzdavRezSkaicPaaiskinimas" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Uždavinių sprendimų rezultatų paaiškinimas</h4>
        </div>
        <div class="modal-body">
		<p style="margin-bottom: inherit;">Lentelėje, dalyvio ir uždavinio sankirtoje, pateikiama, kiek kartų dalyvis pateikė uždavinio sprendimą ir ar uždavinį išsprendė teisingai:
		
		<ul style="line-height: 1.7em;">
			<li > „0“ - naudotojas nepateikė nei vieno sprendimo uždaviniui;
			<li> <span style="color:#1e9d1e; float: left;">„+X“ </span> - naudotojas teisingai išsprendė uždavinį ir pateikė X neteisingų sprendimų;
			<li> <span style="color:red; float: left;">„-X“ </span>- naudotojas pateikė X uždavinio sprendimų, bet teisingai neišsprendė.</p>
		</ul>
		
      </div>
	  <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" style="margin-bottom: 0px;">Uždaryti</button>
	</div>
    </div>
 </div>
</div>