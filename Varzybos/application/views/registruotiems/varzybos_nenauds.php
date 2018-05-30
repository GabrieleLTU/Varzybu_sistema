<?php
if (isset($this->session->userdata['logged_in'])) {
$username = ($this->session->userdata['logged_in']['Naudotojo_vardas']);
} else {
//header("location: login");
}
?>

<div class="container contactform center">
<h2 class="text-center  wowload fadeInUp"> ... </h2>

<a type="button" class="btn btn-primary" style="float: right; width:25%" href="<?php echo base_url();?>index.php/Neregistruoti_naudotojai/v_kurimas">Kurti varžybas</a>
     
      <div class="col-sm-8 col-sm-offset-3 col-xs-12 tableborder">    
		<h4 style="text-align:center; margin-top:25px">Rengiamos varžybos</h4>	  
        <table class="table table-hover">
			<thead>
			<tr>
				<th>Pavadinimas</th>
				<th>Pradžia</th>
				<th>Trukmė</th>
				<th>Registracija</th>
				<th> </th>
			</tr>
			</thead>
			
			<tr>
				<td>Olimpiada 2017</td>
				<td>2017-10-31 </br> 18:00:00 h</td>
				<td>3:00:00 h</td>
				<td>Registracija</td>
				<td>x10</td>
			</tr>
  
		 </table>
      </div>
	  
	  <div class="col-sm-8 col-sm-offset-3 col-xs-12 tableborder">    
		<h4 style="text-align:center; margin-top:25px">Įvykusios varžybos</h4>	  
        <table class="table table-hover">
			<thead>
			<tr>
				<th>Pavadinimas</th>
				<th>Pradžia</th>
				<th>Trukmė</th>
				<th>Rzultatai</th>
				<th>Dalyvavo</th>
			</tr>
			</thead>
			<tr>
				<td>Olimpiada 2016</td>
				<td>2016-10-20 17:00:00 h</td>
				<td>3:00:00 h</td>
				<td><a href="<?php echo base_url();?>index.php/Neregistruoti_naudotojai/v_tur_lentele">Turnyrinė lentelė</a></td>
				<td>x45</td>
			</tr>
  
		 </table>
      </div>
	
	<?php
		print_r($visos_varzybos);		
		?>
  
  
</div>