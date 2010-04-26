<?php
class User extends AppModel {
    var $name = 'User';
    var $hasMany = array('Item', 'Purchase');
    var $belongsTo = array('Group');
    var $actsAs = array('Acl' => 'requester');
    
    // Gets the parent node of the user.
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
    
    // Updates AROs table.
    function afterSave($created) {
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