<?php


namespace Controller;

require_once("view/MemberListView.php"); // shows members, generate html list
require_once("view/MemberView.php"); 
require_once("view/MemberFormView.php"); 
require_once("view/BoatRegisterView.php"); 
require_once("view/BoatView.php"); 
require_once("view/PageLayoutView.php"); 
require_once("model/MemberList.php"); // All members ( storage )
require_once("model/Member.php");
require_once("model/Boat.php");
require_once("model/BoatList.php");

class Router {

    private $memberList;
    private $memberListView;
    private $boatList;
    private $boatView;
    private $memberRegisterView;
    private $boatRegisterView;
    private $pageLayoutView;
    private $memberView;

    public function __construct() {
        $this->memberList = new \Model\MemberList();
        $this->boatList = new \Model\BoatList();
        $this->memberRegisterView = new \View\MemberFormView();
        $this->boatRegisterView = new \View\BoatRegisterView($this->memberList);
        $this->pageLayoutView = new \View\PageLayoutView();
        $this->memberView = new \View\MemberView();
        $this->boatView = new \View\BoatView();
        $this->memberListView = new \View\MemberListView($this->memberList);
    }

    /**
	* Router checks if form is send or parameters are sent in url and routes
	*
	* @return View, view to be used on pag
	*/
    public function run() {

        // Post router
        if ($this->memberRegisterView->isRegisterFormPosted()) {
            $newMember = new \Model\Member($this->memberRegisterView->getName(), $this->memberRegisterView->getPersonalNr());
            $this->memberList->registerMember($newMember);
        }
        if ($this->memberRegisterView->isEditFormPosted()) {
            $this->memberList->editMember($this->memberRegisterView->getEditId(), $this->memberRegisterView->getName(), $this->memberRegisterView->getPersonalNr());
        }
        if ($this->boatRegisterView->isRegisterFormPosted()) {
            $boat = new \Model\Boat($this->boatRegisterView->getType(), $this->boatRegisterView->getLength(), $this->boatRegisterView->getOwnerId());
            $this->boatList->addBoat($boat);
            $this->memberList->addBoat($boat);
        }
        if ($this->boatRegisterView->isEditFormPosted()) {
            $this->boatList->editBoat($this->boatRegisterView->getType(), $this->boatRegisterView->getLength(), $this->boatRegisterView->getBoatId());
        }

        // Page router
        if ($this->pageLayoutView->isPageRegisterMember()) { // Register member
            $view = $this->memberRegisterView;
        } else if ($this->pageLayoutView->isPageRegisterBoat()) { // Register boat
            $view = $this->boatRegisterView;
        } else if ($this->memberListView->isPageViewMember()) { // View member
            $view = $this->viewMember();
        } else if ($this->memberView->isPageEditMember()) { // Edit member
            $view = $this->editMember();
        } else if ($this->memberView->isPageDeleteMember()) { // Delete member
            $view = $this->deleteMember();
        } else if ($this->boatView->isPageDeleteBoat()) { // Delete boat
            $view = $this->deleteBoat();
        } else if ($this->boatView->isPageEditBoat()) { // EditBoat
            $view = $this->editBoat();
        } else {
            $view = $this->viewAllMembers();
        }
        //return $view;
        $this->pageLayoutView->render($view);
    }

    private function viewAllMembers() {
        $this->memberList = new \Model\MemberList(); // View all members
        $this->memberListView->updateMemberList($this->memberList);
        return $this->memberListView;
    }
    private function viewMember(){
        $this->memberView->setMember($this->memberList->getMember($this->memberListView->getMember()));
        return $this->memberView;
    }
    private function editMember() {
        $member = $this->memberList->getMember($this->memberView->getMemberToEdit());
        $this->memberRegisterView->setMemberToEdit($member);
        return $this->memberRegisterView;
    }
    
    private function deleteMember() {
        $this->memberList->deleteMember($this->memberView->getMemberToDelete());
        $this->memberListView->updateMemberList($this->memberList);
        return $this->memberListView;
    }

    private function editBoat() {
        $boat = $this->boatList->getBoatById($this->boatView->getBoatToEdit());
        $this->boatRegisterView->setBoatToEdit($boat);
        return $this->boatRegisterView;
    }

    private function deleteBoat() {
        $this->boatList->deleteBoat($this->boatView->getBoatToDelete());
        $this->memberList->deleteBoat($this->boatView->getBoatToDelete());
        $this->memberListView->updateMemberList($this->memberList);
        return $this->memberListView;

    }
}
?>