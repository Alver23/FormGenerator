<?php

  $serverName = "(local)\sqlexpress"; 

  /* Connect to SQL Server. */ 
  $conn = new PDO( "sqlsrv:server=$serverName;Database=DbLabMqm" );

  /* Get products by querying against the product name.*/ 
  $tsql = "SELECT ProductID, Name, Color, Size, ListPrice FROM Production.Product";

  /* Execute the query. */ 
  $getProducts = $conn->query( $tsql );

  /* Loop thru recordset and display each record. */ 
  while( $row = $getProducts->fetch( PDO::FETCH_ASSOC ) ) 
  { 
    print_r( $row ); 
  }

  /* Free the statement and connection resource. */
  $getProducts = NULL;
  $conn = NULL;

?>

