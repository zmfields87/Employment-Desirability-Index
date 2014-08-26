<? 

$jobTitle = $_REQUEST['job'];


try 
{
		$db = new PDO("mysql:host=localhost;dbname=wp_hczander","censored","censored");
}	
	catch (Exception $e) 
		{
			echo "Could not connect to database.";
			exit;
		}


	
$stmt = $db->prepare("SELECT DISTINCT
	Rank,
	AREA_NAME,  
	PRIM_STATE,
	TOT_EMP,
	JOBS_1000,
	A_MEAN
	FROM EDI_Top5 
	WHERE OCC_TITLE = '$jobTitle'
	ORDER BY Rank ASC");
	

	

	if ($stmt->execute()) 
	{
		while ($rows = $stmt->fetch(PDO::FETCH_ASSOC))
		{		

echo "<li class='topAREA_NAME'>" . $rows['AREA_NAME'] . "</li>";

echo "<li class='topPRIM_STATE'>" . $rows['PRIM_STATE'] . "</li>";

echo "<li class='topTOT_EMP'>" . $rows['TOT_EMP'] . " jobs" . "</li>";

echo "<li class='topJOBS_1000'>" . $rows['JOBS_1000'] . "</li>";

echo "<li class='topA_MEAN'>" . "$" . $rows['A_MEAN'] . "</li>";



	}
}

	
?>