<div class="container contactform center " id="home" style="min-height: calc(100% - 200px);">
	
      <div class="col-sm-10 col-sm-offset-1 col-xs-12 tableborder"> 
	  
			<h4 class="text-title" >
				<a 
					href="<?php echo base_url();?>index.php/Registruoti_naudotojai/
					<?php if($this->session->userdata['logged_in']['Yra_administratorius'])
								{echo "v_apzvalga/";}
						  else	{echo "v_vykdymas/";}
						  echo $this->uri->segment('3');?>">
						  <?php foreach($varzybos as $vienos_v){
						  echo "„".$vienos_v->V_pavadinimas."“";}?>
				</a>
			</h4>	
			<h5 class="text-center  wowload animated" style="display:<?php if($liko_laiko>0){echo 'true';}else{echo 'none';}?>">Iki varžybų pabaigos: <?php  echo $liko_laiko;?></h5>
		  <div class="text-center">
		  <?php
			$i=1;
			?><ul class="pagination"><?php
				foreach($uzdaviniai as $vienas_u)
				{
					//echo $vienas_u->ispresta;
					echo '<li><a href="'.base_url().'index.php/Registruoti_naudotojai/varzybu_uzdavinys/'.$this->uri->segment('3').'/'.$vienas_u->U_ID.'" ';
						if($vienas_u->U_ID==$this->uri->segment('4')){ echo ' class="active" ';}
						else if(!is_null($vienas_u->ispresta))
						{
							if($vienas_u->ispresta)
							{
								echo ' style=" background: #dff0d8;" ';
							}
							else{
								echo ' style=" background: #f2dede;" ';
							}
						}
							
					echo ' >'.$i.'</a></li>';		
					$i++;
				}
			?></ul>
			</div>
	  
		  <div class="  wowload fadeInUp animated" style="padding:15px; border:1px solid lightgray; margin-bottom: 20px;    display: flow-root;">
			<?php
				if(isset($ats))
				{
					if($ats=="neteisingas")
					{
						echo "<br><div class=\"alert alert-danger collapse show\" >
							Pateiktas atsakymas yra <strong>neteisingas</strong>.
							</div>";
					}
					else if($ats=="teisingas")
					{
						echo "<br><div class=\"alert alert-success collapse show\" >
							Pateiktas atsakymas yra <strong>teisingas</strong>.
							</div>";
					}
					else if($ats=="jau_priduotas")
					{
						echo "<br><div class=\"alert alert-dismissible alert-info collapse show\" >
							Jūs jau išsprendėte šį uždavinį teisingai.
							</div>";
					}
				}
			?>
			<?php
				foreach ($uzdavinys as $row)
				{
					echo "<h4 style=\"text-align:center; margin-top:25px;\">".$row->U_pavadinimas."</h4>";
					echo "<p dir=\"ltr\" style=\"text-align: justify;\">".$row->Salyga."</p>";
					if($row->Iliustracija == false){ echo "</br>";}
					else { 
					
						/*<img style="width:80%; margin-left:10%; display:block;" src="<?php echo base_url()."images/uzdaviniu_iliustracijos/".$row->U_ID; ?>" class="animated bounceInUp" style="width:100%;" alt="iliustracija"/>*/
						$files = glob("./images/uzdaviniu_iliustracijos/$row->U_ID".'*');
					foreach($files as $fullpath){ $ext = pathinfo($fullpath, PATHINFO_EXTENSION); }
					//echo $ext;
					echo '<img src="'.base_url().'images/uzdaviniu_iliustracijos/'.$row->U_ID.'.'.$ext.'" class="animated bounceInUp" style="width: 70%; display: block; margin-left: 15%;" alt="iliustracija" />';
					
						
						 }						
				
				if($rodyti)
				{
					?>
					<button type="button" class="btn btn-primary" style="text-transform: none; padding: 8px;" data-toggle="collapse" data-target="#atsakymas">Žiūrėti atsakymą</button>
					<button type="button" class="btn btn-primary" style="text-transform: none; padding: 8px; float: right;" data-toggle="modal" data-vid=<?php echo $row->U_ID; ?> data-target="#senuatsModal">Naudotojų priduoti atsakymai</button>
						<div id="atsakymas" class="collapse" style="width:100%;">
						<br>
						Uždavinio atsakymas: <strong> <?php echo $row->Atsakymas; ?></strong>.
						</div>
					<a href="javascript:window.history.go(-1);" class="btn btn-primary btn-dif">Atšaukti</a>
					<a href="<?php echo base_url();?>index.php/Registruoti_naudotojai/u_keitimas/<?php echo $row->U_ID; ?>" class="btn btn-primary" >Redaguoti</a>
					<?php
				}
				else
				{
					?>
					<form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/varzybu_uzdavinys/<?php echo $this->uri->segment('3').'/'.$this->uri->segment('4');?>"> 
					<hr>
					<label>Atsakymas:  </label><input type="text" name="atsakymas" autocomplete="off"><br/>
					<?php echo form_error('atsakymas'); /*validation error*/?>
					
					<button type="submit" class="btn btn-primary submit" style="width:100%">Pateikti atsakymą</button>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-vid=<?php echo $row->U_ID; ?> data-target="#senuatsModal" >Anksčiau pateikti atsakymai</button>
					<a href="javascript:window.history.go(-1);" class="btn btn-primary btn-dif" >Atšaukti</a>
					
					</form>
					<?php
				}
				}
			?>
				
			</form> 
		</div>
	</div>
</div>

<!-- Priduotų naudotojo atsakymų peržiūrėjimo modal-->
<div class="modal fade" id="senuatsModal" role="dialog" style="max-height: -webkit-fill-available;">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;"><?php echo ($rodyti ? "Naudotojų" : "Jūsų"); ?> pateikti uždavinio "<?php echo $row->U_pavadinimas;?>" atsakymai</h4>
        </div>
        <div class="modal-body" style="max-height:calc(100vh - 200px); overflow-y: auto;">		
         <table class="table table-hover">
			<thead>
			<tr>
				<th style="text-align: center; width: 30%;">Data</th>
				<?php if($rodyti){ echo "<th style=\"text-align: center;\">Naudotojo vardas</th>"; } ?>
				<th style="text-align: center;">Pateiktas atsakymas</th>				
			</tr>
			</thead>
			<tbody>
			<?php
			$i=0;
			foreach ($sprendimai as $row)
			{
				if($row->Issprestas == true){echo "<tr class=\"success\">";}
				else {echo "<tr class=\"danger\" >";}	
				echo "<td>".$row->Pridavimo_laikas."</td>";
				if($rodyti){ echo "<td>".$row->Naud_vardas."</th>"; }
				echo "<td style=\"text-align: center;\">".$row->Pateiktas_atsakymas."</td>";				
				echo "</tr>";
				$i=$i+1;
			};?>			
			</tbody>
		 </table>
		 
        </div>
		
        <div class="modal-footer">
		<p style="float: left;">Viso priduota: <?php echo $i?> kartus/ų</p>
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" style="float:right;">Uždaryti</button>
        </div>
      </div>
      </div>
</div>