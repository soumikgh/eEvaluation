      <hr />

      <!-- <footer>
		<p class="pull-right"><a href="#"><i class="icon-chevron-up"></i></a></p>
        <p>&copy; <?php echo date("Y"); ?> eEvaluation System</p>
      </footer> -->

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo _REMOTE_URL_;?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo _REMOTE_URL_;?>/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
		$( document ).ready(function() {
			$(document).on("click", ".viewdetrepbtn", function () {
				$.post("includes/admin-process.php",  { user_id: $(this).data('id'), mode: 'showdetailres' }, function(data){ $("#viewdetrep").html(data); });
				return false;
			});
		});
    </script>


	<script type="text/javascript">
	//<![CDATA[
	var sc_project=8695174; 
	var sc_invisible=1; 
	var sc_security="7564b198"; 
	var scJsHost = (("https:" == document.location.protocol) ?
	"https://secure." : "http://www.");
	document.write("<sc"+"ript type='text/javascript' src='" +
	scJsHost+
	"statcounter.com/counter/counter_xhtml.js'></"+"script>");
	//]]>
	</script>
	<noscript><div class=""><img class=""
	src="http://c.statcounter.com/8695174/0/7564b198/1/"
	alt="" /></div></noscript>
	
	
  </body>
</html>
