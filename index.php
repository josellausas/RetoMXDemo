<?php

$page_title	=	"NIBSI / RetoAlertaMx";
require('cap-reader.php');

$url_conagua 		= "https://correo1.conagua.gob.mx/feedsmn/feedalert.aspx";
$cap_parser 			= new LL_CAP();
$cap_data 			= $cap_parser->get($url_conagua);

?>
<script>

function cap_detail(title, updated, type, event_name,description, urgency, area_description, area_type,area_coordinates) {
	 
	 document.getElementById("cap_detail_title").innerHTML					= title;
	 document.getElementById("cap_detail_updated").innerHTML 			= updated;
	 document.getElementById("cap_detail_type").innerHTML 				= type;
	 document.getElementById("cap_detail_urgency").innerHTML 			= urgency;
	 
	 document.getElementById("cap_detail_event").innerHTML 				= event_name;
	
	 document.getElementById("cap_detail_description").innerHTML 		= description;
	 
	 /*
	 document.getElementById("cap_detail_area_description").innerHTML 	= area_description;
	 document.getElementById("cap_detail_area_type").innerHTML 			= area_type;
	 document.getElementById("cap_detail_area_cordinates").innerHTML	= area_coordinates;
	 */
	 
	 create_cap_map(area_type, area_coordinates);
	 document.getElementById('cap_detail_wrapper').style.visibility= 'visible' ;

}

function create_cap_map(area_type, area_coordinates) {

		//alert(area_type);
		//alert(area_coordinates);
		
		if(area_type=="circle") {
			draw_circle_on_map (area_coordinates)
		}
		else
		{
			draw_poly_on_map (area_coordinates)
		}
}

function draw_circle_on_map (area_coordinates) {
	
		  var coordinates 			= area_coordinates.split(" ");
		  var latitud_longitud		=	coordinates[0].split(",");
		  var radio						=	coordinates[1];
		  
		  var latitud					=	latitud_longitud[0];
		  var longitud					=	latitud_longitud[1];
		  
		  /*
		  alert(latitud);
		  alert(longitud);
		  alert(radio);
		  */
		  // Add circle
		  map.remove();
		  map = L.mapbox.map('map', 'zunware.ipmlddlh').setView([latitud, longitud], 2);
		  L.circle([latitud, longitud], radio, {color:'red'}).addTo(map);	
		  
}

function draw_poly_on_map (area_coordinates) {

		  // alert('draw_poly_on_map');
		  // alert(area_coordinates);
		  var poly = area_coordinates.split(" ");
		  
		  var myArray = [];
		  
  		  poly.forEach(function(entry) {
		  var pair 		= entry.split(',');
		  var numPair 	= [];
		  numPair[0] 	= parseFloat(pair[0]);
		  numPair[1] 	= parseFloat(pair[1]);
		  myArray.push(numPair);
		  });
		
		  map.remove();
		  map = L.mapbox.map('map', 'zunware.ipmlddlh').setView([32.62, -117.11], 5);
		  map.fitBounds(myArray);
		  L.polygon(myArray,{color:'red'}).addTo(map);
}

</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

     <meta charset="utf-8">
     
     <link href="css/styles.css" rel="stylesheet" type="text/css" />
     <!-- Link the Mapbox API -->
     <script src='https://api.tiles.mapbox.com/mapbox.js/v1.6.4/mapbox.js'></script>
     <link  href='https://api.tiles.mapbox.com/mapbox.js/v1.6.4/mapbox.css' rel='stylesheet' />
     <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
     <link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.css' rel='stylesheet' />
     <script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.js'></script>
     <script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-geodesy/v0.1.0/leaflet-geodesy.js'></script>
     
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
     <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
     <meta http-equiv="Pragma" content="no-cache" />
     <meta http-equiv="Expires" content="0" />
     
     <title><?=$page_title?>asd</title>

</head>

<body>

<div id="nibsi_alertamx_page_container">

          <div id="nibsi_alertamx_header_wrapper" style="color:#FFFFFF;">RetoAlertaMX</div><!-- nibsi_alertamx_header_wrapper -->

          <div id="nibsi_alertamx_header_content">
               <div style="float:right"><img src="images/logo_negro_transparente.png" width="200" height="100" /></div>
          </div>
          <!-- nibsi_alertamx_header_content -->


          <div id="nibsi_alertamx_body_content">
                    
                              <div id="conagua_box" style="width:100%; height:auto; background-color:transparent; display:block; padding-bottom:20px;">

                                                  <div style="font-size:14px; font-weight:bold; text-transform:uppercase; margin-bottom:2px;">Evidencia de lectura de CAP</div>
                                                  <div style="font-size:12px; font-weight:normal; text-transform:none; margin-bottom:2px;"><strong>Fuente:</strong>&nbsp;CONAGUA</div>
                                                  <div style="font-size:12px; font-weight:normal; text-transform:lowercase; margin-bottom:2px;"><?=$url_conagua?></div>
                              
                              </div>
                              <!-- conagua_box -->
                                        
                               <div id="cap_instructions_section">
                               
                                   <div style="font-size:12px; font-weight:normal; text-transform:none; margin-top:10px; margin-bottom:2px;">
                                                  <strong>INSTRUCCIONES</strong>
                                                  <ol>
                                                            <li style="margin-bottom:6px;">Presione sobre algún renglón de la lista de alertas</li>
                                                            <li style="margin-bottom:6px;">Aparecerá el detalle de la alerta en el dispositivo izquierdo
                                                            <ul>
                                                            <li style="margin-bottom:6px;">Detalle de la alerta</li>
                                                            <li style="margin-bottom:6px;">Área afectada en el mapa</li>
                                                            <li style="margin-bottom:6px;"><a href="http://www.nibsi.com.mx/retoalertamx/"><input name="datos" type="button" value="Regcargar Datos de CONAGUA" /></a></li>
                                                  </ul>
                                                  </li>
                                                  </ol>
                                   </div>
                                                  
                                    <div style="font-size:12px; font-weight:normal; text-transform:none; margin-top:20px; margin-bottom:2px;">
                                    
                                              <strong>OBSERVACIONES</strong>
                                              <br />
                                              <ol>

                                                   <li style="margin-bottom:6px;"><span style="text-transform:none">Datos CAP cargados en tiempo real</span></li>
                                                   <li style="margin-bottom:6px;"><span style="text-transform:none">Validar el trazo del área afectada dentro del mapa</span></li>
                                                   <li style="margin-bottom:6px;"><span style="text-transform:none">En la parte inferior se muestra el objeto interpretado del XML del CAP de CONAGUA</span></li>                                                   
                                                   <li style="margin-bottom:6px;"><span style="text-transform:none">
                                                   Contamos con experiencia para parsear cualquier XML con distintos lenguajes de programación:</span>
                                                   <ul>
                                                   <li>PHP</li>
                                                   <li>JAVA</li>
                                                   <li>LUA</li>
                                                   <li>.NET, ASP, ASPX</li>
                                                   </ul>
                                                   
                                                   </li>     
                                              </ol>
                                    
                                    </div>
                                    
                                    <div style="font-size:12px; font-weight:normal; text-transform:none; margin-top:20px; margin-bottom:2px;">
                                    
                                             <strong>NOTA</strong>
                                             <br />
                                             <ul>
                                                  <li style="margin-bottom:6px; text-transform:uppercase;">La imagen no representa el diseño final de la aplicación</span></li>
                                             </ul>
                                    
                                    </div>                                    
                                    
                                    
                                    
                               </div>
                              
                              <div id="cap_detail_section" class="device_image">
                              
                                        <div id="app_header"></div>
                                        
                                   <div id="cap_detail_wrapper" style="visibility:hidden;">
                                        
                                                                 <div id="cap_detail_data">
                                                                 		   <div class="cap_detail_tormenta_icon"></div>
                                                                           <div id="cap_detail_title">cap_detail_title</div>
                                                                           <div id="cap_detail_updated" style="float:left; margin-left:44px; color:#FFFFFF;">cap_detail_updated</div>
                                                                           <div id="cap_detail_type" style="float:left; margin-left:44px; color:#FFFFFF;">type</div>
                                                                           <div id="cap_detail_urgency" style="float:left; margin-left:44px; color:#FFFFFF;">urgency</div>
                                                                           
                                                                           <div id="cap_detail_event">event</div>
                                                                           <div id="cap_detail_description">description</div>     
                                                                 </div>
                    
                                                                 <div id="cap_detail_map">
                                                                      <div id='map'></div>
                                                                 </div>
                                                              
                                             </div>
                                   <!--cap_detail_wrapper -->          
                                        
                              </div>
                              <!-- cap_detail -->                       
                                       
                              <div id="cap_list_section" class="device_image">
                              
                                        <div id="app_header"></div>
                              
                                        <div id="cap_list_wrapper">
                                        
                    
                                                      <?php  $show_markee=1;
                                                                  foreach($cap_data->alertas as $alerta ) {  
                                                                 $area_description	=	$alerta->areas[0]->description;
                                                                 $area_type				=	 $alerta->areas[0]->type;
                                                                 $area_coordinates	=	 $alerta->areas[0]->data[0];								  
                                                        ?>
                                                      
                                                            <div class="cap_list_row" onClick="cap_detail(
                                                                  '<?php echo $alerta->title?>',
                                                                  '<?php echo $alerta->updated?>',
                                                                  '<?php echo $alerta->type?>',
                                                                  '<?php echo $alerta->event?>',
                                                                  '<?php echo $alerta->description?>',
                                                                  '<?php echo $alerta->urgency?>',
                                                                  '<?php echo $area_description?>',
                                                                  '<?php echo $area_type?>',
                                                                  '<?php echo $area_coordinates?>'
                                                                  )">
                                                                  
                                                                  <div class="cap_list_row_icon"></div>
                                                                  <div class="cap_list_row_text_wrapper">
                                                                  
                                                                                <div class="cap_list_title"><?php echo substr($alerta->title,0,30)?>...</div>
                                                                                <div class="cap_list_updated"><?php echo $alerta->updated?></div>
                                                                                <div style="clear:both"></div>
                                                                                <div class="cap_list_alert"><?php echo $alerta->type?></div>
                                                                                <div class="cap_list_alert"><?php echo $alerta->urgency?></div>                                              
                                                                  
                                                                  </div>
                                                                  <!-- cap_list_row_text_wrapper -->
                                                                  
                                                                  
                                                            </div>
                                                            <!-- cap_list_row -->
                                                     <? } // foreach?>
                                                     
                                        </div>
                                        <!-- cap_list_wrapper -->
                    
                              </div>
                              <!-- cap_list -->        
                              
                              <div style="float:left; width:100%; height:auto; display:block; margin-top:40px; padding-bottom:10px; overflow:auto; text-transform:uppercase;">
                                                  <strong>muestra el objeto interpretado del xml de cap de conagua</strong>
                              </div>                                    
                              
                              <div style="float:left; width:100%; height:500px; display:block; background-color:transparent; margin-top:10px; margin-bottom:40px; overflow:auto; text-align:left;" class="border_div">
                              
                                        <pre>
                                        <?php 
										$cap_xml 			= $cap_parser->get_cap($url_conagua);
										print_r($cap_xml)
										
										?>
                                        </pre>
                              
                              </div>                  
                    
                    
     </div>
          <!-- nibsi_alertamx_body_content -->

          <div class="footer_push"></div>

          <div id="nibsi_alertamx_footer_wrapper">
                    <div id="nibsi_alertamx_footer_content">
                              <a href="#"  class="nibsi_alertamx_footer_links">footer 1</a>&nbsp;|&nbsp;
                              <a href="#"  class="nibsi_alertamx_footer_links">footer 2</a>&nbsp;|&nbsp;
                    </div>
          </div>
          <!-- nibsi_alertamx_footer_wrapper -->         
 </div>
 <!-- nibsi_alertamx_page_container -->

</body>
</html>
<script>
var map = L.mapbox.map('map', 'zunware.ipmlddlh').setView([32.62, -117.11], 5);



// Add circle
//L.circle([32.62, -117.11], 10000).addTo(map);

// L.polygon(coords).addTo(map);
// var polyline = L.polyline(coords, {color: '#CCC'}).addTo(map);
// var polygony = L.polygon(coords, {color: 'red'}).addTo(map);

map.fitBounds(coords);
							  
</script>
  <script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  </script>