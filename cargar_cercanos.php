<?php 
 $latitud_actual=$_POST['lat'];
 $longitud_actual=$_POST['log'];
 
 
 
 $distancia_n=$_POST['distancia']*1;//esta en metros
 
 /*Necesitaba pasarlos porque no los reconocia como numeros*/
 $longitud_actual = $longitud_actual*1; // Normalizamos número
 $latitud_actual = $latitud_actual*1; // Normalizamos número
 /**/

 
 /* Se supone que con este foreach cogere solo los que esten dentro del radio:
	DEJO ESTO PARA UNA FUTURA CONSULTA EN LA BASE DE DATOS 
	http://www.michael-pratt.com/blog/7/Encontrar-Lugares-cercanos-con-MySQL-y-PHP/
*/

 //TEMPORAL PARA PRUEBAS
 $direcciones= array(
   '0' => array(
		'nombre'=> 'BRETXA',
		'lat' => 43.323128,
		'lon' =>  -1.982442
	   ),
	'1' => array(
		'nombre'=> 'EROSKI GROS',
		'lat' =>  43.323089,
		'lon' =>  -1.972229
	
	   ),
	'2' => array(
		'nombre'=> 'Mercado san Martin',
		'lat' => 43.318112, 
		'lon' =>  -1.983197
	   )		   
 );


 /*esto problablemte lo pueda hacer desde la consulta de sitios en la misma bd*/
	function sitios_dentro_de_radio($sitios ,$lat_actual,$lon_actual,$radio){
		$earth = 6371 ; //Kilometros
		$km = 111.302;
		$degtorad = 0.01745329;
		//1 Radian = 57.29577951 Grados
		$radtodeg = 57.29577951; 
		$lugares_dentro_radio=array();
		
		foreach($sitios as $k => $destino){
				 //Point 1 cords
			

				//Point 2 cords
				$destino_lat =$destino['lat'];
				$destino_lon= $destino['lon'];
				//Haversine Formula
				$dlong=$destino_lon-$lon_actual;
				$dlat=$destino_lat-$lat_actual;
				
				 
				$dvalue = (sin($lat_actual * $degtorad) * sin($destino_lat * $degtorad)) + (cos($lat_actual  * $degtorad) * cos($destino_lat * $degtorad) * cos($dlong * $degtorad)); 
				$dd = acos($dvalue) * $radtodeg; 
				$distancia = round(($dd * $km), 2) *1000;
				
				if(!isset($dist_menor)){
					 $dist_menor = $distancia;
				}
				if($distancia <= $dist_menor ){
					$dist_menor = $distancia;
					$lugares_dentro_radio['cercano']= $sitios[$k];
				}
				 
				/*guardamos los que estan dentro del radio*/
				if($distancia <= $radio ){
					
					$lugares_dentro_radio['sitios'][]= $sitios[$k];	
				}			

			}
			$lugares_dentro_radio['numero']= count($lugares_dentro_radio['sitios']);
			
			return $lugares_dentro_radio ;
	}
	
 $res = sitios_dentro_de_radio($direcciones,$latitud_actual,$longitud_actual,$distancia_n);

 
 echo json_encode($res);  

?>