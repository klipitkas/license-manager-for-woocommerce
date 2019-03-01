<?php

namespace LicenseManagerForWooCommerce;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto as DefuseCrypto;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;

defined('ABSPATH') || exit;

/**
 * LicenseManagerForWooCommerce Crypto class.
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class Crypto
{
    /**
     * The defuse key file name.
     *
     * @since 1.0.0
     */
    const DEFUSE_FILE = 'defuse.txt';

    /**
     * The secret file name.
     *
     * @since 1.0.0
     */
    const SECRET_FILE = 'secret.txt';

    /**
     * The defuse key file content.
     *
     * @var string
     */
    private $keyAscii;

    /**
     * Setup Constructor.
     */
    public function __construct()
    {
        $this->keyAscii = file_get_contents(LMFWC_ETC_DIR . self::DEFUSE_FILE);

        add_filter('lmfwc_encrypt', array($this, 'encrypt'), 10, 1);
        add_filter('lmfwc_decrypt', array($this, 'decrypt'), 10, 1);
        add_filter('lmfwc_hash',    array($this, 'hash'),    10, 1);
    }

    /**
     * Load the defuse key from the plugin folder.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function loadEncryptionKeyFromConfig()
    {
        return Key::loadFromAsciiSafeString($this->keyAscii);
    }

    /**
     * Encrypt a string and return the encrypted cipher text.
     *
     * @since 1.0.0
     *
     * @param string $value - The text which will be encrypted.
     *
     * @return string
     */
    public function encrypt($value)
    {
        return DefuseCrypto::encrypt($value, $this->loadEncryptionKeyFromConfig());
    }

    /**
     * Decrypt a cipher and return the decrypted value.
     *
     * @since 1.0.0
     *
     * @param string $cipher - The cipher text which will be decrypted.
     *
     * @return string
     */
    public function decrypt($cipher)
    {
        try {
            return DefuseCrypto::decrypt($cipher, $this->loadEncryptionKeyFromConfig());
        } catch (WrongKeyOrModifiedCiphertextException $ex) {
            // An attack! Either the wrong key was loaded, or the ciphertext has changed since it was created -- either
            // corrupted in the database or intentionally modified by someone trying to carry out an attack.
        }
    }

    public function hash($value)
    {
        return hash_hmac('sha256', $value, file_get_contents(LMFWC_ETC_DIR . self::SECRET_FILE));
    }
}