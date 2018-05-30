<!-- Header Starts -->
<div class="topbar animated"></div>
<div class="navbar-wrapper">
      <div class="container">

        <div class="navbar navbar-default navbar-fixed-top" role="navigation" id="top-nav">
          <div class="container">
            <div class="navbar-header" >
              <!-- Logo Starts -->
              <a  style="padding-top:3px" href="<?php echo base_url();?>index.php/Neregistruoti_naudotojai/pagrindinis"><img src="<?php echo base_url();?>images/logo.png"  alt="logo"></a>
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
                 <li <?php if($a=="pagrindinis"){ echo "class='active'";}?> ><a href="<?php echo base_url();?>/index.php/Neregistruoti_naudotojai/pagrindinis">Pagrindinis</a></li>                 
                 <li <?php if($a=="varzybos" ){ echo"class='active'";} ;?> ><a href="<?php echo base_url();?>/index.php/Neregistruoti_naudotojai/varzybos">Varžybos</a></li>
                 <li <?php if($a=="uzdaviniai" ){echo "class='active'";} ;?> ><a href="<?php echo base_url();?>/index.php/Neregistruoti_naudotojai/uzdaviniai">Uždaviniai</a></li>
				 <li <?php if($a=="DUK" ){ echo "class='active'";} ;?> ><a href="<?php echo base_url();?>/index.php/Neregistruoti_naudotojai/DUK">DUK</a></li>	
				 <li style="padding:15;"><a href="<?php echo base_url();?>/index.php/Neregistruoti_naudotojai/prisijungimas" class="btn-dif"
						style="margin:0; line-height: 2.6em; margin-top:0.5em; padding: 0; padding-left: 10; padding-right: 10; width: 100%; color: #003a6c;">
						Prisijungti</a></li>	
				 <li style="padding:15;"><a href="<?php echo base_url();?>/index.php/Neregistruoti_naudotojai/registracija" 
						style="margin:0; line-height: 2.6em; margin-top:0.5em; padding: 1.5px 10px 1.5px 10px; background: #003a6c;color: #fff;" >
						Registruotis</a></li>
				</ul>
            </div>
            <!-- #Nav Ends -->
          </div>
        </div>

      </div>
    </div>
<!-- #Header Starts -->
