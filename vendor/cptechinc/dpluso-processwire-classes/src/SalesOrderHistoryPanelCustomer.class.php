<?php 
	class CustomerSalesOrderHistoryPanel extends SalesOrderHistoryPanel {
		use OrderPanelCustomerTraits;
		
		public $orders = array();
		public $paneltype = 'shipped-order';
		public $filterable = array(
			'custpo' => array(
				'querytype' => 'between',
				'datatype' => 'char',
				'label' => 'Cust PO'
			),
			'custid' => array(
				'querytype' => 'between',
				'datatype' => 'char',
				'label' => 'CustID'
			),
			'orderno' => array(
				'querytype' => 'between',
				'datatype' => 'char',
				'label' => 'Order #'
			),
			'ordertotal' => array(
				'querytype' => 'between',
				'datatype' => 'numeric',
				'label' => 'Order Total'
			),
			'orderdate' => array(
				'querytype' => 'between',
				'datatype' => 'date',
				'date-format' => 'Ymd',
				'label' => 'Order Date'
			),
			'invdate' => array(
				'querytype' => 'between',
				'datatype' => 'date',
				'date-format' => 'Ymd',
				'label' => 'Invoice Date'
			)
		);
		
		/* =============================================================
			SalesOrderPanelInterface Functions
		============================================================ */
		public function get_ordercount($debug = false) {
			$count = count_customersaleshistory($this->sessionID, $this->custID, $this->shipID, $this->filters, $this->filterable, $debug);
			return $debug ? $count : $this->count = $count;
		}
		
		public function get_orders($debug = false) {
			$useclass = true;
			if ($this->tablesorter->orderby) {
				if ($this->tablesorter->orderby == 'orderdate') {
					$orders = get_customersaleshistoryorderdate($this->sessionID, $this->custID, $this->shipID, DplusWire::wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $this->filters, $this->filterable, $useclass, $debug);
				} elseif ($this->tablesorter->orderby == 'invdate') {
					$orders = get_customersaleshistoryinvoicedate($this->sessionID, $this->custID, $this->shipID, DplusWire::wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $this->filters, $this->filterable, $useclass, $debug);
				} else {
					$orders = get_customersaleshistoryorderby($this->sessionID, $this->custID, $this->shipID, DplusWire::wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $this->tablesorter->orderby, $this->filters, $this->filterable, $useclass, $debug);
				}
			} else {
				// DEFAULT BY ORDER DATE SINCE SALES ORDER # CAN BE ROLLED OVER
				$this->tablesorter->sortrule = 'DESC';
				$orders = get_customersaleshistoryorderdate($this->sessionID, $this->custID, $this->shipID, DplusWire::wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $this->filters, $this->filterable, $useclass, $debug);
			}
			return $debug ? $orders : $this->orders = $orders;
		}
		
		/* =============================================================
			OrderPanelInterface Functions
			LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
		============================================================ */
		public function setup_pageurl(\Purl\Url $pageurl) {
			$url = $pageurl;
			$url->path = DplusWire::wire('config')->pages->ajax."load/sales-history/customer/";
			$url->path->add($this->custID);
            $this->paginationinsertafter = $this->custID;
            if (!empty($this->shipID)) {
                $url->path->add("shipto-$this->shipID");
                $this->paginationinsertafter = "shipto-$this->shipID";
            }
			$url->query->remove('display');
			$url->query->remove('ajax');
			return $url;
		}
		
		public function generate_loaddetailsurl(Order $order) {
			$url = new \Purl\Url(parent::generate_loaddetailsurl($order));
			$url->query->set('custID', $this->custID);
			return $url->getUrl();
		}
		
		public function generate_lastloadeddescription() {
			if (DplusWire::wire('session')->{'orders-loaded-for'}) {
				if (DplusWire::wire('session')->{'orders-loaded-for'} == $this->custID) {
					return 'Last Updated : ' . DplusWire::wire('session')->{'orders-updated'};
				}
			}
			return '';
		}
		
		public function generate_filter(ProcessWire\WireInput $input) {
			parent::generate_filter($input);
			
			if (isset($this->filters['orderdate'])) {
				if (empty($this->filters['orderdate'][0])) {
					$this->filters['orderdate'][0] = date('m/d/Y', strtotime(get_minsaleshistoryorderdate($this->sessionID, 'orderdate')));
				}
				
				if (empty($this->filters['orderdate'][1])) {
					$this->filters['orderdate'][1] = date('m/d/Y');
				}
			}
			
			if (isset($this->filters['invdate'])) {
				if (empty($this->filters['invdate'][0])) {
					$this->filters['invdate'][0] = date('m/d/Y', strtotime(get_minsaleshistoryorderdate($this->sessionID, 'invdate')));
				}
				
				if (empty($this->filters['orderdate'][1])) {
					$this->filters['invdate'][1] = date('m/d/Y');
				}
			}
			
			if (isset($this->filters['ordertotal'])) {
				if (!strlen($this->filters['ordertotal'][0])) {
					$this->filters['ordertotal'][0] = '0.00';
				}
				
				if (!strlen($this->filters['ordertotal'][1])) {
					$this->filters['ordertotal'][1] = get_maxordertotal($this->sessionID, $this->custID);
				}
			}
		}
		
		/* =============================================================
			SalesOrderDisplayInterface Functions
			LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
		============================================================ */
		public function generate_trackingrequesturl(Order $order) {
			$url = new \Purl\Url(parent::generate_trackingrequesturl($order));
			$url->query->set('custID', $this->custID);
			$url->query->set('type', 'history');
			return $url->getUrl();
		}
		
		/* =============================================================
			OrderDisplayInterface Functions
			LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
		============================================================ */
		public function generate_documentsrequesturl(Order $order, OrderDetail $orderdetail = null) {
			$url = new \Purl\Url(parent::generate_documentsrequesturl($order, $orderdetail));
			$url->query->set('custID', $this->custID);
			$url->query->set('type', 'history');
			return $url->getUrl();
		}
	}