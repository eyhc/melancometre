<?php
class Model
{
    private $db;

    public function __construct()
    {
        try
        {
            $this->db = new PDO('mysql:host=127.0.0.1;dbname=frameofmind;charset=utf8', 'root', '');
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }
    }

    public function getUserById($id) {
        $req = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $req->execute(array('id' => $id));
        return $req->fetch(PDO::FETCH_OBJ);
    }

    public function getUserByUserName($name) {
        $req = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $req->execute(array('username' => $name));
        return $req->fetch(PDO::FETCH_OBJ);
    }

    public function addUser($un, $fn, $ln, $password)
    {
        $req = $this->db->prepare('INSERT INTO `users`(`username`, `first_name`, `last_name`, `password`) VALUES (:username, :firstName, :lastName, :password)');
        return $req->execute(array(
            'username' => $un,
            'firstName' => $fn,
            'lastName' => $ln,
            'password' => $password
        ));
    }

    public function updateUser($user, $un = null, $fn = null, $ln = null, $password = null)
    {
        $req = $this->db->prepare('UPDATE `users` SET `username` = :username, `first_name` = :firstName, `last_name` = :lastName, `password` = :password WHERE id = :id');
        return $req->execute(array(
            'id' => $user->id,
            'username' => $un === null ? $user->username : $un,
            'firstName' => $fn === null ? $user->first_name : $fn,
            'lastName' => $ln === null ? $user->last_name : $ln,
            'password' => $password === null ? $user->password : $password
        ));
    }

    public function deleteUser($user)
    {
        $req = $this->db->prepare('DELETE FROM `states` WHERE user_id = :id');
        $r = $req->execute(array('id' => $user->id));

        if ($r === false) {
            return false;
        }

        $req = $this->db->prepare('DELETE FROM `users` WHERE id = :id');
        return $req->execute(array('id' => $user->id));
    }

    public function setToken($user, $token) {
        $req = $this->db->prepare('UPDATE users SET token = :tok WHERE id = :id');
        return $req->execute(array('id' => $user->id, 'tok' => $token));
    }

    public function getLastData($user) {
        $req = $this->db->prepare('SELECT `date`, general, moral, energy, suicidal_ideas FROM states WHERE user_id = :id ORDER BY `date` DESC');
        $req->execute(array('id' => $user->id));
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function addNowData($user, $general, $moral, $energy, $si) {
        $req = $this->db->prepare('INSERT INTO `states` (`user_id`, `date`, `general`, `moral`, `energy`, `suicidal_ideas`) VALUES (?, ?, ?, ?, ?, ?)');
        return $req->execute(array(
            $user->id,
            date('Y-m-d H:i:s'),
            $general,
            $moral,
            $energy,
            $si
        ));
    }

    public function getAllData($user)
    {
        $req = $this->db->prepare('SELECT `id`, `date`, general, moral, energy, suicidal_ideas FROM states WHERE user_id = :id ORDER BY `date` ASC');
        $req->execute(array('id' => $user->id));
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setLastLogin($user, DateTime $date)
    {
        $req = $this->db->prepare('UPDATE users SET last_login = :datet WHERE id = :id');
        return $req->execute(array(
            'id' => $user->id,
            'datet' => $date->format('Y-m-d H:i:s')
        ));
    }

    public function setRequest($user, DateTime $date, $nbR) {
        $req = $this->db->prepare('UPDATE users SET date_last_request = :dt, nb_requests = :nb WHERE id = :id');
        return $req->execute(array(
            'id' => $user->id,
            'dt' => $date->format('Y-m-d'),
            'nb' => $nbR
        ));
    }
}
