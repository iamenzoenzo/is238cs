<?php
date_default_timezone_set('Asia/Manila');

class PhoneNumbers extends Database {
    private $phoneNumbersTable = 'Phone_numbers';
    private $dbConnect = false;

public function __construct(){
    $this->dbConnect = $this->dbConnect();
}

public function savePhoneNumber($Phone_Number,$City_Code,$City_Name,$Agency_Code,$Created_By) {

    $Created_Date = date("Y/m/d H:i:s", strtotime('now'));
    $sqlQuery = "INSERT INTO ".$this->phoneNumbersTable." (phone_number,city_code,city_name,agency_code,created_by,created_date)
                VALUES('".$Phone_Number."', '".$City_Code."','".$City_Name."','".$Agency_Code."','".$Created_By."','".$Created_Date."');";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    return $result;
}

public function getPhoneNumberByCityCode($City_Code){

    $sqlQuery = "SELECT Phone_numbers.phone_number,Phone_numbers.agency_code FROM ".$this->phoneNumbersTable.
    " WHERE  city_code='".$City_Code."'";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    $data= array();
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$data[]=$row;
	}
	return $data;
}

public function getCityNameByCityCode($City_Code){
    
    $sqlQuery = "SELECT Phone_numbers.city_name FROM ".$this->phoneNumbersTable.
    " WHERE Phone_numbers.city_code='".$City_Code."';";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    $row = mysqli_fetch_array($result);
    return $row['city_name'];
    

}

public function getPhoneNumberByAgencyCode($City_Code,$Agency_Code){

    $sqlQuery = "SELECT Phone_numbers.phone_number FROM ".$this->phoneNumbersTable.
    " WHERE  city_code='".$City_Code."' AND agency_code='".$Agency_Code."'";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    $data= array();
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$data[]=$row;
	}
	return $data;
}
public function getCityCodes(){

    $sqlQuery = "SELECT DISTINCT Phone_numbers.city_code FROM ".$this->phoneNumbersTable." ORDER BY city_code DESC;";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    $data= array();
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$data[]=$row;
	}
	return $data;
}

public function getAgencyCodes(){

    $sqlQuery = "SELECT DISTINCT Phone_numbers.agency_code FROM ".$this->phoneNumbersTable." ORDER BY agency_code ASC;";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    $data= array();
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$data[]=$row;
	}
	return $data;
}

public function validCityCode($City_Code){

    $sqlQuery = "SELECT DISTINCT Phone_numbers.city_code FROM ".$this->phoneNumbersTable.
    " WHERE city_code='".$City_Code."' ORDER BY city_code ASC;";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    $count = mysqli_num_rows($result);
	if($count!=0){
		return true;
	}else{
		return false;
	}
}

public function validAgencyCode($Agency_Code){

    $sqlQuery = "SELECT DISTINCT Phone_numbers.agency_code FROM ".$this->phoneNumbersTable.
    " WHERE agency_code='".$Agency_Code."' ORDER BY agency_code ASC;";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    $count = mysqli_num_rows($result);
	if($count!=0){
		return true;
	}else{
		return false;
	}
}


}
