<div class="container contactform center" id="home" style="min-height: calc(100% - 200px);">
	
      <div class="col-sm-8 col-sm-offset-2 col-xs-12 tableborder">   
		<?php
			foreach ($uzdavinys as $row)
			{
			echo "<h4 style=\"text-align:center; margin-top:25px\">".$row->U_pavadinimas."</h4>";
			echo "<p dir=\"ltr\" style=\"text-align: justify;\">".$row->Salyga."</p>";
			if($row->Iliustracija == false){ echo "</br>";}
				else 
				{ 
					$files = glob("./images/uzdaviniu_iliustracijos/$row->U_ID".'*');
					foreach($files as $fullpath){ $ext = pathinfo($fullpath, PATHINFO_EXTENSION); }

					echo '<img src="'.base_url().'images/uzdaviniu_iliustracijos/'.$row->U_ID.'.'.$ext.'" class="animated bounceInUp" style="width:100%; display:block;" alt="iliustracija" />';
					
				}
			}
		?>
			
		<a href="javascript:window.history.go(-1);" class="btn btn-primary btn-dif" 
		style="padding-left:0px;padding-right:0px;">Grįžti</a>
      </div> 
  
</div>

<a href="#home" class="gototop "><i class="fa fa-angle-up  fa-3x"></i></a>