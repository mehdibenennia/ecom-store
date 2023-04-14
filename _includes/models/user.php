<?php
require_once(__DIR__ . "/model.php");
require_once(__DIR__ . "/secureCookie.php");
require_once(__DIR__ . "/command.php");
$secure_cookie = new SecureCookie(PROJECT_COOKIE_NAME);
class User extends Model
{
    private $id = null;
    public $username;
    private $password;
    public $email;
    public $first_name;
    public $last_name;
    public $is_admin;
    public function __construct(int $id = null)
    {
        parent::__construct();
        if ($id) {
            $this->id = $id;
            $this->load();
        }
    }
    public function getID()
    {
        return $this->id;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getName()
    {
        return $this->first_name . " " . $this->last_name;
    }
    static function listUsers()
    {
        global $__def_user;
        $users = [];
        $result = $__def_user->fetchColumn("users", [], "username", "username", "ASC");
        foreach ($result as $user) {
            $users[] = $user["username"];
        }
        return $users;
    }
    static function newUser(
        string $username,
        string $password,
        string $email,
        string $firstName,
        string $lastName,
    ) {
        $user = new User();
        $user->username = $username;
        $user->password = $password;
        $user->email = $email;
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $id = $user->create("users", [
            "username" => $user->username,
            "password" => password_hash($user->password, PASSWORD_DEFAULT),
            "email" => $user->email,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
        ]);
        if ($id)
            $user->id = intval($id);
        return $user;
    }
    private function copy(array $user)
    {
        $this->id = $user["id"];
        $this->username = $user["username"];
        $this->password = $user["password"];
        $this->email = $user["email"];
        $this->first_name = $user["first_name"];
        $this->last_name = $user["last_name"];
        $this->is_admin = boolval($user["is_admin"]);
    }
    public function loaduser(string $username)
    {
        $user = $this->get("users", "username", $username);
        if ($user)
            $this->copy($user);
        return $user;
    }
    public function load_email(string $email)
    {
        $user = $this->get("users", "email", $email);
        if ($user)
            $this->copy($user);
        return $user;
    }
    public static function count()
    {
        global $__def_user;
        $count = $__def_user->countAll("users");
        return $count;
    }
    public static function find_by_username(string $username)
    {
        $user = new User();
        if ($user->loaduser($username)) {
            return $user;
        }
        return false;
    }
    public static function find_by_email(string $email)
    {
        $user = new User();
        if ($user->load_email($email)) {
            return $user;
        }
    }
    public static function find_by_id(int $id)
    {
        $user = new User();
        $user->id = $id;
        if ($user->load()) {
            return $user;
        }
        return false;
    }
    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    public function load()
    {
        $user = $this->get("users", "id", $this->id);
        if ($user) {
            $this->copy($user);
            return true;
        }
        return false;
    }
    public function verifyPassword(string $password)
    {
        return password_verify($password, $this->password);
    }
    public function save()
    {
        return $this->updateWhere("users", [
            "username" => $this->username,
            "password" => $this->password,
            "email" => $this->email,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "is_admin" => bool_to_str($this->is_admin),
        ], "id", $this->id);
    }
    public function delete()
    {
        return $this->deleteWhere("users", "id", $this->id);
    }
    public function isAdmin()
    {
        return $this->is_admin;
    }
    public function isLoggedIn()
    {
        return $this->id !== null;
    }
    public static function getCurrentUser()
    {
        global $secure_cookie;
        if (isset($_SESSION["user_id"]) && isset($_SESSION["user_password"])) {
            $user = User::find_by_id($_SESSION["user_id"]);
            if ($user && ($user->password === $_SESSION["user_password"])) {
                return $user;
            }
        } else if(isset($secure_cookie->user_id) && isset($secure_cookie->user_password)) {
            $user = User::find_by_id($secure_cookie->user_id);
            if ($user && ($user->password === $secure_cookie->user_password)) {
                $_SESSION["user_id"] = $user->id;
                $_SESSION["user_password"] = $user->password;
                return $user;
            }
        }
        return null;
    }
    public static function login(string $username, string $password, bool $remember = false)
    {
        global $secure_cookie;
        $user = User::find_by_username($username);
        if ($user && $user->verifyPassword($password)) {
            $_SESSION["user_id"] = $user->getID();
            $_SESSION["user_password"] = $user->password;
            if($remember) {
                $secure_cookie->user_id = $user->getID();
                $secure_cookie->user_password = $user->password;
            }
            return true;
        }
        return false;
    }
    public function getOrders()
    {
        return Command::find_by_user($this->id);
    }
    public static function logout()
    {
        global $secure_cookie;
        unset($_SESSION["user_id"]);
        unset($_SESSION["user_password"]);
        unset($_SESSION["cart"]);
        unset($secure_cookie->user_id);
        unset($secure_cookie->user_password);
    }
}
$__def_user = new User();
$current_user = User::getCurrentUser();
