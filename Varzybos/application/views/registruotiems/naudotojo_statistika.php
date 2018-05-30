<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<div id="home" class="container contactform center" style="min-height: calc(100% - 200px);">
	<div>
		<?php if(!$yra_admin){ ?>
		<div class="col-sm-8 col-sm-offset-2 col-xs-12 tableborder fadeInUp animated"> 
			<h4 class="text-center  wowload fadeInUp" style="margin-top: inherit;">Jūsų statistika</h4>
			<!-- DALYVAVIMO VARŽYBOSE STATISTIKA -->
			<div style="height: 420; overflow: auto;">
			<h4 style="margin: 25px; font-size: large;"><i class="fas fa-greater-than"></i> Dalyvavimas varžybose</h4>
			<hr style="margin:10 0;"><br>
			<?php if($n_statistika['dalyvavo_var']>0){ ?>
				<div style="width:40%; float: left; height: 300px;">
					<ul>
						<li>Viso dalyvavote <?php echo $n_statistika['dalyvavo_var'] ;?> varžybose.</li>		
						<li>Viso <?php echo $n_statistika['dalyvavo_var_kaip_grupe'] ;?> važybose dalyvavote kaip komanda.</li>		
					</ul>
				</div>		
				<div class="col-md-5" style="width:60%;  float: right;">
					<div id="piechart_kaip_dalyv_varzybose" style="height: 300px;"></div>
				</div>
			<?php } else { echo '<p>Jūs dar nedalyvavote nei vienose varžybose.<br>Kokios varžybos organizuojamos rasite viršutiniame meniu pasirinkę <a href="'.base_url().'index.php/Registruoti_naudotojai/varzybos">"varžybos"</a>. </p>';}?>
			</div>
			<br>
			<!-- UŽDAVINIŲ SPRENDIMO STATISTIKA -->
			<div style=" height: 430;overflow: overlay;">
			<h4 style="margin: 25px; font-size: large;"><i class="fas fa-greater-than"></i> Uždavinių sprendimas</h4>
			<hr style="margin:10 0;"><br>
			<br>
			<?php if($n_statistika['sprendimu_pridve']>0){ ?>
			<div style="width:40%; float: left; height: 300px;">
				<ul>
				<li>Viso sprendėte  <?php echo $n_statistika['sprende_u'] ; 
									if($n_statistika['sprende_u']<10){ echo " uždavinius";} 
									else {echo "uždavinių";}?>.</li>
				<li>Iš jų teisingai išsprendėte <?php echo $n_statistika['teisingai_issprende'] ; 
									if($n_statistika['teisingai_issprende']<10){ echo " uždavinius";} 
									else {echo "uždavinių";}?>.</li>
				<li>Iš viso pridavėte <?php echo $n_statistika['sprendimu_pridve'] ; 
									if($n_statistika['sprendimu_pridve']<10){ echo " sprendimus";} 
									else {echo "sprendimų";}?>.</li>
				</ul>
			</div>
			<div class="col-md-5" style="width:60%;  float: right;">
				<div id="piechart_u_sprendimai" style="height: 300px;"></div>
			</div>
			<?php } else { echo '<p>Jūs dar nesprendėte nei vieno uždavinio.<br>Uždavinių sąrašą rasite viršutiniame meniu pasirinkę "uždaviniai" arba dalyvaukite varžybose. </p>';}?>
			 
        </div> </div>		
			<?php } ?>
	  
	</div>
</div>

<!-- Naudotojo profilio statistikos diagramoms -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
 <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

	  <?php if($n_statistika['sprendimu_pridve']>0){ ?>
        var data = google.visualization.arrayToDataTable([
          ['Ar uždavinys išspręstas?', 'uždavinių skaičius'],
          ['Teisingai išspręsta uždavinių',     <?php echo $n_statistika['teisingai_issprende'] ;?>],
          ['Neteisingai išspręsta uždavinių',   <?php echo $n_statistika['sprende_u']-$n_statistika['teisingai_issprende'] ;?>]
        ]);

        var options = {
		  title: 'Kiek išsprendėte uždavinių?',
		  colors:['#003a6c','#9c1f31'],
		  legend: {position: 'bottom'},
		  chartArea: {top: 20},
		  pieHole: 0.5
        };
		
		
        var chart = new google.visualization.PieChart(document.getElementById('piechart_u_sprendimai'));
		chart.draw(data, options);
		<?php }; 
         if($n_statistika['dalyvavo_var']>0){ ?> 
	   
		var data2 = google.visualization.arrayToDataTable([
          ['Kaip varžybose dalyvauta?', 'varžybų skaičius'],
          ['Kaip naudotojas',   <?php echo $n_statistika['dalyvavo_var']-$n_statistika['dalyvavo_var_kaip_grupe'] ;?>],
          ['Kaip komanda',      <?php echo $n_statistika['dalyvavo_var_kaip_grupe'] ;?>]
        ]);

        var options2 = {
		  title: 'Kaip dalyvaujate varžybose?',
		  colors:['#8c8d8f','#003a6c'],
		  legend: {position: 'bottom'},
		  chartArea: {top: 20},
		  pieHole: 0.5
        };

        var chart2 = new google.visualization.PieChart(document.getElementById('piechart_kaip_dalyv_varzybose'));

        chart2.draw(data2, options2);
	   <?php };?>
      }
    </script>
