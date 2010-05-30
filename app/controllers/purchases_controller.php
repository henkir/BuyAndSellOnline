<?php
class PurchasesController extends AppController {
    var $name = 'Purchases';
    var $components = array('Paypal');

    function beforeFilter() {
        parent::beforeFilter();
    }

    /**
     * Gets all Purchases paginated.
     */
    function index() {
        $this->paginate = array('limit' => 20,
                          'conditions' => array('Purchase.user_id = ' =>
                                        $this->Auth->user('id')));
        $this->set('data', $this->paginate('Purchase'));
    }

    /**
     * Gets the Purchase with the given id.
     *
     * @param id the id of the Purchase
     */
    function view($id = null) {
        $this->Purchase->id = $id;
        $this->set('purchase', $this->Purchase->read());
    }

    /**
     * Confirms the purchase with the specified id.
     */
    function confirm($id) {
        if (!empty($id)) {
            $this->Purchase->id = $id;
            $purchase = $this->Purchase->read();
            // Check that logged in user should confirm purchase.
            if ($purchase['Purchase']['user_id']
                == $this->Auth->user('id')) {
                // Check that it hasn't been confirmed.
                if (!$purchase['Purchase']['confirmed']) {
                    // The stores creditcard info
                    $paymentInfo = array('Member'=>
                                   array(
                                       'first_name' => 'Robin',
                                       'last_name' => 'Axelsson',
                                       'billing_address' => '1 Main St',
                                       'billing_address2' => '',
                                       'billing_country' => 'US',
                                       'billing_city' => 'San Jose',
                                       'billing_state' => 'CA',
                                       'billing_zip' => '95131'
                                   ),
                                   'CreditCard'=>
                                   array(
                                       'card_number' => '4835953932033841',
                                       'credit_type' => 'Visa',
                                       'expiration_month' => '05',
                                       'expiration_year' => '2015',
                                       'cv_code' => '3841'
                                   ),
                                   'Order'=>
                                   array('theTotal' => $item['Item']['price'])
                                   );

                    // Seller's details.
                    $paypalInfo = array('Info'=>
                                  array(
                                      'username' => $item['Item']['paypal'],
                                      'password' => $item['Item']['paypalpass'],
                                      'signature' => $item['Item']['paypalsignature']
                                  ));

                    // Transfer money.
                    $result = $this->Paypal->processPayment($paymentInfo,"DoDirectPayment", $paypalInfo);

                    $ack = strtoupper($result["ACK"]);

                    if($ack=="SUCCESSWITHWARNING" || $ack=="SUCCESS") {
                        // If successful, set confirmed to true.
                        $this->Purchase->saveField('confirmed', true);
                        $this->Session->setFlash('Successfully confirmed purchase.',
                            'default', array('class' => 'success'));

                    } else {
                        $this->set('error', $result['L_LONGMESSAGE0']);
                    }
                    $this->autoRender = false;
                    $this->redirect('/purchases/index');
                }
            }
        }
    }

    /**
     * Edits the Purchase with the given id.
     *
     * @param id the id of the Purchase
     */
    function edit($id) {
        $this->set('purchases', $this->Purchase->find('all'));
        if (!empty($this->data)) {
            if ($this->Purchase->save($this->data)) {
                $this->Session->setFlash('The purchase has been saved.');
            } else {
                $this->Session->setFlash('Failed saving the purchase.');
            }
        }
    }

    /**
     * Adds a Purchase with the data supplied in the form.
     */
    function add() {
        $this->set('purchases', $this->Purchase->find('all'));
        if (!empty($this->data)) {
            if ($this->Purchase->save($this->data)) {
                $this->Session->setFlash('The purchase has been saved.');
            } else {
                $this->Session->setFlash('Failed saving the purchase.');
            }
        }
    }

    /**
     * Deletes the Purchase with the given id.
     *
     * @param id the id of the Purchase
     */
    function delete($id) {
        $this->Purchase->delete($id);
        $this->Session->setFlash('The purchase has been deleted.');
        $this->redirect(array('action' => 'edit'));
    }


  }

?>