<?php

// Bienvenido a nuestro código =)
// Creado para Nibsi por: jose@josellausas en Julio 2014
// Este archivo vive en:  https://github.com/zunware/RetoMXDemo

// Clase que encapsula nuestra herramienta de parsing de CAP
class LL_CAP
{

	// Descarga el contenido de un URL
	private function downloadXML($url)
	{
		return file_get_contents($url);
	}
	
	// Devuelve el contenido XML en un objeto PHP
	public function get_cap( $url )
	{
		$xml = simplexml_load_string($this->downloadXML($url));	
		
		return($xml);
	}
	

	// Devuelve un objeto más limpio y con nuestro propio formato.
	public function get( $url )
	{
		$xml = simplexml_load_string($this->downloadXML($url));

		// Una nueva clase para guardar los datos interesantes.
		$myCap = new stdClass();


		$myCap->title 	= $xml->title;
		$myCap->updated = $xml->updated;

		// Aqui se guardan todas las alertas
		$myCap->alertas = array();


		// Navegar el arbol XML y obtener los datos interesantes.
		foreach ($xml->entry as $entry)
		{
			// Almacena todos los datos pertinentes a una instancia de alerta
			$la_alerta = new stdclass();

			$la_alerta->title 	= $entry->title;
			$la_alerta->updated = $entry->updated;

			foreach ($entry->content as $content)
			{
				foreach ($content->alert as $alert)
				{
					$la_alerta->type = $alert->msgType;
					$la_alerta->status = $alert->status;

					foreach($alert->info as $info)
					{
						$la_alerta->event = $info->event;
						$la_alerta->headline =$info->headline;
						$la_alerta->description = $info->description;
						$la_alerta->urgency = $info->urgency;
						$la_alerta->responseType = $info->responseType;

						$la_alerta->areas = array();

						// El entry puede tener mas de un area.
						foreach ($info->area as $area)
						{
							$my_area = new stdClass();
							$my_area->description = $area->areaDesc;

							if($area->polygon)
							{
								$my_area->type = "poly";
								$my_area->data = $area->polygon;
							}
							elseif($area->circle)
							{
								$my_area->type = "circle";
								$my_area->data = $area->circle;
							}

							// Inserta esta area a el arreglo de areas
							array_push($la_alerta->areas, $my_area);
						}
					}
				}
			}

			// Inserta esta alerta a el arreglo de alertas
			array_push($myCap->alertas, $la_alerta);
		}

		return($myCap);
	}
}

?>