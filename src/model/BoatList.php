<?php

namespace Model;

class BoatList {

    private $boatsFile = "model/db/boats.json";
    private $boats = array(); // TODO CLASS DIAGRAM AN ARRAY OF BOATS ON KLASS LEVEL REFERENCE ?
    
	public function __construct() {
        if (file_exists($this->boatsFile)) {
            $jsonContents = file_get_contents($this->boatsFile);
            $this->makeObjectsOfSavedBoats(json_decode($jsonContents, true));
          } else {
              throw new \Exception("No file " . $this->boatsFile . " found on server");
              // Never catched but will cause and error with info
          }
    }

    /**
	* Creates objects of all previosly saved boats in db
	*
    * @return void, but changes variables
	*/
    public function makeObjectsOfSavedBoats($boatsInFile){
        foreach ($boatsInFile as $key => $boat) {
            $boatToAdd = new Boat($boat["type"], $boat["length"], $boat["memberId"]);
            $boatToAdd->setBoatId($boat["id"]);
            array_push($this->boats, $boatToAdd);       
        }
    }

    /**
	* Add new boat
	*
	* @return void, but writes to file
	*/
    public function addBoat(\Model\Boat $newBoat) {
        $newBoat->createBoatId();
        array_push($this->boats, $newBoat);
        $this->saveFileAsJson();
    }

    /**
	* Saves members to file after making them json
	*
	*/
    public function saveFileAsJson(){
        $boatsAsJson = [];
        foreach ($this->boats as $key => $value) { // Get each objects data
            array_push($boatsAsJson, $value->getBoat());
        }
        file_put_contents($this->boatsFile, json_encode($boatsAsJson));
    }

    /**
    * Returns one boat with certain id
    *
    * @return \Model\Boat $newBoat
	*/
    public function getBoatById($boatId) { 
        foreach ($this->boats as $boat => $value) {
            if ($value->getId() == $boatId) {
                return $value;
            }
        }        
    }

    public function editBoat(string $type, float $length, int $boatId) {
        foreach ($this->boats as $boat => $value) {
            if ($boatId == $value->getId()) {
                $value->setType($type);
                $value->setLength($length);
            }
        }
        
        $this->saveFileAsJson();
    }

    public function deleteBoat(int $id) {
        if (is_numeric($id)) {
            foreach ($this->boats as $boat => $value) {
                if ($id == $value->getId()) {
                    unset($this->boats[$boat]);
                }
            }
            $this->saveFileAsJson();
        } else {
            echo "Not a valid number";
            // THROW EXCEPTION?
        }
    }

}

?>