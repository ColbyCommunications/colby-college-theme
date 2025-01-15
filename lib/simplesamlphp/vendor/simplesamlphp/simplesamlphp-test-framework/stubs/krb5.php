<?php

class KRB5NegotiateAuth
{
    /**
     * @param string $keytab
     * @param string $spn|null
     */
    public function __construct($keytab, $spn = null, GSSAPIChannelBinding $binding = null)
    {
    }


    /**
     * @return bool
     */
    public function doAuthentication()
    {
    }


    /**
     * @return string
     */
    public function getAuthenticatedUser()
    {
    }


    /**
     * @param KRB5CCache $ccache
     * @return void
     */
    public function getDelegatedCredentials(KRB5CCache $ccache)
    {
    }

    /**
     * @return bool
     */
    public function isChannelBound()
    {
    }
}


class GSSAPIChannelBinding
{
    public function __construct()
    {
    }


    /**
     * @return string
     */
    public function getInitiatorAddress()
    {
    }


    /**
     * @return string
     */
    public function getInitiatorAddressType()
    {
    }


    /**
     * @return string
     */
    public function getAcceptorAddress()
    {
    }


    /**
     * @return string
     */
    public function getAcceptorAddressType()
    {
    }


    /**
     * @return string
     */
    public function getApplicationData()
    {
    }


    /**
     * @param string $data
     * @return void
     */
    public function setApplicationData($data)
    {
    }


    /**
     * @param int $type
     * @param string $data
     * @return void
     */
    public function setInitiatorAddress($type, $data)
    {
    }


    /**
     * @param int $type
     * @param string $data
     * @return void
     */
    public function setAcceptorAddress($type, $data)
    {
    }
}


class KRB5CCache
{
    /**
     *
     */
    public function __construct()
    {
    }


    /**
     * @return string
     */
    public function getName()
    {
    }


    /**
     * @param string $src
     * @return bool
     */
    public function open($src)
    {
    }


    /**
     * @param string $dest
     * @return bool
     */
    public function save($dest)
    {
    }


    /**
     * @param string $principal
     * @param string $pass
     * @param array|null $options
     * @return bool
     */
    public function initPassword($principal, $pass, $options = null)
    {
    }


    /**
     * @param string $principal
     * @param string $keytab_file
     * @param array|null $options
     * @return bool
     */
    public function initKeytab($principal, $keytab_file, $options = null)
    {
    }


    /**
     * @return string
     */
    public function getPrincipal()
    {
    }


    /**
     * @return string
     */
    public function getRealm()
    {
    }


    /**
     * @return array
     */
    public function getLifetime()
    {
    }


    /**
     * @return array
     */
    public function getEntries()
    {
    }


    /**
     * @param int $timeRemain
     * @return bool
     */
    public function isValid($timeRemain = 0)
    {
    }


    /**
     * @param string|null $prefix
     * @return array
     */
    public function getTktAttrs($prefix = null)
    {
    }


    /**
     * @return bool
     */
    public function renew()
    {
    }


    /**
     * @param string $principal
     * @param string $oldpass
     * @param string $newpass
     * @return bool
     */
    public function changePassword($principal, $oldpass, $newpass)
    {
    }


    /**
     * @return array
     */
    public function getExpirationTime()
    {
    }
}
