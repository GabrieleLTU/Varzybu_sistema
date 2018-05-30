<div class="container contactform center" id="home" style="min-height: calc(100% - 200px); margin-top:20px;">

<?php
 if($rodyti){?>
     <!-- -->
	 
	 <div class="container col-sm-10 col-sm-offset-1 col-xs-12  FadeInUp animated">
  
	  <ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#uzdaviniai">Uždaviniai</a></li>
		<li><a data-toggle="tab" href="#kuriami_u">Kuriami uždaviniai</a></li>
	  </ul>

	  <div class="tab-content" >
		<div id="uzdaviniai" class="tab-pane fade in active" 
		style="padding:15px; border:1px solid lightgray; border-top:none; margin-bottom: 20px;">		
		<div class="">    
		<h4  class="fadeInDown animated text-title">Uždaviniai</h4>	
		
		<button type="button" class="btn btn-primary btn-dif glyphicon glyphicon-search" style="width: auto; float:right; padding:6px 10px; margin-top:-1; margin-right:5px;" data-toggle="collapse" data-target="#u_paieska"></button>
			<div id="u_paieska" class="collapse" style="width:100%;">
				<input class="form-control" id="ieskoti_uzdavinio" type="text" placeholder="Ieškoti uždavinio pavadinimu.." style="width:100%; float: left;">	
			</div>			
        <div style="width:100%; overflow: auto; max-height: calc(50%); ">
		<table class="table table-hover">
			<thead>
			<tr>
				<th>Nr.</th>
				<th style="width:70%">Pavadinimas</th>
				<th>Išsprendė naudotojų</th>
			</tr>
			</thead>
			<tbody id="rodomi_uzdaviniai">
			<?php
			$i = 1;
			foreach ($uzdaviniai as $row)
			{
				echo "<tr>";
				echo "<td>".$i.".</td>";
				echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/uzdavinys/".$row->U_ID."\">".$row->U_pavadinimas."</a></td>";
				echo "<td style=\"text-align: center;\"><a href='".base_url()."index.php/Registruoti_naudotojai/uzdaviniu_issprendeju_sarasas/".$row->U_ID."'><i class=\"fa fa-user\"></i> x".$row->Issprende."</a></td>";
				echo "</tr>";
				$i=$i+1;
			}; ?>
			</tbody>
		 </table>
      </div></div>
		</div>
		<!--KURIAMI UZDAVINIAI skiltis-->
		<div id="kuriami_u" class="tab-pane fade" style="padding:15px; border:1px solid lightgray; border-top:none; margin-bottom: 20px;">

		<div class="">  		
		<h4 class="fadeInDown animated text-title">Kuriami uždaviniai</h4>
		<?php if($kurti_nauja){?>
		<a type="button" class="btn btn-primary" 
				style="width: auto; float: right; text-transform: none; padding: 6px 51px; margin-top: 0;" href="<?php echo base_url();?>index.php/Registruoti_naudotojai/u_kurimas">
		Kurti uždavinį</a><?php } ?>
		<button type="button" class="btn btn-primary btn-dif glyphicon glyphicon-search" style="width: auto; float:right; padding:6px 10px; margin-top:-1; margin-right:5px;" data-toggle="collapse" data-target="#paieska"></button>
			<div id="paieska" class="collapse" style="width:100%;">
				<input class="form-control" id="ieskoti_kuriamo_u" type="text" placeholder="Ieškoti uždavinio..." style="width:100%; float: left;">
			</div>	
		<div style="width:100%; overflow: auto; max-height: calc(50%); ">		
        <table class="table table-hover" >
			<thead>
			<tr>
				<th>Nr.</th>
				<th style="width:70%">Pavadinimas</th>
				<th>Būsena</th>
			</tr>
			</thead>
			<tbody id="kuriami_uzdaviniai">
			<?php
			$i = 1;
				if(sizeof($kuriami)==0)
			{
				?>
				<tr>
					<td colspan="5" style="text-align:center;" >Nėra kuriamų uždavinių. <?php if($kurti_nauja){ echo " Sukurti naują galite <a href='".base_url()."index.php/Registruoti_naudotojai/u_kurimas'>čia</a>"; };?></td>
				</tr>
				<?php 
			}
			else
			{
				foreach ($kuriami as $row)
				{
					echo "<tr>";
					echo "<td>".$i.".</td>";
					echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/uzdavinys/".$row->U_ID."\">".$row->U_pavadinimas."</a></td>";
					if($row->U_paruostas == true)
					{echo "<td style=\"color: green\">Paruoštas</td>";}
					else {echo "<td style=\"color: red\">Neparuoštas</td>";}
					echo "</tr>";
					$i=$i+1;
				};
			} ?>
			</tbody>
		 </table>
		</div>
		</div>		
		</div>
	  </div>
	</div>
	 
     <!-- -->
 <?php }
 
	else {?>
		<div class="col-sm-10 col-sm-offset-1 col-xs-12 tableborder  FadeInUp animated">    
		
			<h4 class="fadeInDown animated text-title">Uždaviniai</h4>
			<div class="fadeInUp animated  table-morepm">
		
			<button type="button" class="btn btn-primary btn-dif glyphicon glyphicon-search" style="width: auto; float:right; padding:6px 10px; margin-top:-1; margin-right:5px;" data-toggle="collapse" data-target="#u_paieska"></button>
			<div id="u_paieska" class="collapse" style="width:100%;">
				<input class="form-control" id="ieskoti_uzdavinio" type="text" placeholder="Ieškoti uždavinio pavadinimu.." style="width:100%; float: left;">	
			</div>
			<div style="width:100%; overflow: auto; max-height: calc(50%); ">
			<table class="table table-hover">
				<thead>
				<tr>
					<th>Nr.</th>
					<th style="width:70%">Pavadinimas</th>
					<th>Išsprendė naudotojų</th>
				</tr>
				</thead>
				<tbody id="rodomi_uzdaviniai">
				<?php
				$i = 1;
				foreach ($uzdaviniai as $row)
				{
					echo "<tr>";
					echo "<td>".$i.".</td>";
					echo "<td><a href=\"".base_url()."index.php/Registruoti_naudotojai/uzdavinys/".$row->U_ID."\">".$row->U_pavadinimas."</a></td>";
					echo "<td style=\"text-align: center;\"><a href='".base_url()."index.php/Registruoti_naudotojai/uzdaviniu_issprendeju_sarasas/".$row->U_ID."'><i class=\"fa fa-user\"></i> x".$row->Issprende."</a></td>";
					echo "</tr>";
					$i=$i+1;
				}; ?>
				</tbody>
			 </table>
		
		</div></div></div>
	
	<?php };?>
	  
	  <!-- Paieska kuriamuose uzdaviniuose-->		
	  <script>		
		$(document).ready(function(){
		$("#ieskoti_kuriamo_u").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#kuriami_uzdaviniai tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		});	
		$(document).ready(function(){
		$("#ieskoti_uzdavinio").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#rodomi_uzdaviniai tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		});
		</script>
	    
</div>