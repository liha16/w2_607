<?php

namespace View;

require_once("view/IView.php");

class BoatView implements IView{

	private $boat;
	private static $editBoat= "editBoat";
	private static $deleteBoat= "deleteBoat";


	public function setBoatList(\Model\Boat $boat) {
		$this->boat= $boat;
	}

	public function getHTML() : string {
		$html = "Type: " . $this->boat->getType();
		$html .= ", Length: " . $this->boat->getLen();
		$html .= ", Id: " . $this->boat->getId();
		$html .= " <a href='?editBoat=" . $this->boat->getId() . "'>&#9997;</a>";
		$html .= " <a href='?deleteBoat=" . $this->boat->getId() . "'>&#128465;</a>";
		return $html;
	}

    public function isPageEditBoat() : bool {
        return isset($_GET[self::$editBoat]);
	}
	public function isPageDeleteBoat() : bool {
        return isset($_GET[self::$deleteBoat]);
	}
	
	public function getBoatToEdit() : int {
        return $_GET[self::$editBoat];
	}
	public function getBoatToDelete() : int {
        return $_GET[self::$deleteBoat];
    }
	
}

?>