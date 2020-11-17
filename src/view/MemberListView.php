<?php

namespace View;

require_once("view/IView.php");
require_once("view/BoatView.php"); // generate html list with boats
require_once("model/BoatList.php"); // All Boats ( storage )



class MemberListView implements IView {

	private $memberList;
	private static $verboseList = "v";
	private $typeofList = "c"; // by default compact list
	private static $viewMember= "viewMember";

	public function __construct(\Model\MemberList $memberList) {
		$this->memberList = $memberList->getMembersList();
		$this->setListType();
	}

	public function getHTML() : string {
		if ($this->typeofList == self::$verboseList) {
			$html = $this->getVerboseList();
		} else {
			$html = $this->getCompactList();
		}
		return $html;
	}

	private function getCompactList() : string {
		$html = "<a href='?listtype=v'>Verbose list</a>";
		$html .= "<table class='memberlist'>";
		$html .= "<tr><th>Id</th><th>Namn</th><th>Number of boats</th></tr>";
		foreach ($this->memberList as $member => $value) {
			$html .=  "<tr>";
			$html .= "<td>" . $value->getId() . "</td>";
			$html .= "<td>" . $value->getName() . "</td>";
			$html .= "<td>" . count($value->getBoats()) . "</td>";
		}
		$html .= "</tr>";
		return $html;
	}

	private function getVerboseList() : string {
		$boatList = new \Model\BoatList();
		$html = "<a href='?'>Compact list</a>";
		$html .= "<table class='memberlist'>";
		$html .= "<tr><th>Id</th><th>Namn</th><th>Personal number</th><th>Boats</th></tr>";
		foreach ($this->memberList as $member => $value) {
			$html .=  "<tr><td>" . $value->getId() . "</td>";
			$html .= "<td><a href='?" . self::$viewMember . "=" . $value->getId() . "'>" . $value->getName() . " &#128065; </a></td>";
			$html .= "<td>" . $value->getPersonalNr() . "</td><td>";
			foreach ($value->getBoats() as $boatId => $id) {
				$boatView = new \View\boatView();
				$boatView->setBoatList($boatList->getBoatById($id));
				$html .= $boatView->getHTML() . "<br>";
			}
			$html .= "</td></tr>";
		}
		return $html;
	}
	
	private function setListType() {
		if (isset($_GET["listtype"]) && $_GET["listtype"] == self::$verboseList) {
			$this->typeofList = self::$verboseList;
		}
	}

	public function isPageViewMember() : bool {
        return isset($_GET[self::$viewMember]);
	}

	public function getMember() : int {
        return $_GET["viewMember"];
	}

	public function updateMemberList(\Model\MemberList $memberList) {
		$this->memberList = $memberList->getMembersList();
		//$this->memberList2 = $memberList->getMembersList2();
	}
	

	

}

?>