<?php

namespace View;

require_once("view/IView.php");

class MemberView implements IView {

	private $member;
	private static $editMember= "editMember";
	private static $deleteMember= "deleteMember";

	// Returns html of member

	public function setMember(\Model\Member $member){
		$this->member = $member;
	}

	public function getHTML() : string {
		$html = "<table class='memberlist'>";
		$html .= "<tr><th>Id</th><th>Namn</th><th>Personal NR</th><th>Number of boats</th><th>Edit</th><th>Delete</th></tr>";
        $html .= "<tr><td>" . $this->member->getId() . "</td>";
        $html .= "<td>" . $this->member->getName() . "</td>";
        $html .= "<td>" . $this->member->getPersonalNr() . "</td>";
        $html .= "<td>" . count($this->member->getBoats()) . "</td>";
        $html .= "<td><a href='?" . self::$editMember . "=" . $this->member->getId() . "'>Edit</a></td>";
        $html .= "<td><a href='?" . self::$deleteMember . "=" . $this->member->getId() . "'>Delete</a></td>";
		$html .= "</tr>";
		return $html;
	}

	public function isPageEditMember() : bool {
        return isset($_GET[self::$editMember]);
    }

    public function isPageDeleteMember() : bool {
        return isset($_GET[self::$deleteMember]);
	}
	
	public function getMemberToEdit() : int {
        return $_GET[self::$editMember];
	}
	public function getMemberToDelete() : int {
        return $_GET[self::$deleteMember];
    }
}

?>