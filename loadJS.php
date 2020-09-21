<?php

echo ('	<!-- jQuery first, then Bootstrap Bundle JS (Bundle includes Popper) -->
		
		<script type="text/javascript" charset="utf8" src="/js/jquery 3.5.1/jquery-3.5.1.min.js" ></script>
		<script type="text/javascript" charset="utf8" src="/js/bootstrap 4.5/bootstrap.bundle.min.js" ></script>
		<script type="text/javascript" charset="utf8" src="/js/sourcery.js"></script>');
		
if (basename($_SERVER['PHP_SELF']) == 'teamView.php') //* Returns The Current PHP File Name */
{
	echo(		
		'<script type="text/javascript" charset="utf8" src="/js/tricks.js"></script>
		
		<!-- For appForm -->
		
		<!-- DataTables -->
		<script type="text/javascript" charset="utf8" src="/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" charset="utf8" src="/js/dataTables.fixedHeader.min.js"></script>
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
		<script type="text/javascript" charset="utf8" src="/js/moment.min.js"></script>
		<script type="text/javascript" charset="utf8" src="/js/jquery.inputmask.min.js"></script>
		<script type="text/javascript" charset="utf8" src="/js/datetime-moment.js"></script>	
		
		<!-- For appInterface -->		
		
		<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>');
	};
	?>	