<!-- Header Starts -->

<div class="topbar animated"></div>
<div class="navbar-wrapper">
      <div class="container">

        <div class="navbar navbar-default navbar-fixed-top" role="navigation" id="top-nav">
          <div class="container">
            <div class="navbar-header">
              <!-- Logo Starts -->
              <a style="padding-top:3" href="<?php echo base_url();?>index.php/Registruoti_naudotojai/pagrindinis"><img src="<?php echo base_url();?>images/logo.png" alt="logo"></a>
              <!-- #Logo Ends -->


              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>

            </div>


            <!-- Nav Starts -->
            <div class="navbar-collapse  collapse">
              <ul class="nav navbar-nav navbar-right ">
                 <li <?php if($a=="pagrindinis"){ echo "class='active'";}?> ><a href="<?php echo base_url();?>/index.php/Registruoti_naudotojai/pagrindinis">Pagrindinis</a></li>                 
                 <li <?php if($a=="varzybos" ){ echo"class='active'";} ;?> ><a href="<?php echo base_url();?>/index.php/Registruoti_naudotojai/varzybos">Varžybos</a></li>
                 <li <?php if($a=="uzdaviniai" ){echo "class='active'";} ;?> ><a href="<?php echo base_url();?>/index.php/Registruoti_naudotojai/uzdaviniai">Uždaviniai</a></li>
				 <li <?php if($a=="naudotojai"){ echo "class='active'";}?> ><a href="<?php echo base_url();?>/index.php/Registruoti_naudotojai/naudotoju_sarasas">Naudotojai</a></li>  
				 <li <?php if($a=="DUK" ){ echo "class='active'";} ;?> ><a href="<?php echo base_url();?>/index.php/Registruoti_naudotojai/DUK">DUK</a></li>	
						
				<li <?php if($a=="naudvardas" ){ echo "class='dropdown active'";}else{echo "class='dropdown'";}  ;?> >
					<a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true"><?php echo $naud_vardas; if($yrazinute){ echo " <span class=\"badge\" style=\"background-color: #8c8d8fe6;\"> ! </span>";}?> <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo base_url()."/index.php/Registruoti_naudotojai/naudotojo_profilis"?>">Profilio nustatymai</a></li>
							<?php if(!$yra_admin){ ?>
						<li><a href="<?php echo base_url()."/index.php/Registruoti_naudotojai/naudotojo_statistika"?>">Mano statistika</a></li>
						<li><a href="<?php echo base_url()."/index.php/Registruoti_naudotojai/mano_grupiu_sarasas"?>">Mano komandos</a></li>
							<?php }
							else { ?>
						<li><a href="<?php echo base_url()."index.php/Registruoti_naudotojai/naudotoju_teises"?>">Teisės naudotojams</a></li>
						<li ><a data-toggle="modal" data-target="#SlaptKeitimas" >Keisti kito naudotojo<br>slaptažodį</a></li>	
							<?php };?>
						<li><a href="<?php echo base_url()."/index.php/Registruoti_naudotojai/zinutes"?>"><?php if($yra_admin){ echo "Žinutės"; }else {echo "Žinutė administratoriui";}; if($yrazinute){ echo " <span class=\"badge\" style=\"background-color: #8c8d8fe6;\"> ! </span>";}?></a></li>
							
						<li class="divider"></li>
						<li><a href="<?php echo base_url()."/index.php/Registruoti_naudotojai/atjungti"?>">Atsijungti</a></li>
					</ul>
				</li>	
				</ul>
            </div>
            <!-- #Nav Ends -->
          </div>
        </div>
      </div>
</div>
	  <!-- Modal Slaptažodžio priminimui-->
<div class="modal fade" id="SlaptKeitimas" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Slaptažodžio keitimas</h4>
        </div>
        <div class="modal-body">		
		<form method="post" action="<?php echo base_url();?>index.php/Registruoti_naudotojai/keisti_slaptazodi"> 
			<label> Naudotojo vardas:  </label>
			<input type="text" name="naudotojo_vardas" 
					value="<?php echo set_value('naudotojo_vardas'); ?>" 
					style="width: 100%; padding: 1em; border: 1px solid #ccc;
							margin-bottom: 1em; border-radius: 0; outline: none;" required><br/>
			<?php echo form_error('naudotojo_vardas'); /*validation error*/?>
			<label> Naujas slaptažodis:  </label>
			<input type="text" name="slaptazodis" 
					value="<?php echo set_value('slaptazodis'); ?>" 
					style="width: 100%; padding: 1em; border: 1px solid #ccc;
							margin-bottom: 1em; border-radius: 0; outline: none;" required><br/>
			<?php echo form_error('slaptazodis'); /*validation error*/?>
        </div>
		
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" style="margin-bottom: 0px;">Atšaukti</button>
		  <button type="submit" class="btn btn-primary" ><i class="submit"></i>Keisti slaptažodį</button>
        </div>
		</form>
      </div>
    </div>
 </div>
 
 	  <!-- Modal Slaptažodžio priminimui-->
<div class="modal fade" id="InfoModal" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" href="<?php echo base_url().$goTo; ?>">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Pranešimas</h4>
        </div>
        <div class="modal-body">
			<p><?php echo $InfoModalMessage; ?></p>		
        </div>
		
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-dif" data-dismiss="modal" style="margin-bottom: 0px;">Gerai</button>
			<a href="<?php echo base_url().$goTo; ?>" class="btn-dif" >Ok</a>
		</div>
      </div>
    </div>
 </div>
 
<script>
$(window).on('load',function(){
		<?php if(isset($InfoModalMessage)){ 
		echo"$('#InfoModal').modal('show');";
		} else echo "$('#InfoModal').hide();"; ?>

    });
	</script>
