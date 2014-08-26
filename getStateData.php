<?php
	
$state = $_REQUEST['state'];
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
	FROM EDI_ByState 
	WHERE PRIM_STATE='" . $state . "' 
	AND OCC_TITLE='" . $jobTitle . "'
	ORDER BY Rank ASC");
	

		

	if ($stmt->execute()) 
	{
		
		
		while ($rows = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			
			
			echo "<li class='stateRank'>" . $rows['Rank'] . "</li>";
			
			echo "<li class='stateAREA_NAME'>" . $rows['AREA_NAME'] . "</li>";
			
			echo "<li class='statePRIM_STATE'>" . $state . "</li>";
			
			echo "<li class='stateTOT_EMP'>" . $rows['TOT_EMP'] . " jobs" . "</li>";

			echo "<li class='stateJOBS_1000'>" . $rows['JOBS_1000'] . "</li>";

			echo "<li class='stateA_MEAN'>" . "$" . $rows['A_MEAN'] . "</li>";

			

	
		}
		
	}
	

	
?>