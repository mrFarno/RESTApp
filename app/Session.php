<?php

namespace App;

class Session  extends \Vespula\Auth\Session\Session
{

    /**
     * @param string $form
     * @return string
     */
    public function generateToken($form)
    {
        $token = sha1(uniqid(microtime(), true));
        $this->setValue($form . "_token", $token);
        return $token;
    }

    /**
     * Ajoute une valeur à la session
     *
     * @param string $key
     * @param mixed  $value
     *
     */
    public function setValue($key, $value)
    {
        switch($key){
            case 'username':
                return (!empty($this->store['userdata']->setUsername($value))) ? $this->store['userdata']->setUsername($value) : null;
                break;
            case 'view':
                return (!empty($this->store['view']= $value) ) ? $this->store['view']= $value : null;
                break;
            case 'id':
                return (!empty($this->store['userdata']->setId($value))) ? $this->store['userdata']->setId($value) : null;
                break;
            case 'role':
                return (!empty($this->store['userdata']->setRole($value))) ? $this->store['userdata']->setRole($value) : null;
                break;
            case 'auth':
                return (!empty($this->store['userdata']->setAuth($value))) ? $this->store['userdata']->setAuth($value) : null;
                break;
            case 'status':
                return (!empty($this->store['userdata']->setStatus($value))) ? $this->store['userdata']->setStatus($value) : null;
                break;
            default:
                return null;
        }
    }

    /**
     * @param string $form
     * @param string $token
     * @return bool
     * @throws \Exception
     */
    public function verifyToken($form, $token)
    {
        return strcmp ($this->getValue($form . "_token"), $token);
    }

    /**
     * Retourne une valeur de session
     *
     * @param $key
     * @return mixed
     */
    public function getValue($key)
    {
        switch($key){
            case 'id':
                return (!empty($this->store['userdata']->getId())) ? $this->store['userdata']->getId() : null;
                break;
            case 'username':
                return (!empty($this->store['userdata']->getLogin())) ? $this->store['userdata']->getLogin() : null;
                break;
            case 'view':
                return (!empty($this->store['view'])) ? $this->store['view'] : null;
                break;
            case 'role':
                return (!empty($this->store['userdata']->getRole())) ? $this->store['userdata']->getRole() : null;
                break;
            case 'auth':
                return (!empty($this->store['userdata']->getAuth())) ? $this->store['userdata']->getAuth() : null;
                break;
            case 'status':
                return (!empty($this->store['userdata']->getStatus())) ? $this->store['userdata']->getStatus() : null;
                break;
            case 'login_admin_token':
                return (!empty($this->store['userdata'][$key])) ? $this->store['userdata'][$key] : null;
                break;
            default:
                return null;
        }
    }

    /**
     * @param string $key
     */
    public function unsetValue($key)
    {
        unset($this->store['userdata'][$key]);
    }

    /**
     * @param string lang
     */
    public function setLang($lang)
    {
        $this->store['lang']= $lang;
    }

    /**
     * @return string lang / null
     */
    public function getLang()
    {
        return !empty($this->store['lang']) ? $this->store['lang'] : null;
    }
}
