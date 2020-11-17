<?php

namespace View;

require_once("view/IView.php");

class BoatRegisterView implements IView {

    private static $length = "RegisterBoatView::Length";
    private static $type = "RegisterBoatView::Type";
    private static $register = "RegisterBoatView::Register";
    private static $edit = "RegisterBoatView::Edit";
    private static $member = "RegisterBoatView::Member";
    private static $boatId = "RegisterBoatView::BoatId";
    private $boatTypes = ["Sailboat", "Motorsailer", "Kayak", "Other"];
    private $memberList = array();
    private $boatToEdit = null;

	public function __construct(\Model\MemberList $memberList) {
        $this->memberList = $memberList->getMembersList();
	}

	/**
    * Generate HTML add boat form 
	*
	* @return string, html form
	*/
	public function getHTML() : string {
        return '
        <form method="post" action="?register" enctype="multipart/form-data"> 
                <fieldset>
                    ' . $this->getFormTitle() . '
                    ' . $this->getMemberOptionList() . '
                    <br/>
                    ' . $this->getBoatTypeOptionList() . '
                    <br/>
                    <label for="' . self::$length . '">Length in meter (ex 1,5):</label>
                    <input type="number" step="0.1" minlength="0" maxlength="25" id="' . self::$length . '" 
                        name="' . self::$length . '" value="' . $this->getSelectedLen() . '" />
                    <br/>'  . $this->getIDfield()  . '<br/>
                    <input type="submit" name="' . $this->getSubmitType() . '" value="Save" />
                </fieldset>
            </form>
        ';
    }

    /**
	* Get title for form
	*
	* @return string
	*/
    private function getFormTitle() : string {
        if ($this->boatToEdit != null) {
            return "<h2>Edit a boat</h2>";
        } else {
            return "<h2>Add a new boat</h2>";
        }
    }

    /**
	* Get name of submit button
	*
	* @return string
	*/
    private function getSubmitType() : string {
        if ($this->boatToEdit != null) {
            return self::$edit;
        } else {
            return self::$register;
        }
    }

    // Generates option list for to choose owner of boat
    private function getMemberOptionList() : string {
        $html = '<label for="' . self::$member . '">Owner (member):</label>
                <select id="' . self::$member . '" name="' . self::$member . '"' . $this->disableOwner() . '>';
        foreach ($this->memberList as $member => $value) {
			$html .=  "<option value='" . $value->getId() . "'" . $this->selectedOwner($value->getId()) . " >";
			$html .= $value->getId() . ": " . $value->getName();
			$html .= "</option>";
        }
        $html .= "</select>";
        return $html;
    }

    /**
	* Get form input (hidden) with id of boat to edit
	*
	* @return string
	*/
    private function getIDfield() : string { 
        if ($this->boatToEdit != null) {
            return '<input type="hidden" id="' . self::$boatId . '" name="' . self::$boatId . '" value="' . $this->boatToEdit->getId() . '">';
        } else {
            return "";
        }
    }

    // If edit, mark owner as selected
    private function selectedOwner(int $id) : string { 
        if ($this->boatToEdit != null) {
            if ($this->boatToEdit->getMemberId() == $id) {
                return "selected";
            }
        }
        return "";
    }

    private function disableOwner() : string { 
        if ($this->boatToEdit != null) {
            return "disabled";
        } else {
            return "";
        }
    }

    // If edit, mark type as selected
    private function selectedType($type) : string { 
        if ($this->boatToEdit != null) {
            if ($this->boatToEdit->getType() == $type) {
                return "selected";
            }
        }
        return "";
    }

    // If edit, put lenght
    private function getSelectedLen() : string{ 
        if ($this->boatToEdit != null) {
            return $this->boatToEdit->getLen();  
        } else {
            return "";
        }   
    }


    // Generates option list for allowed types of boat
    private function getBoatTypeOptionList() : string {
        $html = '<label for="' . self::$type . '">Boat type:</label>
                <select id="' . self::$type . '" name="' . self::$type . '">';
        foreach ($this->boatTypes as $type) {
			$html .=  "<option value='" . $type . "'" . $this->selectedtype($type) . ">";
			$html .= $type;
			$html .= "</option>";
        }
        $html .= "</select>";
        return $html;
    }

    public function isRegisterFormPosted() : bool {
        return isset($_POST[self::$register]);
    }

    public function isEditFormPosted() : bool {
        return isset($_POST[self::$edit]);
    }

    public function getType() : string {
        return $this->filterInput($_POST[self::$type]);
    }

    public function getLength() : float {
        return $_POST[self::$length];
    }

    public function getOwnerId() : int {
        return $this->filterInput($_POST[self::$member]);
    }
    public function getBoatId() : int {
        return $this->filterInput($_POST[self::$boatId]);
    }

    private function filterInput(string $input) : string {
        return trim(preg_replace('/[^a-zA-Z0-9\s]/', '',$input));	
    }

    public function setBoatToEdit(\Model\Boat $boat){
        $this->boatToEdit = $boat;
    }


	
}

?>