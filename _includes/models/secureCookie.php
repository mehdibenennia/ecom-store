<?php
require_once(__DIR__ . "/../utils/_init.php");
class SecureCookie
{
    private $cookieName;
    private $cookieExpire;
    private $data;
    private $options;
    private $deleted = false;
    public function __construct(string $cookieName = "SECURE_COOKIE", int $cookieExpire = PROJECT_COOKIE_TIME, array $options = ["path" => "/"])
    {
        ob_start();
        $this->cookieName = $cookieName;
        $this->cookieExpire = $cookieExpire;
        $data = $this->getData();
        if (!$data) {
            $data = [];
        }
        $this->data = $data;
        $this->options = $options;
    }
    private function _encrypt($decrypted)
    {
        $password = PROJECT_PWD;
        $salt = PROJECT_SALT;
        $key = hash('SHA256', $salt . $password, true);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        $encrypted = base64_encode(openssl_encrypt(json_encode($decrypted), 'aes-128-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag));
        $tag = base64_encode($tag);
        return "$tag.$encrypted";
    }
    private function _decrypt($data)
    {
        $password = PROJECT_PWD;
        $salt = PROJECT_SALT;
        $d = explode('.', $data);
        $tag = base64_decode($d[0]);
        $encrypted = base64_decode($d[1]);
        $key = hash('SHA256', $salt . $password, true);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        $decrypted = openssl_decrypt($encrypted, 'aes-128-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
        return json_decode($decrypted, true);
    }
    public function getData()
    {
        if (isset($_COOKIE[$this->cookieName])) {
            $data = $this->_decrypt($_COOKIE[$this->cookieName]);
            if (
                isset($data['$time'])
                && $data['$time'] < time() + $this->cookieExpire
            ) {
                unset($data['$time']);
                return $data;
            }
        }
        return false;
    }
    public function save()
    {
        $time = time();
        $data = array_merge($this->data, [
            '$time' => $time
        ]);
        $encrypted = $this->_encrypt($data);
        setcookie($this->cookieName, $encrypted, array_merge($this->options, [
            'expires' => $time + $this->cookieExpire,
        ]));
    }
    public function __get($name)
    {
        $data = $this->data;
        if (isset($data[$name])) {
            return $data[$name];
        }
        return false;
    }
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }
    public function __unset($name)
    {
        unset($this->data[$name]);
    }
    function __destruct()
    {
        $this->save();
        ob_end_flush();
    }
}
