<?php
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	 if (isset($_GET['bnk']) && $_GET['bnk'] != "" )
	{
		$aColumns = array( 'Name' , 'BankID' );
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "BankID";	
		/* DB table to use */
		$sTable = "bank";
		$table = 'bank';
	}
	else if (isset($_GET['sts']) && $_GET['sts'] != "" )
	{
		$aColumns = array( 'Status' , 'ID' );
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "ID";	
		/* DB table to use */
		$sTable = "status";
		$table = 'status';
	}
	else if (isset($_GET['stkc']) && $_GET['stkc'] != "" )
	{
		$aColumns = array('Category' , 'ID');
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "ID";	
		/* DB table to use */
		$sTable = "`stock category`";
		$table = 'cate';
	}
	
	
	
	
	/* Database connection information */
	$gaSql['user']       = "root";
	$gaSql['password']   = "!smartview13#";
	$gaSql['db']         = "_store";
	$gaSql['server']     = "localhost";
	
	/* REMOVE THIS LINE (it just includes my SQL connection user/pass) */
	//include( $_SERVER['DOCUMENT_ROOT']."/datatables/mysql.php" );
	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * Local functions
	 */
	function fatal_error ( $sErrorMessage = '' )
	{
		header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
		die( $sErrorMessage );
	}

	
	/* 
	 * MySQL connection
	 */
	if ( ! $gaSql['link'] = mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) )
	{
		fatal_error( 'Could not open connection to server' );
	}

	if ( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
	{
		fatal_error( 'Could not select database ' );
	}

	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
			intval( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
					($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
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
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
			{
				$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(`".$sIndexColumn."`)
		FROM   $sTable
	";
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
	 if ( $aColumns[$i] == "ID" )
			{
			$row[] = "<div class='hidden-phone visible-desktop btn-group'>
							<span class='btn btn-mini'><a href='setting.php?$table=1&ID=".$aRow[ $aColumns[$i] ]."' class='white'><i class='icon-edit' data-rel='tooltip' title='Edit' data-placement='left'></i></a></span>
							<span class='btn btn-mini btn-danger'><i class='icon-trash'></i></span>
						</div>
						<div class='hidden-desktop visible-phone'>
						<div class='inline position-relative'>
								<button class='btn btn-minier btn-yellow dropdown-toggle' data-toggle='dropdown'><i class='icon-caret-down icon-only'></i></button>
								<ul class='dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close'>
									<li><a href='setting.php?$table=1&ID=".$aRow[ $aColumns[$i] ]."' class='tooltip-success' data-rel='tooltip' title='Edit' data-placement='left'><span class='green'><i class='icon-edit'></i></span></a></li>
									<li><a href='#' class='tooltip-error' data-rel='tooltip' title='Delete' data-placement='left'><span class='red'><i class='icon-trash'></i></span> </a></li>
								</ul>
							</div>
						</div>";
			}
			else if ( $aColumns[$i] == "BankID" )
			{
			$row[] = "<div class='hidden-phone visible-desktop btn-group'>
							<span class='btn btn-mini'><a href='setting.php?$table=1&ID=".$aRow[ $aColumns[$i] ]."' class='white'><i class='icon-edit' data-rel='tooltip' title='Edit' data-placement='left'></i></a></span>
							<span class='btn btn-mini btn-danger'><i class='icon-trash'></i></span>
						</div>
						<div class='hidden-desktop visible-phone'>
						<div class='inline position-relative'>
								<button class='btn btn-minier btn-yellow dropdown-toggle' data-toggle='dropdown'><i class='icon-caret-down icon-only'></i></button>
								<ul class='dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close'>
									<li><a href='setting.php?$table=1&ID=".$aRow[ $aColumns[$i] ]."' class='tooltip-success' data-rel='tooltip' title='Edit' data-placement='left'><span class='green'><i class='icon-edit'></i></span></a></li>
									<li><a href='#' class='tooltip-error' data-rel='tooltip' title='Delete' data-placement='left'><span class='red'><i class='icon-trash'></i></span> </a></li>
								</ul>
							</div>
						</div>";
			}
			else if ( $aColumns[$i] != ' ' )
			{
				/* General output */
				$row[] = $aRow[ $aColumns[$i] ];
			}
			
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>