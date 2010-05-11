<?php
class User extends AppModel {
    var $name = 'User';
    /**
     * A User has many Items and Purchases.
     */
    var $hasMany = array('Item', 'Purchase');
    /**
     * A User belongs to a Group.
     */
    var $belongsTo = array('Group');
    /**
     * A user acts as a requester.
     */
    var $actsAs = array('Acl' => 'requester');

    var $validate = array('username' => array('nameRule1' => array('rule' => array('between', 4, 20),
								   'last' => true,
								   'message' => 'Username must be between 4 and 20 characters.'),
					      'nameRule2' => array('rule' => 'alphaNumeric',
								   'last' => true,
								   'message' => 'Username must be alpha-numeric.'),
					      'nameRule3' => array('rule' => 'isUnique',
								   'message' => 'Username already exists.')),
			  'passwd' => array('passwordRule1' => array('rule' => array('minLength', 8),
								     'message' => 'Password must be at least 8 characters.')),
			  'email' => array('emailRule1' => array('rule' => 'email',
								 'message' => 'The email must be valid.')));

    /**
     * Gets the parent node of the user.
     *
     * @return array with Group id, or null
     */
    function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        $data = $this->data;
        if (empty($this->data)) {
            $data = $this->read();
        }
        if (!$data['User']['group_id']) {
            return null;
        } else {
            return array('Group' => array('id' => $data['User']['group_id']));
        }
    }

    function afterSave($created) {
	// Updates AROs table so it is correct.
        if (!$created) {
            $parent = $this->parentNode();
            $parent = $this->node($parent);
            $node = $this->node();
            $aro = $node[0];
            $aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
            $this->Aro->save($aro);
        }
    }

}
?>