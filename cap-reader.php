<?php

class LL_CAP
{
	private function downloadXML($url)
	{
		return file_get_contents($url);
	}
	
	public function get_cap( $url )
	{
		$xml = simplexml_load_string($this->downloadXML($url));	
		
		return($xml);
	}
	

	public function get( $url )
	{
		$xml = simplexml_load_string($this->downloadXML($url));

		$myCap = new stdClass();
		$myCap->title 	= $xml->title;
		$myCap->updated = $xml->updated;

		$myCap->alertas = array();


		foreach ($xml->entry as $entry)
		{
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

							array_push($la_alerta->areas, $my_area);
						}

						// echo "\t\tEvent    = " . $info->event       . "\n";
						// echo "\t\tHeadline = " . $info->headline    . "\n";
						// echo "\t\tDesc     = " . $info->description . "\n\n";
					}
				}
			}

			array_push($myCap->alertas, $la_alerta);
		}

		return($myCap);
	}
}

?>