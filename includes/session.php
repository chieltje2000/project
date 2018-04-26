<?php
// A class to help work with Sessions
// In our case, primarily to manage logging users in and out

// Keep in mind when working with sessions that it is generally
// inadvisable to store DB-related objects in sessions

class Session
{
    private $loggedIn = false;
    public $userId;
    public $message;
    public $page;
    public $superAdmin;

    function __construct()
    {
        session_start();
        $this->checkMessage();
        $this->checkPage();
        $this->checkLogin();
        $this->checkSuperAdmin();
        if ($this->loggedIn)
        {
            // action to take right away if user is logged in
        }
        else
        {
            // action to take right away if user is not logged in
        }
    }

    public function isLoggedIn()
    {
        return $this->loggedIn;
    }

    public function isSuperAdmin()
    {
        return $this->superAdmin == 1 ? true : false;
    }

    public function login($user)
    {
        // database should find user based on username/password
        if ($user)
        {
            $this->userId = $_SESSION["userId"] = $user->id;
            $this->superAdmin = $_SESSION["superAdmin"] = $user->super_admin;
            $this->loggedIn = true;
        }
    }

    public function logout()
    {
        unset($_SESSION["userId"]);
        unset($this->userId);
        unset($_SESSION["superAdmin"]);
        unset($this->superAdmin);
        $this->loggedIn = false;
    }

    public function message($msg = "")
    {
        if (!empty($msg))
        {
            // then this is "set message"
            // make sure you understand why $this->message=$msg wouldn't work
            $_SESSION['message'] = $msg;
        }
        else
        {
            // then this is "get message"
            return $this->message;
        }
    }

    public function page($page = "")
    {
        if (!empty($page))
        {
            $_SESSION['page'] = $page;
        }
        else
        {
            return $this->page;
        }
    }

    public function superAdmin($superAdmin = "")
    {
        if (!empty($superAdmin))
        {
            $_SESSION['superAdmin'] = $superAdmin;
        }
        else
        {
            return $this->superAdmin;
        }
    }


    private function checkLogin()
    {
        if (isset($_SESSION["userId"]))
        {
            $this->userId = $_SESSION["userId"];
            $this->loggedIn = true;
        }
        else
        {
            unset($this->userId);
            $this->loggedIn = false;
        }
    }

    private function checkMessage()
    {
        // Is there a message stored in the session?
        if (isset($_SESSION['message']))
        {
            // Add it as an attribute and erase the stored version
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);
        }
        else
        {
            $this->message = "";
        }
    }

    public function checkPage()
    {
        // Is there a page stored in the session?
        if (isset($_SESSION['page']))
        {
            // Add it as an attribute and erase the stored version
            $this->page = $_SESSION['page'];
            unset($_SESSION['page']);
        }
        else
        {
            $this->page = 1;
        }
    }

    public function checkSuperAdmin()
    {
        if (isset($_SESSION["superAdmin"]))
        {
            $this->superAdmin = $_SESSION["superAdmin"];

        }
        else
        {
            unset($this->superAdmin);
        }
    }

}

$session = new Session();
$message = $session->message();
$page = $session->page();