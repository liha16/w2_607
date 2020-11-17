<?php

namespace Model;

class MemberList {

    private $membersFile = "model/db/members.json";
    private $members = array();
    
	public function __construct() {
        if (file_exists($this->membersFile)) {
            $jsonContents = file_get_contents($this->membersFile);
            $this->makeObjectsOfSavedMembers(json_decode($jsonContents, true));
        } else {
              throw new \Exception("No file " . $this->membersFile . " found on server");
              // Never catched but will cause and error with info
        }
    }

    /**
	* Creates objects of all previosly saved members in db
	*
	* @return void
	*/
    public function makeObjectsOfSavedMembers($membersInFile){
        foreach ($membersInFile as $key => $member) {
            $memberToAdd = new Member($member["name"], $member["personalNr"]);
            $memberToAdd->setId($member["id"]);
            $memberToAdd->setBoats($member["boats"]);
            array_push($this->members, $memberToAdd);
        }
    }

    /**
	* Return array with all members
	*
	* @return array
	*/
    public function getMembersList() : array {
        return $this->members;
    }

    /**
	* Add new member and save
	*
	* @return void
	*/
    public function registerMember(\Model\Member $member) {
        $member->setId($this->generateNewMemberID());       
        array_push($this->members, $member);
        $this->saveFileAsJson();
    }

    /**
	* Generates new member ID, based on last ID in db
	*
	* @return int
	*/
    public function generateNewMemberID() : int {
        $lastMember = end($this->members);
        return $lastMember->getId() + 1;
    }

    /**
	* Saves members to file after making them json
	*
	*/
    public function saveFileAsJson(){
        $membersAsJson = [];
        foreach ($this->members as $key => $value) { // Get each objects data
            array_push($membersAsJson, $value->getMember());
        }
        file_put_contents($this->membersFile, json_encode($membersAsJson));
    }

    public function deleteMember(int $id) {
        if (is_numeric($id)) {
            foreach ($this->members as $member => $value) {
                if ($id == $value->getId()) {
                    unset($this->members[$member]);
                }
            }
            $this->saveFileAsJson();
        } else {
            echo "Not a valid number";
            // THROW EXCEPTION?
        }
    }

    public function editMember(int $id, string $name, int $personalNr) {
        if (is_numeric($id)) {
            foreach ($this->members as $member => $value) {
                if ($id == $value->getId()) {
                    $value->setName($name);
                    $value->setPersonalNr($personalNr);
                }
            }
            $this->saveFileAsJson();
        } else {
            echo "Not a valid number";
            // THROW EXCEPTION?
        }
    }

    public function getMember($id) { // Cant type hint return type as its a \Model\Member
        foreach ($this->members as $member => $value) {
            if ($id == $value->getId()) {
                return $value;
            }
        }
    }

    /**
	* Add new boat to member
	*
	* @return void, but writes to file
	*/
    public function addBoat(\Model\Boat $boat) {
        foreach ($this->members as $member => $value) {
            if ($boat->getMemberId() == $value->getId()) {
                $value->addBoat($boat->getId());
            }
        }
        $this->saveFileAsJson();
    }

    /**
	* Eliminate boat from member
	*
	* @return void, but writes to file
	*/
    public function deleteBoat(int $boatIdToDelete) {
        foreach ($this->members as $member => $value) {
            foreach ($value->getBoats() as $boatId => $id) {
                if ($id == $boatIdToDelete) {
                    $value->deleteBoat($boatId);
                }
			}
        }
        $this->saveFileAsJson();
    }
}
