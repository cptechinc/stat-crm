<?php 
	/**
	 * Class for the Individual Items in the User's Cart
	 */
	class CartDetail extends OrderDetail implements OrderDetailInterface {
		use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;
	
		protected $orderno;
		protected $price;
		protected $qty;
		protected $qtyshipped;
		protected $qtybackord;
		protected $hasdocuments;
		protected $qtyavail;
		protected $cost;
		protected $promocode;
		protected $taxcodeperc;
		protected $uomconv;
		protected $catlgid;
		protected $ponbr;
		protected $poref;
				
		/* =============================================================
			GETTER FUNCTIONS
		============================================================ */
		/**
		 * Cart Detail does not have a flag for error msg so we check if the errormsg
		 * is empty
		 * @return bool is errormsg empty
		 */
		public function has_error() {
			return !empty($this->errormsg);
		}
		
		public function is_kititem() {
			return $this->kitemflag == 'Y' ? true : false;
		}
		/**
		 * Checks if detail has documents by looking at the document flag
		 * @return bool $this->hasdocuments == 'Y'
		 */
		public function has_documents() {
			return $this->hasdocuments == 'Y' ? true : false;
		}
		
		/**
		 * Checks if there's dplus Qnotes 
		 * @return bool Calls to Database for Qnote Count for this line #
		 */
		public function has_notes() {
			return has_dplusnote($this->sessionid, $this->sessionid, $this->linenbr, Processwire\wire('config')->dplusnotes['cart']['type']) == 'Y' ? true : false;
		}
		
		/**
		 * Returns if Cart Detail is editable AND should always be by default
		 * @return bool Can the cart detail be editable
		 */
		public function can_edit() {
			return true;
		}
		
		/* =============================================================
			GENERATE DPLUS DATA FUNCTIONS 
		============================================================ */
			
			function generate_editdetaildata($custID, $shipID = false) {
				$data = array('DBNAME' => Processwire\wire('config')->dbName, 'CARTDET' => false, 'LINENO' => $this->linenbr);
				$data['CUSTID'] = empty($custID) ? $config->defaultweb : $custID;
				if (!empty($shipID)) {$data['SHIPTOID'] = $shipID; }
				return $data;
			}
		/* =============================================================
			GENERATE ARRAY FUNCTIONS 
			The following are defined CreateClassArrayTraits
			public static function generate_classarray()
			public function _toArray()
		============================================================ */
		/**
		 * Mainly called by the _toArray() function which makes an array
		 * based of the properties of the class, but this function filters the array
		 * to remove keys that are not in the database
		 * This is used by database classes for update
		 * @param  array $array array of the class properties
		 * @return array        with certain keys removed
		 */
		public static function remove_nondbkeys($array) {
			unset($array['sublinenbr']);
			unset($array['status']);
			unset($array['custid']);
			unset($array['ordrtotalcost']);
			unset($array['lostreason']);
			unset($array['lostdate']);
			unset($array['stancost']);
			return $array;
		}
		
		/* =============================================================
			CRUD FUNCTIONS
		============================================================ */
		/**
		 * Creates a Cart Detail record in the Database
		 * @param  bool   $debug Whether SQL executes or not
		 * @return string Query for the INSERT Operation
		 */
		public function create($debug = false) {
			return insert_cartdetail($this->sessionid, $this, $debug);
		}
		
		/**
		 * Reads the Cart Detail from the Database
		 * @param  string  $sessionID Session ID
		 * @param  int     $linenbr   Line # to load
		 * @param  bool    $debug     Whether to return SQL query or CartDetail object
		 * @return CartDetail         Or SQL Query for retrieving record
		 */
		public static function load($sessionID, $linenbr, $debug = false) {
			return get_cartdetail($sessionID, $linenbr, $debug);
		}
		/**
		 * CartDetail submits changes to the record in the Database
		 * @param  bool   $debug Whether SQL executes or not
		 * @return string Query for the INSERT Operation
		 */
		public function update($debug = false) {
			return update_cartdetail($this->sessionid, $this, $debug);
		}
		/**
		 * Checks if changes were made to the Cart Detail
		 * @return bool If this has any differences from original record
		 */
		public function has_changes() {
			$properties = array_keys(get_object_vars($this));
			$detail = self::load($this->sessionid, $this->linenbr, false);
			
			foreach ($properties as $property) {
				if ($this->$property != $detail->$property) {
					return true;
				}
			}
			return false;
		}
	}
