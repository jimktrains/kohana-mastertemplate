<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_MasterTemplate extends Controller {
	protected $_template;
	protected $_master = 'master';
	protected $_auto_render = TRUE;

	// To be used later
	protected $_allowed = array();
	
	protected $_master_vars = array('title', 'styles', 'scripts', 'metas', 'httpequivs', 'body', 'links', 'js_text');
	
	public function __construct(Request $request){
		parent::__construct($request);
	}
	
	public function before(){
		if(is_null($this->_template)){	
			$this->_template = $this->request->controller.'/'.$this->request->action;
		}
		if ($this->_auto_render){
			$this->_master = View::factory($this->_master);
			if (($path = Kohana::find_file('views', $this->_template)) !== FALSE){
				$this->_template = View::factory($this->_template);
			}else{
				$this->_template = $this->request->controller.'/'.$this->request->_action_requested;
				if (($path = Kohana::find_file('views', $this->_template)) !== FALSE){
					$this->_template = View::factory($this->_template);
				}else{
					$this->_template = $this->request->controller;
					if (($path = Kohana::find_file('views', $this->_template)) !== FALSE){
						$this->_template = View::factory($this->_template);
					}else{
						$this->_template = "";
						$this->_auto_render = FALSE;
					}
				}
			}
			$this->_master->styles = array();
			$this->_master->scripts = array();
			$this->_master->metas = array();
			$this->_master->httpequivs = array();
			$this->_master->links = array();
			$this->_master->body = "";
			$this->_master->js_text = "";
			
		}
	}

	// public function & __get($key){
	// 	if($this->_auto_render){
	// 		if(in_array($key, $this->_master_vars)){
	// 			return $this->_master->$key;
	// 		}else{
	// 			return $this->_template->$key;
	// 		}
	// 	}
	// 	throw new Kohana_View_Exception('The requested variable :var could not be found', array(
	// 		':var' => $key,
	// 	));
	// }
	// 
	// public function __set($key, $value){
	// 	if($this->_auto_render){
	// 		if(in_array($key, $this->_master_vars)){
	// 			$this->_master->$key = $value;
	// 		}else{
	// 			$this->_template->$key = $value;
	// 		}
	// 	}
	// }

	/**
	 * Assigns the template as the request response.
	 *
	 * @param   string   request method
	 * @return  void
	 */
	public function after(){
		if ($this->_auto_render === TRUE){
			if(0 === strlen($this->_master->body)){
				$this->_master->body = $this->_template;
			}
			$this->request->response = $this->_master;
		}
	}
	
	abstract public function not_found();
}
