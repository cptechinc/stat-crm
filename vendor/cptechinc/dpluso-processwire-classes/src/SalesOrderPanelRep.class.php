<?php 
	class RepSalesOrderPanel extends SalesOrderPanel {
		
		/* =============================================================
			SalesOrderPanelInterface Functions
		============================================================ */
		public function get_ordercount($debug = false) {
			return parent::get_ordercount($debug);
		}
		
		public function get_orders($debug = false) {
			return parent::get_orders($debug);
		}
		
		/* =============================================================
			OrderPanelInterface Functions
		============================================================ */
		public function setup_pageurl() {
			parent::setup_pageurl();
			$this->pageurl->path = DplusWire::wire('config')->pages->ajax."load/orders/salesrep/";
			$this->pageurl->query->remove('display');
			$this->pageurl->query->remove('ajax');
			$this->paginationinsertafter = 'salesrep';
		}
	}
