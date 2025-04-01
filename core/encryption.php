<?php

class encryption
{
    private $key;
    private $method = 'AES-256-CBC';

    public function __construct($key = 'default_secret_key')
    {
        $this->key = hash('sha256', $key, true);
    }

    public function encryptSimple($texto)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method));
        $encrypted = openssl_encrypt($texto, $this->method, $this->key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decryptSimple($texto)
    {
        $data = base64_decode($texto);
        $iv_length = openssl_cipher_iv_length($this->method);
        $iv = substr($data, 0, $iv_length);
        $encrypted_text = substr($data, $iv_length);
        return openssl_decrypt($encrypted_text, $this->method, $this->key, 0, $iv);
    }

    public function generateRandomCode($length = 10)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generarHash($password)
    {
        // PASSWORD_DEFAULT utiliza el algoritmo por defecto (actualmente BCRYPT)
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Función para verificar que un password corresponde a un hash dado
    function verificarHash($password, $hash)
    {
        // password_verify compara la contraseña con el hash
        return password_verify($password, $hash);
    }
}
