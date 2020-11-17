<?php

namespace View;

require_once("view/IView.php");

class PageLayoutView {
  
  private static $registerMember = "registerMember";
  private static $registerBoat = "registerBoat";
  
  public function render(\View\IView $view) {
    echo '<!DOCTYPE html>
      <html lang="en">
        <head>
          <meta charset="utf-8">
          <title>W2 - Member and boat registry</title>
          <link rel="stylesheet" href="public/style.css">
        </head>
        <body>
          <h1>Member and boat registry W2 </h1>
          <nav>
          <a href="?">Home</a> |
          <a href="?' . self::$registerMember . '">Register a member</a> |
          <a href="?' . self::$registerBoat . '">Register a boat</a> |
          </nav>
          
          <div class="container">
              ' . $view->getHTML() . '
          </div>
         </body>
      </html>
    ';
  }


  public function isPageRegisterMember() : bool {
    return isset($_GET[self::$registerMember]);
  }

  public function isPageRegisterBoat() : bool {
      return isset($_GET[self::$registerBoat]);
  }


}
?>