<?php

require_once 'MercadoPago/mercadopago.php';

class MetodoPago_MercadoPago implements Interface_IMetodoPagoFacade {

    private $_mp_instancia = null;
    private $_instancia_compra = null;
    private $_is_sandbox = false;
    private $_url_ok;
    private $_url_ko;
    private $_url_notificacion;

    public function __construct($compra) {
        $cfg = Zend_Registry::get('Zend_Config');
        $mp = new MP($cfg['mp']['client_id'], $cfg['mp']['client_secret']);
        if ($cfg['mp']['sandbox']) {
            $mp->sandbox_mode(true);
            $this->setIsSandbox(true);
        }

        $this->_mp_instancia = $mp;
        $this->_instancia_compra = $compra;
    }

    public function isSandbox() {
        return $this->_is_sandbox;
    }

    public function setIsSandbox($sandbox) {
        $this->_is_sandbox = $sandbox;
        return $this;
    }

    /**
     * 
     * @return MP
     */
    public function getInstancia() {
        return $this->_mp_instancia;
    }

    public function getCompra() {
        return $this->_instancia_compra;
    }

    public function notify(Model_Row_Compra $compra) {
        if ($_GET['topic'] == 'payment') {
            try {
                $data_id = $_GET['id'];
                $ntf = $this->getInstancia()->get_payment($data_id);
                $response = $ntf['response'];

                if ($response['collection']['status'] == 'approved' && $compra->getAprobado() == 0) {
                    return true;
                }
            } catch (Exception $ex) {
                Bootstrap::logVar($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
            }
        } elseif ($_GET['topic'] == 'merchant_order') {
//            try {
//                $data_id = $_GET['id'];
//                $ntf = $mp->get('/merchant_orders/' . $data_id);
//                Bootstrap::logVar($ntf);
//            } catch (Exception $ex) {
//                Bootstrap::logVar($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
//            }
        }
        return false;
    }

    public function isRedirect() {
        return true;
    }

    public function getFormularioPago() {
        return '';
    }

    public function getRedirect() {
        $compra = $this->getCompra();

        $mpId = $compra->getMpId();
        $instanciaMercadoPago = $this->getInstancia();

        // Si compra no tiene id asignado, debemos crearlo
        if ($mpId == null) {
            // Creamos las preferencias
            $items = array();
            foreach ($compra->getDetalles() as $detalle) {
                if ($detalle->getCantidad() == 0)
                    continue;

                $items[] = array(
                    "title" => $detalle->getTipoLicencia()->getNombre(),
                    "quantity" => $detalle->getCantidad(),
                    "currency_id" => "ARS",
                    "unit_price" => (float) $detalle->getTipoLicencia()->getPrecio()
                );
            }

            $preference_data = array(
                "items" => $items,
                'back_urls' => array(
                    'success' => $this->getUrlOk(),
                    'pending' => $this->getUrlOk(),
                    'failure' => $this->getUrlKo(),
                ),
                'notification_url' => $this->getUrlNotificacion()
            );
            $preference = $instanciaMercadoPago->create_preference($preference_data);

            // Guardamos lo que nos retorno MP
            $compra->setFromArray(array(
                'mp_id' => Zend_Json::encode($preference['response']),
                'borrador' => 0
            ))->save();
        }

        // En este punto, si no tenia MpId se genero en el paso anterior
        // Sino lo saca de lo guardado en otro momento
        $preference = Zend_Json::decode($compra->getMpId());
        $preference = $instanciaMercadoPago->get_preference($preference['id']);

        if ($this->isSandbox())
            $url = $preference['response']['sandbox_init_point'];
        else
            $url = $preference['response']['init_point'];

        return $url;
    }

    public function getUrlKo() {
        return $this->_url_ko;
    }

    public function getUrlNotificacion() {
        return $this->_url_notificacion;
    }

    public function getUrlOk() {
        return $this->_url_ok;
    }

    public function setUrlKO($url) {
        $this->_url_ko = $url;
        return $this;
    }

    public function setUrlNotificacion($url) {
        $this->_url_notificacion = $url;
        return $this;
    }

    public function setUrlOk($url) {
        $this->_url_ok = $url;
        return $this;
    }

}
