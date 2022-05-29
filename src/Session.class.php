<?php
class Session
{
    const MAX_REQUEST_DAY = 10000;
    private $user;
    private $model;

    public function __construct($model)
    {
        session_start();
        $this->model = $model;
        $this->update()->verifyEnabled()->verifyNumberRequest()->incrementRequest();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserName()
    {
        $name = "";
        if (! $this->isConnected()) {
            return $name;
        }
        if (!empty($this->user->first_name)) {
            $name .= $this->user->first_name;
        }
        if (!empty($this->user->last_name)) {
            if ($name !== "") {
                $name .= " ";
            }
            $name .= $this->user->last_name;
        }
        if ($name === "") {
            $name = $this->user->username;
        }

        return $name;
    }

    public function isConnected()
    {
        return $this->user !== false;
    }

    public function changeUser($username)
    {
        $_SESSION['username'] = $username;
        $this->update();

        return $this;
    }

    public function update()
    {
        $this->user = (isset($_SESSION['username'])) ? $this->model->getUserByUserName($_SESSION['username']) : false;
        return $this;
    }

    public function connectWithRedirection($user) {
        if ($user->enabled != false) {
            $this->model->setLastLogin($user, new DateTime());
            $_SESSION['username'] = $user->username;
        }
        header('location: index.php');
        exit;
    }

    public function resetWithRedirection()
    {
        $_SESSION = array();
        session_destroy();

        header('Location: index.php');
        exit;
    }

    public function AlreadyConnectedWithRedirection() {
        if ($this->isConnected()) {
            header('Location: index.php');
            exit;
        }
        else {
            return $this;
        }
    }

    public function NotConnectedWithRedirection() {
        if (!$this->isConnected()) {
            header('Location: login.php');
            exit;
        }
        else {
            return $this;
        }
    }

    private function verifyEnabled() {
        if ($this->isConnected() && $this->user->enabled == false) {
            $this->resetWithRedirection();
        }

        return $this;
    }

    private function verifyNumberRequest() {
        if ($this->isConnected()) {
            $last_req = explode('-', $this->user->date_last_request);
            if (time() - mktime(0, 0, 0, (int)$last_req[1], (int)$last_req[2], (int)$last_req[0]) >= 24*60*60) {
                $this->initNbRequest();
            }
            elseif ($this->user->nb_requests > self::MAX_REQUEST_DAY) {
                API::http_response_error(429);
                header('Retry-After: 3600');
                echo "<h1>429 - Too Many Requests</h1>";
                exit;
            }
            return $this;
        }

        return $this;
    }

    private function incrementRequest() {
        if ($this->isConnected()) {
            $this->model->setRequest($this->user, new DateTime($this->user->date_last_request), $this->user->nb_requests + 1);
        }

        return $this;
    }

    private function initNbRequest()
    {
        $this->model->setRequest($this->user, (new DateTime()), 0);
        $this->update();
        return $this;
    }
}
