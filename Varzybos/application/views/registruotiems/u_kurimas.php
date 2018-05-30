
<div class="container contactform center" id="home" style="min-height: calc(100% - 270px); margin-top: 40px;">
<?php if(!isset($duomenys)){?>
  <form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/u_kurimas" 
		enctype="multipart/form-data" class="row wowload fadeInLeftBig">  
      <div class="col-sm-6 col-sm-offset-3 col-xs-12 tableborder">      
	  <h4 class="text-center  wowload fadeInUp" style="margin-top: inherit;">Uždavinio kūrimas</h4>
        <label>Pavadinimas:  </label><input type="text" name="pavadinimas" value="<?php echo set_value('pavadinimas'); ?>" required><br/>
		<?php echo form_error('pavadinimas'); /*validation error*/?>
		<label>Sąlyga:       </label><textarea rows="5" name="salyga"  required style="max-width: 100%;
    min-width: 100%;"><?php echo set_value('salyga'); ?></textarea>
		<?php echo form_error('salyga'); /*validation error*/?>
		<label>Atsakymas:    </label><input type="text" name="atsakymas" value="<?php echo set_value('atsakymas'); ?>" required>
		<?php echo form_error('atsakymas'); /*validation error*/?>
		<label>Iliustracija: </label><input type="file" name="userfile" />
			
		<label style="width: 30%;">Paruoštas:    </label><!-- input type file (ikelti faila -->
			<div style="display: inline;">
				<input type="radio" style="width: 5%; margin-left: 40px;" name="ar_paruostas" value="1" ><label>Taip</label>   
				<input type="radio" style="width: 5%; margin-left: 40px;"name="ar_paruostas" value="0" checked><label>Ne</label><br>
			</div>		
		
        <button type="submit" class="btn btn-primary" value="upload" style="margin-bottom: 10px;"><i class="submit" ></i> Kurti/Saugoti</button>
		<a href="javascript:window.history.go(-1);" class="btn btn-primary btn-dif" style="float:right;">Atšaukti</a>
      </div>
  </form>
<?php } ?>
</div>