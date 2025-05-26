<?php

class encryption
{
    private $key;
    private $method = 'AES-256-CBC';

    // Constructor: recibe una clave y la convierte en hash SHA-256 (clave binaria de 256 bits)
    public function __construct($key = 'default_secret_key')
    {
        $this->key = hash('sha256', $key, true);
    }

    // Encriptación simétrica simple (texto plano a texto cifrado, codificado en base64)
    public function encryptSimple($texto)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method)); // Vector de inicialización aleatorio
        $encrypted = openssl_encrypt($texto, $this->method, $this->key, 0, $iv); // Cifra el texto
        return base64_encode($iv . $encrypted); // Devuelve el IV concatenado con el texto cifrado
    }

    // Desencriptación del texto cifrado (base64) a texto plano
    public function decryptSimple($texto)
    {
        $data = base64_decode($texto); // Decodifica el texto en base64
        $iv_length = openssl_cipher_iv_length($this->method); // Longitud del IV
        $iv = substr($data, 0, $iv_length); // Extrae el IV
        $encrypted_text = substr($data, $iv_length); // Extrae el texto cifrado
        return openssl_decrypt($encrypted_text, $this->method, $this->key, 0, $iv); // Devuelve el texto plano
    }

    // Generador de códigos aleatorios (alfanuméricos)
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

    // Genera un hash seguro para una contraseña
    public function generarHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT); // Usa el algoritmo recomendado (actualmente BCRYPT)
    }

    // Verifica si una contraseña coincide con su hash
    public function verificarHash($password, $hash)
    {
        return password_verify($password, $hash); // Compara el password plano con el hash
    }
}
