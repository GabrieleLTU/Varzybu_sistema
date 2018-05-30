<div class="container contactform center" id="home" style="min-height: calc(100% - 270px); margin-top: 40px;">

	<div class="col-sm-8 col-sm-offset-2 col-xs-12 tableborder"> 
			
		
		<?php foreach($varzybos as $vienos_v){?>
		
			<h4 class="text-title wowload fadeInUp animated" 
				>"<?php echo $vienos_v->V_pavadinimas; 
				if($rodyti && ($liko_laiko>"00:00:00"))
				{
					echo "<a href=\"".base_url()."index.php/Registruoti_naudotojai/v_keitimas/".$vienos_v->V_ID."\" style=\"font-size: 14px;\"> 
								<i class=\"fa fa-edit\" style=\"vertical-align: baseline;\"></i></a>";
			 	}?>"
			</h4>
					<h5 class="text-center  wowload animated" style="display:<?php if($liko_laiko>"00:00:00"){echo 'true';}else{echo 'none';}?>">Iki varžybų pabaigos: <?php  echo $liko_laiko;?></h5>						
					
			<br>
			
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#uzdaviniai">Uždaviniai</a></li>
				<li><a data-toggle="tab" href="#t_lentele">Turnyrinė lentelė</a></li>
			</ul>
		
			<div class="tab-content" >
			  <div id="uzdaviniai" class="tab-pane fade in active" style="padding:15px; border:1px solid lightgray; border-top:none; margin-bottom: 20px;">
					<h5 class="text-center  wowload animated">Uždaviniai:</h5> 
					<table class="table table-hover table-responsive">
						<tr>
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
					<h5 class="text-center  wowload animated">Turnyrinė lentelė</h5>
					<br>
					<?php if(isset($paskutinis_sprendimas)) { ?>
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
		<?php }; ?>
	
					<div style=" width:100%; overflow: auto;">
					<table class="table table-hover table-responsive">
						<tr>
							<th style="width: 7%;">Vieta</th>
							<th>Naudotojo vardas</th>
							<th>Išsprende uždavinių</th>
							<th>Baudos taškai</th>
						</tr>
						<tbody>
						<?php
							$i=1;
						 foreach($t_lentele as $dalyvis){ ?>
							<tr>
								<td><?php echo $i;?></td>
								<td><?php echo $dalyvis->N_ID;?></td>
								<td><?php echo $dalyvis->Issprende_u;?></td>
								<td><?php echo $dalyvis->Taskai_isviso;?></td>
							</tr>
						 <?php $i=$i+1; }?>
						</tbody>
					</table>
					</div>
			  </div>
			</div>
		<?php }?>
	</div>		  
</div>
</script>