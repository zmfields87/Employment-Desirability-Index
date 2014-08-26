<? 
$job=$_REQUEST['job'];

try 
{
	$db = new PDO("mysql:host=localhost;dbname=wp_hczander","censored","censored");
}	catch (Exception $e) 
	{
		echo "Could not connect to database.";
		exit;
	}
		
$stmt = $db->prepare("SELECT DISTINCT PRIM_STATE 
		FROM EDI_ByState 
		WHERE OCC_TITLE='$job'
		ORDER BY PRIM_STATE ASC");
		
	

?>
<select id="select2" class="required" name="state" onChange="getStateData(this.value,'<?=$job?>')">
<option value="">Select a State</option>
<? if ($stmt->execute()) {
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
<option value='<?=$row['PRIM_STATE']?>'><?=$row['PRIM_STATE']?></option>
<? } }?>
</select>
