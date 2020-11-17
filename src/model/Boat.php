<?php

namespace Model;

class Boat {

    private $type;
    private $memberId;
    private $id;
    private $length;
    private static $maxLength = 25;
    private static $boatTypes = ["Sailboat", "Motorsailer", "Kayak", "Other"];

    
	public function __construct(string $type, float $len, int $memberid) {
        $this->setType($type);
        $this->setLength($len);
        $this->setMemberId($memberid);
    }

    public function setType(string $type) {
        if(!in_array($type, self::$boatTypes)){
            throw new \Exception('Not allowed boat type!');
        }
        $this->type = $type;
    }

    public function setLength(float $length) {
        if($length < 0 || $length > self::$maxLength){
            throw new \Exception($length . 'is not a valid lenght!');
        }
        $this->length = $length;
    }

    public function setMemberId(int $memberID) {
        if(!is_numeric($memberID)){
            throw new \Exception('Not a valid member id'); // further validation in boatlist
        }
        $this->memberId = $memberID;
    }

    public function setBoatId($id) { 
        $this->id = $id;
    }

    public function createBoatId() { 
        $this->id = intval($this->memberId . rand());
    }

    public function getId() : int { 
        return $this->id;
    }

    public function getType() : string { 
        return $this->type;
    }

    public function getLen() : float { 
        return $this->length;
    }

    public function getMemberId() : int {
        return $this->memberId;
    }

    public function getBoat() : array {
        return get_object_vars($this);
    }

}


?>