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
			$url = new Purl\Url($this->pageurl->getUrl());
			$url->path = DplusWire::wire('config')->pages->ajax."load/orders/salesrep/";
			$url->query->remove('display');
			$url->query->remove('ajax');
			$this->paginationinsertafter = 'salesrep';
			return $url;
		}
	}
