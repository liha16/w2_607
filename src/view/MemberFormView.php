<?php

namespace View;

require_once("view/IView.php");

class MemberFormView implements IView {

    private static $name = "RegisterMemberView::Name";
    private static $personalNr = "RegisterMemberView::PersonalNr";
    private static $register = "RegisterMemberView::Register";
    private static $memberId = "RegisterMemberView::MemberId";
    private static $edit = "RegisterMemberView::Edit";
    private $isEditMember;


    public function setMemberToEdit(\Model\Member $member) {
        $this->isEditMember = $member;
    }

	/**
	* Generate HTML register form
	*
	* @return string, html form
	*/
	public function getHTML() : string {
        return $this->getTitle() . '
        <form method="post" action="?" enctype="multipart/form-data"> 
                    <label for="' . self::$name . '">Name :</label>
                    <input type="text" minlength="2" maxlength="20" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getEditName() . '" />
                    <br/>
                    <label for="' . self::$personalNr . '">Personal number:</label>
                    <input type="text" minlength="2" maxlength="20" id="' . self::$personalNr . '" name="' . self::$personalNr . '" value="' . $this->getEditPersonalNr() . '" />
                    <br/>'  . $this->getIDfield()  . '<br/>
                    <input type="submit" name="' . $this->getSubmitType() . '" value="Save" />
            </form>
        ';
    }

    /**
	* Get value of member name (only edit)
	*
	* @return string, value of member field
	*/
    public function getEditName() : string {
        if ($this->isEditMember != null) {
            return $this->isEditMember->getName();
        } else {
            return "";
        }
    }

    public function getEditPersonalNr() : string {
        if ($this->isEditMember != null) {
            return $this->isEditMember->getPersonalNr();
        } else {
            return "";
        }
    }

    /**
	* Get title for form
	*
	* @return string
	*/
    public function getTitle() : string  {
        if ($this->isEditMember != null) {
            return "<h2>Edit user</h2>";
        } else {
            return "<h2>Register new user</h2>";
        }
    }

    /**
	* Get form input (hidden) with id of member to edit
	*
	* @return string
	*/
    private function getIDfield() : string { 
        if ($this->isEditMember != null) {
            return '<input type="hidden" id="' . self::$memberId . '" name="' . self::$memberId . '" value="' . $this->memberToEdit() . '">';
        } else {
            return "";
        }
    }

    /**
	* Get name of submit button
	*
	* @return string
	*/
    private function getSubmitType() : string {
        if ($this->isEditMember != null) {
            return self::$edit;
        } else {
            return self::$register;
        }
    }


    public function isRegisterFormPosted() : bool {
        return isset($_POST[self::$register]);
    }
    public function isEditFormPosted() : bool {
        return isset($_POST[self::$edit]);
    }

    public function memberToEdit() : int {
        return $_GET["editMember"];
    }

    public function getName() : string{
        return $this->filterInput($_POST[self::$name]);
    }

    public function getPersonalNr() : int {
        return $this->filterInput($_POST[self::$personalNr]);
    }

    public function getEditId() : int {
        return $this->filterInput($_POST[self::$memberId]);
    }

    private function filterInput(string $input) : string {
        return trim(preg_replace('/[^a-zA-Z0-9\s]/', '',$input));	
    }
}

?>