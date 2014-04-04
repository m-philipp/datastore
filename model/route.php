<?php

//Class for Pre Alpha Navigation functionality

class Route
{


	static $navigation = array(
		array("type" => "link", "link" => "./", "title" => "Home"),
		array("type" => "link", "link" => "./viewList", "title" => "Data"),
		array("type" => "link", "link" => "./impressum", "title" => "Impressum")
	);


	static public function impressum()
	{
		$params = array();
		$params["navigation"] = self::$navigation;
		$params["content"] = getTemplate()->get("impressum.php");
		$params["bp"] = "./";

		getTemplate()->display("layout.php", $params);
	}

	static public function start()
	{
		$params = array();
		$params["navigation"] = self::$navigation;
		$params["content"] = getTemplate()->get("start.php");
		$params["bp"] = "./";

		getTemplate()->display("layout.php", $params);
	}

	static public function viewStream($streamId)
	{
		$params = array();
		$params["navigation"] = self::$navigation;
		$params["content"] = getTemplate()->get("viewStream.php", array("stream" => $streamId));
		$params["bp"] = "./../";

		getTemplate()->display("layout.php", $params);
	}

	static public function view($streamId)
	{
		$params = array();
		$params["streamId"] = $streamId;
		$result = getDatabase()->all('SELECT loggedTime FROM store WHERE streamId=:streamId ORDER BY loggedTime DESC LIMIT 0, 30 ', $params);

		if(count($result) < 2)
			exit; // TODO

		$startTimestamp = $result[0]["loggedTime"] - 3600;
		$endTimestamp = $result[count($result)-1]["loggedTime"] + 3600;


		// 2000/01/01 00:00
		$end = date('Y/m/d H:i', $endTimestamp);
		$start = date('Y/m/d H:i', $startTimestamp);


		$params = array();
		$params["navigation"] = self::$navigation;
		$params["content"] = getTemplate()->get("view.php", array("stream" => $streamId, "start" => $start, "end" => $end, "startTimestamp" => $startTimestamp, "endTimestamp" => $endTimestamp));
		$params["bp"] = "./../";

		getTemplate()->display("layout.php", $params);
	}

	static public function viewList()
	{


		$result = getDatabase()->all('SELECT DISTINCT streamId FROM store ORDER BY streamId ASC');

		$streams = array();
		foreach($result as $val){
			$streams[] = $val["streamId"];
		}
		$params = array();
		$params["navigation"] = self::$navigation;
		$params["content"] = getTemplate()->get("viewList.php", array("streams" => $streams));
		$params["bp"] = "./";

		getTemplate()->display("layout.php", $params);
	}


}

?>
