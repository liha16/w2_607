<?php


namespace Model;

class Member {

    private $name;
    private $personalNr;
    private $id;
    private $boats = [];

    
	public function __construct(string $name, int $personalNr) {
        $this->name = $name;
        $this->personalNr = $personalNr;
    }

    public function setId(int $memberID) {
        if(!is_numeric($memberID)){
            throw new \Exception('Not a valid member id'); // further validation in boatlist
        }
        $this->id = $memberID;
    }
    public function setBoats(array $boats) {
        foreach ($boats as $boat) {
            array_push($this->boats, $boat);
        }  
    }

    public function addBoat(int $boat) {
        array_push($this->boats, $boat);
    }

    public function deleteBoat(int $boat) {
        unset($this->boats[$boat]);
    }

    public function setName(string $name) {
        $this->name = $name;
    }
    public function setPersonalNr(int $nr) {
        $this->personalNr = $nr;
    }

    public function getMember() : array {
        return get_object_vars($this);
    }

    public function getName() : string {
        return $this->name;
    }

    public function getId() : int {
        return $this->id;
    }

    public function getBoats() : array {
        return $this->boats;
    }

    public function getPersonalNr() : int {
        return $this->personalNr;
    }

}
