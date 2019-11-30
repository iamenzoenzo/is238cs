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

    $sqlQuery = "SELECT Phone_numbers.phone_number FROM ".$this->phoneNumbersTable.
    " WHERE  city_code='".$City_Code."'";
    $result = mysqli_query($this->dbConnect, $sqlQuery);
    $data= array();
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$data[]=$row;
	}
	return $data;
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
}
