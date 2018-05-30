<?php if($liko_laiko>"00:20:00") { ?>
	<meta http-equiv="refresh" content="300; url="javascript:window.history.go(0);"">
<?php }
	else if($liko_laiko>"00:00:00") { ?>
	<meta http-equiv="refresh" content="60; url="javascript:window.history.go(0);"">
<?php }; ?>	
	

 <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
<div class="container contactform center" id="home" style="min-height: calc(100% - 200px);">
	<div class="col-sm-10 col-sm-offset-1 col-xs-12 tableborder" style="padding-bottom: 15px;"> 
	  
		<h4 class="text-title wowload fadeInUp animated">Varžybų „<?php echo $v_pavad[0]->V_pavadinimas;?>“ turnyrinė lentelė</h4>
		<br>
		<h5 class="text-center  wowload animated" style="display:<?php if($liko_laiko>0){echo 'true';}else{echo 'none';}?>">Iki varžybų pabaigos: <?php  echo $liko_laiko;?></h5>
			
					<div style="max-height: calc(65%); width:100%; overflow: auto;">
					<table class="table table-bordered table-responsive">
						<tr style="background: #f8f8f8;">
							<th style="width: 4%; vertical-align: middle;" rowspan="2">Vieta</th>
							<th style="width: 20%; vertical-align: middle;" rowspan="2">Dalyvio vardas</th>
							<th style="text-align: center;" colspan="<?php echo sizeof($uzdaviniai); ?>">Varžybų uždaviniai 
								<i class="far fa-question-circle" data-toggle="modal" data-target="#uzdavRezSkaicPaaiskinimas"></i>
							</th>
							<th style="width: 10%; vertical-align: middle; text-align: center;" rowspan="2">Išspręstų uždavinių skaičius</th>
							<th style="width: 10%; vertical-align: middle;     text-align: center;" rowspan="2">Baudos taškai</th>
						</tr>
						<tr style="background: #f8f8f8;">							
							<?php $j=1; foreach($uzdaviniai as $u) {echo "<th style=\"text-align: center;\"><a href=\"".base_url()."index.php/Neregistruoti_naudotojai/uzdavinys/".$u->U_ID."\">".$j."</a></th>"; $j++;}?>
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
											echo $dalyvis->Naud_vardas;}
											else{echo $dalyvis->G_pavadinimas;}?>
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
			<li> <span style="color:red; float: left;">„-X“ </span>- naudotojas neišsprendė uždavinio ir pateikė X neteisingų sprendimų.</p>
		</ul>
		
      </div>
	  <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" style="margin-bottom: 0px;">Uždaryti</button>
	</div>
    </div>
 </div>
</div>