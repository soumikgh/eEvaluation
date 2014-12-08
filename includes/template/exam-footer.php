			</div> <!-- /span10 -->
			<div class="span2">
			<?php require(_BASE_URL_ . "/exam-sidebar.php");?>
			</div>  <!-- /span2 -->
		</div> <!-- /row-fluid -->
	</div> <!-- /container-fluid -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.countdown.min.js"></script>
    <script type="text/javascript">
		$(function () {
			var austDay = new Date();
			$('#countdown').countdown({until: +<?php echo $time_left; ?>, expiryUrl: '<?php echo _REMOTE_URL_; ?>/post-exam.php', alwaysExpire: true, onTick: highlightLast10});
			
			function highlightLast10(periods) { 
				if ($.countdown.periodsToSeconds(periods) <= 10) { 
					$(this).addClass('highlight'); 
				} 
			}
			
			$("#rulePopover").popover({
				placement: 'bottom',
				title: "Exam Rules",
				html: true,
				content: '<ul><li>To save your answer, click on the "Previous" or "Next" buttons.</li><li>If you click on a question in the right-sidebar, the answer of your current question will not be saved.<li>Number of questions: <?php echo count($_SESSION["qs"]); ?>.</li><li>Marks awarded for a correct answer: <?php echo $marks["pos_marks"]; ?>.</li><li>Marks deducted for a wrong answer: <?php echo $marks["neg_marks"]; ?>.</li><li>Total marks: <?php echo (count($_SESSION["qs"]) * $marks["pos_marks"]); ?>.</li></ul>'
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
