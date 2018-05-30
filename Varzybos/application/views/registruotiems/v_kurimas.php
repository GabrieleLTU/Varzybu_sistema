<div class="container contactform center" id="home" style="min-height: calc(100% - 270px); margin-top: 40px;">

  <div class=" wowload fadeInLeftBig">
  <form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/v_kurimas" > 
              
      <div class="col-sm-6 col-sm-offset-3 col-xs-12 tableborder"> 
		<h4 class="text-center  wowload fadeInUp" style="margin-top: inherit;">Varžybų kūrimas</h4>	  
        <label>Pavadinimas:  </label><input type="text" name="pavadinimas" value="<?php echo set_value('pavadinimas'); ?>" required><br/>
        <?php echo form_error('pavadinimas'); /*validation error*/?>
		<label style="width: 30%;">Pradžios data:  </label><input type="datetime-local" name="pr_data" style="width: 70%; text-align: center; item-align:right; padding: 0;" value="<?php echo set_value('pr_data'); ?>">
		<?php echo form_error('pr_data'); /*validation error*/?>
		<label style="width: 30%;">Varžybų trukmė (h:min):  </label>
			<input type="number" name="tr_h" min="0" max="800" style="padding: 10.2px; width: 30%; text-align: center;" value="<?php echo set_value('tr_h'); ?>"> :
			<input type="number" name="tr_min" min="0" max="59" style="padding: 10.2px; width: 20%; text-align: center;" value="<?php echo set_value('tr_min'); ?>">
		<?php echo form_error('trukme'); /*validation error*/?>
		</br>
		<!--<label style="width: 30%;">Paruoštos:    </label>
			<div style="display: inline;">
				<input type="radio" style="width: 5%; margin-left: 40px;" name="ar_paruostas" value="1" ><label>Taip</label>   
				<input type="radio" style="width: 5%; margin-left: 40px;"name="ar_paruostas" value="0" checked><label>Ne</label><br>
			</div>	
		<?php echo form_error('ar_paruostas'); /*validation error*/?>-->	
        <button type="submit" class="btn btn-primary" ><i class="submit" ></i>Kurti/Saugoti</button>
		<a href="javascript:window.history.go(-1);" class="btn btn-primary btn-dif" >Atšaukti</a>
      </div>
	 </form> 
  </div>
  
  </div>

