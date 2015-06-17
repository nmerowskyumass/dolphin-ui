<?php

class FastlaneController extends VanillaController {
	private $username;
	
	function beforeAction() {

	}
 
	function index() {
		$this->set('field', "Fastlane");
		$this->username=$_SESSION['user'];
		$this->set('title','NGS Fastlane');
		
		$this->set('uid', $_SESSION['uid']);
        $gids = $this->Fastlane->getGroup($_SESSION['user']);
        $this->set('gids', $gids);
	}
	
	function process() {
		$this->set('uid', $_SESSION['uid']);
        $gids = $this->Fastlane->getGroup($_SESSION['user']);
        $this->set('gids', $gids);
		
		$text = '';
		$bad_samples = '';
		if(isset($_SESSION['fastlane_values'])){$fastlane_values = $_SESSION['fastlane_values'];}
		if(isset($_SESSION['barcode_array'])){$barcode_array = $_SESSION['barcode_array'];}
		if(isset($_SESSION['pass_fail_values'])){$pass_fail_values = $_SESSION['pass_fail_values'];}
		if(isset($_SESSION['bad_samples'])){$bad_samples = $_SESSION['bad_samples'];}
		
		$fastlane_array = explode(",",$fastlane_values);
		$pass_fail_array = explode(",",$pass_fail_values);
		$bad_samples_array = explode(",",$bad_samples);
		
		$fastlane_values = str_replace("\n", ":", $fastlane_values);
		
		if($pass_fail_array[0] == "true" || $pass_fail_array == "false"){
			$text.= "<h4>Errors found during submission:</h4><br>";
		}else{
			$text.= "<h4>Successful Fastlane submission!</h4><br>";
			$text.= "Don't forget to add more information about your samples!<br><br>";
			$text.="<script type='text/javascript'>";
			$text.="var initialSubmission = '" . $fastlane_values . "';";
			if(isset($_SESSION['barcode_array'])){$text.="var barcode_array = '" . $barcode_array . "';";}
			$text.="</script>";
		}
		$database_sample_bool = false;
		foreach($pass_fail_array as $key=>$index){
			if($index == 'false'){
				if($key == 1){
					$text.="Barcode selection is either empty or not properly formatted<br>";
				}else if($key == 3){
					$text.="Experiment Series field is empty<br>";
				}else if($key == 4){
					$text.="Experiment field is either empty or contains improper white space<br>";
				}else if($key == 5){
					$text.="Input Directory is either empty or contains improper white space<br>";
				}else if($key == 6){
					$text.="Input files are either empty or do not fit the correct format for the current selection<br>";
				}else if($key == 7){
					$text.="Backup directory is either empty or contains improper white space<br>";
				}else if($key >= 9){
					$database_sample_bool = true;
				}
			}else if($index != 'true' && $index != 'false'){
				$text.= "Sample created with id #".$index."<br>";
				$text.='<h4>We are currently processing your samples to obtain read counts and additional information.<br>
                            You can check the status of these initial runs on your NGS Status page.</h4>';
			}
		}
		if($database_sample_bool == true){
			$text.="Samples given are already contained within the database:<br>";
			foreach($bad_samples_array as $bad){
				$text.="Sample with name: ".$bad."<br>";
			}
		}
		
		$text.="<br>";
		
		if($pass_fail_array[0] == "true" || $pass_fail_array == "false"){
			$text.= '<div>
				<input type="button" class="btn btn-primary" value="Return to Fastlane" onclick="backToFastlane()">
				<input type="button" class="btn btn-primary" value="Send to Status" disabled="true" onclick="">
				</div>';
		}else{
			$text.= '<div>
				<input type="button" class="btn btn-primary" value="Return to Fastlane" onclick="sentToNewFastlane()">
				<input type="button" class="btn btn-primary" value="Send to Status" onclick="sendToStatus()">
				</div>';
		}
		
		$this->set('mytext', $text);
		unset($_SESSION['fastlane_values']);
		unset($_SESSION['bar_distance']);
		unset($_SESSION['pass_fail_values']);
		unset($_SESSION['bad_samples']);
	}
	
	function afterAction(){
		
	}
}