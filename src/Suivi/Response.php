<?php
/**
 * La Poste Suivi API
 *
 * @author debuss-a <zizilex@gmail.com>
 * @copyright 2020 debuss-a
 * @license https://github.com/debuss/LaPosteSuivi/LICENSE.md MIT License
 * @link https://developer.laposte.fr/products/suivi/2
 */

namespace LaPoste\Suivi;

use DateTime;

/**
 * Class Response
 *
 * @package LaPoste\Suivi
 */
class Response
{

    /** @var string */
    protected $lang;

    /** @var string */
    protected $scope;

    /** @var string */
    protected $code;

    /** @var string */
    protected $message;

    /** @var int */
    protected $return_code;

    /** @var string */
    protected $return_message;
    
    /** @var string */
    protected $technical_message;

    /** @var string */
    protected $id_ship;

    /** @var Shipment */
    protected $shipment;

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getReturnCode()
    {
        return $this->return_code;
    }

    /**
     * @param int $return_code
     */
    public function setReturnCode($return_code)
    {
        $this->return_code = $return_code;
    }

    /**
     * @return string
     */
    public function getReturnMessage()
    {
        return $this->return_message;
    }
    
    /**
     * @return string
     */
    public function getTechnicalMessage()
    {
        return $this->technical_message;
    }

    /**
     * @param $return_message
     */
    public function setReturnMessage($return_message)
    {
        $this->return_message = $return_message;
    }
    
    /**
     * @param string
     */
    /*public function setTechnicalMessage($technical_message)
    {
        $this->technical_message = $technical_message;
    }*/

    /**
     * @return string
     */
    public function getIdShip()
    {
        return $this->id_ship;
    }

    /**
     * @param string $id_ship
     */
    public function setIdShip($id_ship)
    {
        $this->id_ship = $id_ship;
    }

    /**
     * @return Shipment
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @param array $data
     */
    public function setShipment($data)
    {
        $shipment = new Shipment();
        foreach ($data as $parameter => $value) {
            $shipment->{'set'.$parameter}($value);
        }

        $this->shipment = $shipment;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return !in_array($this->return_code, [200, 207]);
    }

    /**
     * @return bool
     */
    public function isDelivered()
    {
        return $this->shipment->isIsFinal();
    }

    /**
     * @return string
     */
    public function getTrackingUrl()
    {
        return sprintf(
            'https://www.laposte.fr/particulier/outils/suivre-vos-envois?code=%s',
            $this->shipment->getIdShip()
        );
    }

    /**
     * @return bool|DateTime
     */
    public function getPlannedDeliveryDate()
    {
        foreach ($this->shipment->getTimeline() as $timeline) {
            if ($timeline->getId() == 5 && $timeline->getDate() instanceof DateTime) {
                return $timeline->getDate();
            }
        }

        return false;
    }

    /**
     * @return Event
     */
    public function getCurrentEvent()
    {
        $events = $this->shipment->getEvent();

        return reset($events);
    }
}
