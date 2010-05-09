<?php  
class UploadsController extends AppController { 
    var $name = 'Uploads';
    var $components = array('FileUpload');

    function beforeFilter() {
	parent::beforeFilter();
	$this->FileUpload->uploadDir = 'img/uploads';
	$this->FileUpload->Upload = $this->Upload;
    }

    function add() {
	if (!empty($this->data)) {
	    if ($this->FileUpload->success) {
		$this->set('image', $this->FileUpload->finalFile);
	    } else {
		$this->Session->setFlash($this->FileUpload->showErrors());
	    }
	}
    }

    function index() {

    }

    function view($id) {
	$this->Upload->id = $id;
	$this->set('upload', $this->Upload->read());
    }

} 
?>