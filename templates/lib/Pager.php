<?PHP
class Pager {
	private $con;
	private $columns;
	private $id;
	private $table;

	public function __construct($con, $table, $columns, $id) {
		$this->Pager($con, $table, $columns, $id);
	}

	//PHP<5 compatibility
	public function Pager($con, $table, $columns, $id) {
		$this->con = $con;
		$this->table = $table;
		$this->columns = $columns;
		$this->id = $id;
	}

	public function getJSON() {
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * Easy set variables
		 */

		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */
		$aColumns = $this->columns;

		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = $this->id;

		/* DB table to use */
		$sTable = $this->table;
		$sLimit = "";
		if (isset($_REQUEST['iDisplayStart'])
				&& $_REQUEST['iDisplayLength'] != '-1') {
			$sLimit = "LIMIT " . $this->con->escape($_REQUEST['iDisplayStart'])
					. ", " . $this->con->escape($_REQUEST['iDisplayLength']);
		}

		/*
		 * Ordering
		 */
		$sOrder = "";
		if (isset($_REQUEST['iSortCol_0'])) {
			$sOrder = "ORDER BY  ";
			for ($i = 0; $i < intval($_REQUEST['iSortingCols']); $i++) {
				if ($_REQUEST['bSortable_'
						. intval($_REQUEST['iSortCol_' . $i])] == "true") {
					$sOrder .= $this->con
							->quoteColumn(
									$aColumns[intval(
											$_REQUEST['iSortCol_' . $i])])
							. "
						" . $this->con->escape($_REQUEST['sSortDir_' . $i])
							. ", ";
				}
			}

			$sOrder = substr_replace($sOrder, "", -2);
			if ($sOrder == "ORDER BY") {
				$sOrder = "";
			}
		}
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		$sWhere = "";
		if (isset($_REQUEST['sSearch']) && $_REQUEST['sSearch'] != "") {
			$sWhere = "WHERE (";
			for ($i = 0; $i < count($aColumns); $i++) {
				$sWhere .= $this->con->quoteColumn($aColumns[$i]) . " LIKE '%"
						. $this->con->escape($_REQUEST['sSearch']) . "%' OR ";
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}
		$rangeSeparator = "~";
		if (isset($_REQUEST['sRangeSeparator']))
			$rangeSeparator = $_REQUEST['sRangeSeparator'];
		/* Individual column filtering */
		for ($i = 0; $i < count($aColumns); $i++) {
			if (@$_REQUEST['bSearchable_' . $i] == "true" && @$_REQUEST['sSearch_' . $i] != ''){
				if ($sWhere == "") {
					$sWhere = "WHERE ";
				} else {
					$sWhere .= " AND ";
				}
				$columnFilterValue = $this->con
						->escape($_REQUEST['sSearch_' . $i]);
				// check for values range
				if (!empty($rangeSeparator)
						&& strstr($columnFilterValue, $rangeSeparator)) {
					// get min and max
					preg_match("/(.*)\~(.*)/", $columnFilterValue,
							$columnFilterRangeMatches);
					//try to convert date (can to be a number)
					$columnFilterRangeMatches[1] = displayDate(
							$columnFilterRangeMatches[1]) == '' ? $columnFilterRangeMatches[1]
							: displayDate($columnFilterRangeMatches[1]);
					$columnFilterRangeMatches[2] = displayDate(
							$columnFilterRangeMatches[2]) == '' ? $columnFilterRangeMatches[2]
							: displayDate($columnFilterRangeMatches[2]);
					// get filter
					if (empty($columnFilterRangeMatches[1])
							&& empty($columnFilterRangeMatches[2]))
						$sWhere .= " 0 = 0 ";
					else if (!empty($columnFilterRangeMatches[1])
							&& !empty($columnFilterRangeMatches[2]))
						$sWhere .= $this->con->quoteColumn($aColumns[$i])
								. " BETWEEN '" . $columnFilterRangeMatches[1]
								. "' AND '" . $columnFilterRangeMatches[2]
								. "' ";
					else if (empty($columnFilterRangeMatches[1])
							&& !empty($columnFilterRangeMatches[2]))
						$sWhere .= $this->con->quoteColumn($aColumns[$i])
								. " <= '" . $columnFilterRangeMatches[2] . "' ";
					else if (!empty($columnFilterRangeMatches[1])
							&& empty($columnFilterRangeMatches[2]))
						$sWhere .= $this->con->quoteColumn($aColumns[$i])
								. " >= '" . $columnFilterRangeMatches[1] . "' ";
				} else {
					if ($columnFilterValue != '')
						$sWhere .= $this->con->quoteColumn($aColumns[$i])
								. " LIKE '%" . $columnFilterValue . "%' ";
				}
			}
		}
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS {$this->id}, "
				. str_replace(" , ", " ",
						implode(", ",
								array_map(array($this->con, "quoteColumn"),
										$aColumns)))
				. "
			FROM   $sTable
			$sWhere
			$sOrder
			$sLimit
		";
		$rResult = $this->con->query($sQuery);
		/* Data set length after filtering */
		$sQuery = "
			SELECT FOUND_ROWS() AS FOUND
		";
		$rResultFilterTotal = $this->con->query($sQuery, 1);
		$aResultFilterTotal = $rResultFilterTotal;
		//$iFilteredTotal = $aResultFilterTotal[0][0];
		$iFilteredTotal = $aResultFilterTotal[0]['FOUND'];

		/* Total data set length */
		$sQuery = "
			SELECT COUNT(" . $sIndexColumn . ") AS cant
			FROM   $sTable
		";
		$rResultTotal = $this->con->query($sQuery, 1);
		$aResultTotal = $rResultTotal;
		//$iTotal = $aResultTotal[0][0];
		$iTotal = $aResultTotal[0]['cant'];
		/*
		 * Output
		 */
		$output = array("sEcho" => intval($_REQUEST['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());
		foreach ($rResult as $aRow) {
			$row = array();
			// Add the row ID to the object
			$row['DT_RowId'] = 'param_' . $aRow[$this->id];
			for ($i = 0; $i < count($aColumns); $i++) {
				if (stripos($aColumns[$i], "date") !== false) {
					/* Special output formatting for 'version' column */
					$row[] = $aRow[$aColumns[$i]];
				} else //if ( $aColumns[$i] != ' ' )
 {
					/* General output */
					$row[] = $aRow[$aColumns[$i]];
				}
			}
			$output['aaData'][] = $row;
		}

		return json_encode($output);
	}
}

?>
