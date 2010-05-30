<?php
class ItemsController extends AppController {
    var $name = 'Items';
    var $components = array('FileUpload', 'Paypal');
    var $helpers = array('Number');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions =
            array('index', 'preview', 'view', 'clear', 'latest', 'terms');
        // Configure FileUpload
        $this->FileUpload->uploadDir = 'img/uploads';
        $this->FileUpload->fileModel = null;
    }

    /**
     * Returns true if the logged in user owns the item with the
     * specified id.
     */
    function _ownsItem($ownerId = null) {
        return $this->Auth->user('id') == $ownerId;
    }

    /**
     * Gets all Items if id is null. If id is not null it gets the Item with
     * that id. If it is an RSS request it gets the Items for the RSS feed.
     */
    function index($id = null) {
        // If request is ajax and id is set, get the item with that id.
        // If request is rss, get the 20 latest items and change the header.
        // Otherwise get pagenated items.
        if ($id != null && $this->RequestHandler->isAjax()) {
            $this->Item->id = $id;
            $this->set('item', $this->Item->read());
        } elseif($this->RequestHandler->isRss()) {
            $this->RequestHandler->respondAs('rss');
            header('Content-type: application/xml');
            Configure::write('debug', 0);
            $this->set('items',
                $this->Item->find('all',
                    array('limit' => 20,
                        'order' => 'Item.created DESC',
                        'conditions' => array('Item.sold = ' => false))));
        } else {
            $this->paginate = array('limit' => Configure::read('itemsPerPage'),
                              'order' => array('Item.created' => 'desc'),
                              'conditions' => array('Item.sold = ' => false));
            // Paginate using the search.
            $data = $this->paginate(null, $this->_filterSearch());
            $this->set('data', $data);
        }
    }

    /**
     * Gets the latest Items.
     *
     * @return the latest Items
     */
    function latest() {
        $url = $this->params['url'];
        if (isset($url['var1'])) {
            $latest = $url['var1'];
            $this->set('latest', $latest);
        } else {
            $this->set('latest', null);
        }
        $items = $this->Item->find('all',
                 array('limit' => 6,
					 'order' => 'Item.created DESC',
                     'conditions' => array('Item.sold = ' => false)));
        $this->set('items', $items);
        return $items;
    }

    /**
     * Clears the filtering of Items.
     */
    function clear() {
        $this->Session->delete($this->name.'.keyword');
        $this->index();
        $this->render('index');
    }

    /**
     * Filters the Items to only show matching ones.
     */
    function _filterSearch() {
        $filters = array();
        // If the data submitted isn't empty, then we use the new keyword.
        // Otherwise, check if there is an old one in the session and use
        // that instead.
        if (!empty($this->data)) {
            if (!empty($this->data['Item']['keyword'])
                && strlen($this->data['Item']['keyword']) > 0) {

                $search = $this->data['Item']['keyword'];
            } elseif (!empty($this->data['Search']['keyword'])
                && strlen($this->data['Search']['keyword']) > 0) {

                $search = $this->data['Search']['keyword'];
            }
        } elseif ($this->Session->check($this->name.'.keyword')) {
            $search = $this->Session->read($this->name.'.keyword');
        }

        // If there is something to search for we create the filters.
        if (isset($search)) {
            $search = strtolower($search);
            $filters = array("LOWER(Item.name) LIKE '%".$search."%'".
                       "OR Item.price LIKE '%".$search."%'".
                       "OR Item.description LIKE '%".$search."%'");
            $this->Session->write($this->name.'.keyword', $search);
        }

        return $filters;
    }

    /**
     * Gets the Item with the given id.
     *
     * @param id the id of the Item
     */
    function view($id = null) {
        $this->Item->id = $id;
        $this->set('item', $this->Item->read());
    }

    /**
     * Gets the Item with the given id.
     *
     * @param id the id of the Item
     */
    function preview($id = null) {
        $this->view($id);
        $this->render('preview');
    }

    /**
     * Edits the Item with the given id.
     *
     * @param id the id of the Item
     */
    function edit($id = null) {
        // If there is post data, try to save it.
        if (!empty($this->data)) {
            $this->Item->id = $this->data['Item']['id'];
            $oldData = $this->Item->read();
            // Check that the user owns the item.
            if ($this->_ownsItem($oldData['Item']['user_id'])) {
                // If user uploaded an image, get the name of the file.
                if ($this->FileUpload->success) {
                    $this->_deleteImage($oldData['Item']['image']);
                    $this->data['Item']['image'] = $this->FileUpload->finalFile;
                } else {
                    $this->data['Item']['image'] = $oldData['Item']['image'];
                }
                // Try to save it.
                if ($this->Item->save($this->data)) {
                    $this->Session->setFlash('The item has been saved.',
                        'default', array('class' => 'success'));
                } else {
                    $this->Session->setFlash('Failed saving the item.',
                        'default', array('class' => 'error'));
                }
                $this->redirect('/');
            } else {
                $this->Session->setFlash('You are not allowed to do that.',
                    'default', array('class' => 'error'));
            }
        }
        // Id should never be null.
        if ($id != null) {
            $this->Item->id = $id;
            $this->data = $this->Item->read();
            // Check that user owns the item.
            if ($this->_ownsItem($this->data['Item']['user_id'])) {
                // Set item, categories
                $this->set('item', $this->Item->read());
                $this->set('categories', $this->Item->Category->find('list'));
                /*$this->set('tags', $this->Item->Tag->find('list'));
                $this->set('selectedTags', $this->Item->Tag->find('list',
                        array('joins' => array(
                                array(
                                    'table' => 'items_tags',
                                    'alias' => 'ItemsTag',
                                    'type' => 'inner',
                                    'conditions'=> array('ItemsTag.tag_id = Tag.id')
                                ),
                                array(
                                    'table' => 'items',
                                    'alias' => 'Item',
                                    'type' => 'inner',
                                    'conditions'=> array(
                                        'Item.id = ItemsTag.item_id',
                                        'Item.id = ' => $id
                                        ))))));*/
            } else {
                $this->Session->setFlash('You are not allowed to do that.',
                    'default', array('class' => 'error'));
            }
        }

    }

    /**
     * Gets the item with the specified id.
     */
    function editPreview($id) {
        $this->Item->id = $id;
        $this->set('item', $this->Item->read());
    }

    /**
     * Adds the Item with the data supplied in the form.
     */
    function add() {
        // Get a list of categories.
        $this->set('categories', $this->Item->Category->find('list'));
        //$this->set('tags', $this->Item->Tag->find('list'));
        // If there is post data, try to save it.
        if (!empty($this->data)) {
            // Check if user uploaded an image, if so get it's name.
            if ($this->FileUpload->success) {
                $this->data['Item']['image'] = $this->FileUpload->finalFile;
            } else {
                $this->data['Item']['image'] = null;
            }

            // Check that user is logged in.
            if ($this->Session->check('Auth.User.id')) {
                // Set the user id and category id.
                $this->data['Item']['user_id'] = $this->Auth->user('id');
                $this->data['Item']['category_id'] = $this->data['Item']['categories'];
                // Try to save item.
                if ($this->Item->save($this->data)) {
                    $this->Session->setFlash('The item has been saved.',
                        'default', array('class' => 'success'));
                    $this->redirect('/');
                } else {
                    // Delete image if failed.
                    if (!empty($this->data['Item']['image'])) {
                        $this->_deleteImage($this->data['Item']['image']);
                    }
                    $this->Session->setFlash('The item could not be saved.',
                        'default', array('class' => 'error'));
                }
            } else {
                if (!empty($this->data['Item']['image'])) {
                    $this->_deleteImage($this->data['Item']['image']);
                }
                $this->Session->setFlash('You need to be logged in.',
                    'default', array('class' => 'error'));
            }


        }
    }

    /**
     * Deletes the image with given name.
     */
    function _deleteImage($name) {
        $file = new File(WWW_ROOT .
                $this->FileUpload->uploadDir . '/' .
                $name);
        $file->delete();
        $file->close();
    }

    /**
     * Deletes the Item with the given id.
     *
     * @param id the id of the Item
     */
    function delete($id) {
        $this->Item->id = $id;
        $item = $this->Item->read();
        // If user owns the item, delete it.
        if ($this->_ownsItem($item['Item']['user_id'])) {
            if (!empty($item['Item']['image'])) {
                $this->_deleteImage($item['Item']['image']);
            }
            if ($this->Item->delete($id)) {
                $this->Session->setFlash('The item has been deleted.',
                    'default', array('class' => 'success'));
            } else {
                $this->Session->setFlash('Failed deleting the item.',
                    'default', array('class' => 'error'));
            }
            $this->autoRender = false;
            $this->redirect('/items/index');
        } else {
            $this->Session->setFlash('You are not allowed to do that.',
                'default', array('class' => 'error'));
        }
    }

    /**
     * Terms of agreement.
     */
    function terms() {

    }

    /**
     * Buys the Item with the given id.
     *
     * @param id the id of the Item
     */
    function buy($id, $confirm = null) {
        $this->Item->id = $id;
        $item = $this->Item->read();
        // Check that item hasn't been sold.
        if ($item['Item']['sold'] == 0) {
            // Confirm will not be null when user has submitted payment form.
            if ($confirm == null) {
                // Get the item, user and a country list.
                $this->Item->id = $id;
                $this->set('item', $this->Item->read());
                $this->set('user',
                    $this->Item->User->findById($this->Session->read('Auth.User.id')));
                $this->set('countries',
                    $this->Item->User->Country->find('list', array('order' => 'Country.name ASC')));
            } else {
                // Get the payment info.
                $paymentInfo = array('Member'=>
                               array(
                                   'first_name' => $this->data['User']['first_name'],
                                   'last_name' => $this->data['User']['last_name'],
                                   'billing_address' => $this->data['User']['address'],
                                   'billing_address2' => '',
                                   'billing_country' => $this->data['User']['countries'],
                                   'billing_city' => $this->data['User']['city'],
                                   'billing_state' => 'CA',
                                   'billing_zip' => $this->data['User']['zip']
                               ),
                               'CreditCard'=>
                               array(
                                   'card_number' => $this->data['User']['creditcard'],
                                   'credit_type' => 'Visa',
                                   'expiration_month' => $this->data['User']['expmonth'],
                                   'expiration_year' => $this->data['User']['expyear'],
                                   'cv_code' => $this->data['User']['cvv']
                               ),
                               'Order'=>
                               array('theTotal' => $item['Item']['price'])
                );
                // Where the payment should go.
                $paypalInfo = array('Info'=>
                              array(
                                  'username' => 'robban_1274642016_biz_api1.hotmail.com',
                                  'password' => '1274642026',
                                  'signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AIhW89.q6lBZucF.z4LsF3w0d7oJ'
                              ));

                /*
                 * On Success, $result contains [AMT] [CURRENCYCODE] [AVSCODE] [CVV2MATCH]
                 * [TRANSACTIONID] [TIMESTAMP] [CORRELATIONID] [ACK] [VERSION] [BUILD]
                 *
                 * On Fail, $ result contains [AMT] [CURRENCYCODE] [TIMESTAMP] [CORRELATIONID]
                 * [ACK] [VERSION] [BUILD] [L_ERRORCODE0] [L_SHORTMESSAGE0] [L_LONGMESSAGE0]
                 * [L_SEVERITYCODE0]
                 *
                 * Success or Failure is best tested using [ACK].
                 * ACK will either be "Success" or "Failure"
                 */

                // Transfer money.
                $result = $this->Paypal->processPayment($paymentInfo,"DoDirectPayment", $paypalInfo);
                $ack = strtoupper($result["ACK"]);

                if ($ack=="SUCCESSWITHWARNING" || $ack=="SUCCESS") {
                    // Successful payment. Set sold to true, add to purchases.
                    $this->Item->id = $id;
                    $this->Item->saveField('sold', true);
                    $item = $this->Item->read();
                    $purchase = array('Purchase' =>
                                array('user_id' =>
                                    $this->Session->read('Auth.User.id'),
                                    'item_id' => $id));
                    $this->Item->Purchase->save($purchase);
                    // Get item seller and set confirmed to true.
                    $this->set('item', $item);
                    $this->set('seller', $this->Item->User->findById($item['User']['id']));
                    $this->set('confirm', true);

                } else {
                    // Payment didn't go through.
                    $this->set('error', $result['L_LONGMESSAGE0']);
                }
            }
        } else {
            // Item has already been bought.
            $this->set('purchased', true);
        }
    }


  }

?>