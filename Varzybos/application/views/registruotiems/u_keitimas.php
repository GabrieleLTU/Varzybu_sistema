
<div class="container contactform center" id="home" style="min-height: calc(100% - 270px); margin-top: 40px;">
<?php
	 foreach($duomenys as $row){?>
	
	  <form method="post" 
			action="<?php echo base_url();?>index.php/Registruoti_naudotojai/u_keitimas/<?php echo $row->U_ID ?>" 
			enctype="multipart/form-data" class="row wowload fadeInLeftBig">  
		  <div class="col-sm-6 col-sm-offset-3 col-xs-12 tableborder"> 
		  
			<h4 class="text-center  wowload fadeInUp" style="margin-top: inherit;">Uždavinio redagavimas</h4>
			
			<label>Pavadinimas:  </label><input type="text" name="pavadinimas" value="<?php echo $row->U_pavadinimas ?>" required><br/>
			<?php echo form_error('pavadinimas'); /*validation error*/?>
			
			<label>Sąlyga:       </label><textarea rows="5" name="salyga" required><?php echo $row->Salyga; ?></textarea>
			<?php echo form_error('salyga'); /*validation error*/?>
			
			<label>Atsakymas:    </label><input type="text" name="atsakymas" value="<?php echo $row->Atsakymas ?>" required>
			<?php echo form_error('atsakymas'); /*validation error*/?>
			
			<label>Iliustracija (.jpg, .png ar .gif formatu): </label>
			<input type="radio" id="yra_pav" name="yra_pav" 
				<?php echo ($row->Iliustracija==1 ? 'checked':' ')?>
					style="display: none;"
			/>
			<?php if($row->Iliustracija==true)
			{?>				
				<button type="button" style="float: right; margin-bottom: 2px;" onclick="closeImage()" title="trinti iliustraciją" id="image2">X</button>
				<?php				
				/*echo "<img  src=\"".base_url()."images/uzdaviniu_iliustracijos/".$row->U_ID."\" 
							class=\"animated bounceInUp\" style=\"width:100%\" 
							alt=\"iliustracija\" id=\"image\" value=\"true\">";*/
							
							$files = glob("./images/uzdaviniu_iliustracijos/$row->U_ID".'*');
					foreach($files as $fullpath){ $ext = pathinfo($fullpath, PATHINFO_EXTENSION); }
					//echo $ext;
					echo '<img src="'.base_url().'images/uzdaviniu_iliustracijos/'.$row->U_ID.'.'.$ext.'" class="animated bounceInUp" style="width: 70%; display: block; margin-left: 15%;" alt="iliustracija" id="image" value="true"/>';
					
			}?>
			<input type="file" name="userfile" />
			<?php echo form_error('userfile'); /*validation error*/?>
			
			<label style="width: 30%;">Paruoštas:    </label>
				<div style="display: inline;">
					<input type="radio" style="width: 5%; margin-left: 40px;" name="ar_paruostas" value="1" <?php if($row->U_paruostas == 1) {echo 'checked';}?>><label>Taip</label>   
					<input type="radio" style="width: 5%; margin-left: 40px;"name="ar_paruostas" value="0"  <?php if($row->U_paruostas == 0) {echo 'checked';}?>><label>Ne</label><br>
				</div>		
			
			<button type="submit" class="btn btn-primary submit" value="upload"style="width: 100%;"> Kurti/Saugoti</button>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#arTrinti">Naikinti uždavinį</button>
			<a href="javascript:window.history.go(-2);" class="btn btn-primary btn-dif" >Atšaukti</a>
		 
		 </div>
  </form>
  
	<?php } ?>
</div>

<!-- Modal Patvirtinti uždavinio trynimui-->
<div class="modal fade" id="arTrinti" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Uždavinio trynimo patvirtinimas</h4>
        </div>
        <div class="modal-body">
		
		<p>Ar tikrai norite ištrinti visiems laikams šį uždavinį?</p>
		
        </div>
		
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" style="margin-bottom: 0px;">Atšaukti</button>
          <a href="<?php echo base_url()."index.php/Registruoti_naudotojai/u_trinimas/".$row->U_ID ?>" class="btn btn-primary" style="width:49%">Ištrinti</a>
		  
        </div>
      </div>
      </div>
      </div>
	  
<!-- closeImage() -->	 
<script>
	function closeImage()
	{
		document.getElementById("image").value = false;
		$("#image").hide();
		$("#image2").hide();
		document.getElementById("yra_pav").checked = false;
	}
</script> 