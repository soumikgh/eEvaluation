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
    <script src="<?php echo _REMOTE_URL_;?>/assets/js/bootstrap-notify.js"></script>
    <script src="<?php echo _REMOTE_URL_;?>/assets/js/jquery.validate.min.js"></script>
    <script src="<?php echo _REMOTE_URL_;?>/assets/js/bootstrapSwitch.min.js"></script>
	<script type="text/javascript">
		$(function () {
			
			/*	Admin permissions	*/
			var role = '<?php echo $_SESSION['user']->get_user_role(); ?>';
			if(role == 'Q') {
				$('#sidenav a[href="#quesmin"]').tab('show');
				$('#quesTabs a[href="#quesadd"]').tab('show');
			}
			else if(role == 'E') {
				$('#sidenav a[href="#exammin"]').tab('show');
				$('#examTabs a[href="#examedit"]').tab('show');
			}				
			
			/*	   User search		*/
			$("#usersearchform").submit(function(event) {  
				
				 /* stop form from submitting normally */
				event.preventDefault();
				var fname = $("#usersearchform #fname").val();
				var lname = $("#usersearchform #lname").val();
				var email = $("#usersearchform #email").val();
				if(fname == '' && lname == '' && email == '')
				{
					showalert('error','Specify at least one criteria before searching.');
					return false;
				}
				
				$.post("includes/admin-process.php", $(this).serialize(), function(data){ $("#usersearchresults").html(data); });
				return false;
			});  
			
			//$('#ueditModal').on('show', function() { Commented since Modal event cannot get us the user id
			$(document).on("click", ".ueditselectbtn", function () {
				$.post("includes/admin-process.php", { user_id: $(this).data('id'), mode: 'userinfo' }, function(data){
					
				// Can reset form fields here if reqd	
				
				$("#ueditForm input[name=fname]").val(data.user_fname);
				$("#ueditForm input[name=lname]").val(data.user_lname);
				$("#ueditForm input[name=email]").val(data.user_email);
				$("#ueditForm select[name=role]").val(data.user_role);
				if(data.user_status == 'A')
				{
					$('input[name="status"]')[0].checked = true;
				}
				else {$('input[name="status"]')[1].checked = true;}
				$("#ueditForm select[name=allow]").val(data.user_allow);
				if(data.user_sex == 'M')
				{
					$('#ueditForm input[name="sex"]')[0].checked = true;
				}
				else {$('#ueditForm input[name="sex"]')[1].checked = true;}
				$("#ueditForm textarea[name=addr]").val(data.user_addr);
				$("#ueditForm input[name=city]").val(data.user_city);
				$("#ueditForm select[name=state]").val(data.user_state);
				$("#ueditForm input[name=pin]").val(data.user_pin);
				$("#ueditForm input[name=pnumber]").val(data.user_pnumber);
				$("#ueditForm input[name=id]").val(data.user_id);
				}, "json");
				return false;
			});
			
			$("#ueditForm").validate ({
			
				rules: {
					fname: {
						minlength: 2,
						required: true
					},
					lname: {
						minlength: 2,
						required: true
					},
					email: {
						required: true,
						email: true
					},
					addr: {
						minlength: 2,
						required: true
					},
					city: {
						minlength: 2,
						required: true
					},	
					pin: {
						minlength: 6,
						digits: true,
						required: true
					},	
					pnumber: {
						minlength: 10,
						digits: true,
						required: true
					}
				},
				
				errorClass:'error help-inline',
				errorElement:'span',
				highlight: function (element, errorClass) { 
					$(element).parents("div.control-group").addClass(errorClass); 

				}, 
				unhighlight: function (element, errorClass, validClass) { 
					$(element).parents("div.control-group").removeClass(errorClass); 
				},
				
				submitHandler: function(form) {
					$.post("includes/admin-process.php", $("#ueditForm").serialize(), function(data) {
						if(data.status == '0')
						{
							showalert('error',data.message);
						}
						else
						{
							$('#ueditModal').modal('hide');
							$('#usersearchform').submit();
							showalert('success', data.message);
							
						}
					}, "json");
				}				
			});
			
			$("#uaddForm").validate ({
			
				rules: {
					fname: {
						minlength: 2,
						required: true
					},
					lname: {
						minlength: 2,
						required: true
					},
					email: {
						required: true,
						email: true
					},
					pass: {
						minlength: 5,
						required: true
					},
					addr: {
						minlength: 2,
						required: true
					},
					city: {
						minlength: 2,
						required: true
					},
					state: {
						required: true
					},	
					pin: {
						minlength: 6,
						digits: true,
						required: true
					},	
					pnumber: {
						minlength: 10,
						digits: true,
						required: true
					}
				},
				
				errorClass:'error help-inline',
				errorElement:'span',
				highlight: function (element, errorClass) { 
					$(element).parents("div.control-group").addClass(errorClass); 

				}, 
				unhighlight: function (element, errorClass, validClass) { 
					$(element).parents("div.control-group").removeClass(errorClass); 
				},
				
				submitHandler: function(form) {
					$.post("includes/admin-process.php", $("#uaddForm").serialize(), function(data) {
						if(data.status == '0')
						{
							showalert('error',data.message);
						}
						else
						{
							showalert('success', data.message);							
						}
					}, "json");
				}				
			});
			
			$("#qaddForm").validate ({		
				rules: {
					q_body: {
						minlength: 2,
						required: true
					},
					q_ans1: {
						minlength: 1,
						required: true
					},
					q_ans2: {
						minlength: 1,
						required: true
					},
					q_ans3: {
						minlength: 1,
						required: true
					},
					q_ans4: {
						minlength: 1,
						required: true
					}
				},			
				
				errorClass:'error help-inline',
				errorElement:'span',
				highlight: function (element, errorClass) { 
					$(element).parents("div.control-group").addClass(errorClass); 

				}, 
				unhighlight: function (element, errorClass, validClass) { 
					$(element).parents("div.control-group").removeClass(errorClass); 
				},
				
				submitHandler: function(form) {
					$.post("includes/admin-process.php", $("#qaddForm").serialize(), function(data) {
						if(data.status == '0')
						{
							showalert('error',data.message);
						}
						else
						{
							showalert('success', data.message);							
						}
					}, "json");
				}				
			});
			
			$("#qeditForm").validate ({		
				rules: {
					q_body: {
						required: true
					}
				},
				
				errorClass:'error help-inline',
				errorElement:'span',
				highlight: function (element, errorClass) { 
					$(element).parents("div.control-group").addClass(errorClass); 

				}, 
				unhighlight: function (element, errorClass, validClass) { 
					$(element).parents("div.control-group").removeClass(errorClass); 
				},
				
				submitHandler: function(form) {
					$.post("includes/admin-process.php", $("#qeditForm").serialize(), function(data) {$("#qeditresults").html(data)});
				}
			});
			
			$(document).on("click", ".qeditbtn", function () {
				$.post("includes/admin-process.php", { q_id: $(this).data('id'), mode: 'qeditinfo' }, function(data){

					$("#qeditdisplayForm input[name=q_id]").val(data.q_id);
					$("#qeditdisplayForm textarea[name=q_body]").val(data.q_body);
					$("#qeditdisplayForm input[name=q_ans1]").val(data.q_ans1);
					$("#qeditdisplayForm input[name=q_ans2]").val(data.q_ans2);
					$("#qeditdisplayForm input[name=q_ans3]").val(data.q_ans3);
					$("#qeditdisplayForm input[name=q_ans4]").val(data.q_ans4);
					$("#qeditdisplayForm select[name=q_correct_ans]").val(data.q_correct_ans);
					if(data.q_allow == '1')
					{
						$('#qeditdisplayForm input[name=q_allow]')[0].checked = true;
					}
					else {
						$('#qeditdisplayForm input[name=q_allow]')[1].checked = true;
					}
				}, "json");
				return false;
			});
			
			$("#qeditdisplayForm").validate ({		
				rules: {
					q_body: {
						minlength: 2,
						required: true
					},
					q_ans1: {
						minlength: 1,
						required: true
					},
					q_ans2: {
						minlength: 1,
						required: true
					},
					q_ans3: {
						minlength: 1,
						required: true
					},
					q_ans4: {
						minlength: 1,
						required: true
					}
				},
				
				errorClass:'error help-inline',
				errorElement:'span',
				highlight: function (element, errorClass) { 
					$(element).parents("div.control-group").addClass(errorClass); 

				}, 
				unhighlight: function (element, errorClass, validClass) { 
					$(element).parents("div.control-group").removeClass(errorClass); 
				},
				
				submitHandler: function(form) {
					$.post("includes/admin-process.php", $("#qeditdisplayForm").serialize(), function(data) {
						if(data.status == '0')
						{
							showalert('error', data.message);
						}
						else
						{
							$('#qeditModal').modal('hide');
							$('#qeditForm').submit();
							showalert('success', data.message);						
						}
					}, "json");
				}				
			});
			
			$("#qdelForm").validate ({		
				rules: {
					q_body: {
						required: true
					}
				},
				
				errorClass:'error help-inline',
				errorElement:'span',
				highlight: function (element, errorClass) { 
					$(element).parents("div.control-group").addClass(errorClass); 

				}, 
				unhighlight: function (element, errorClass, validClass) { 
					$(element).parents("div.control-group").removeClass(errorClass); 
				},
				
				submitHandler: function(form) {
					$.post("includes/admin-process.php", $("#qdelForm").serialize(), function(data) {$("#qdelresults").html(data)});
				}
			});
			
			$(document).on("click", ".qdelbtn", function () {
				$("#qdeldisplayForm input[name=q_id]").val($(this).data('id'));
				return false;
			});
			
			$("#qdeldisplayForm").validate ({		
				submitHandler: function(form) {
					$.post("includes/admin-process.php", $("#qdeldisplayForm").serialize(), function(data) {
						if(data.status == '0')
						{
							showalert('error', 'Failed to delete question as it\'s referenced by a stored answer');
						}
						else
						{
							$('#qdelModal').modal('hide');
							$('#qdelForm').submit();
							showalert('success', data.message);					
						}
					}, "json");
				}				
			});
			
			var refreshExamInfo = function() {
				$.post("includes/admin-process.php", { mode: 'examinfo' }, function(data){
					$("#examdetailsform input[name=exam_title]").val(data.exam_title);
					$("#examdetailsform input[name=exam_no_of_qs]").val(data.exam_no_of_qs);
					$("#examdetailsform input[name=exam_time]").val(data.exam_time);
					$("#examdetailsform input[name=pos_marks]").val(data.pos_marks);
					$("#examdetailsform input[name=neg_marks]").val(data.neg_marks);
					if(data.exam_allow == 1){
						$("#examdetailsform").find('input, button').prop("disabled", true);
						showalert('warning', 'Exam settings cannot be changed since exam is ongoing');
					}
					else {
						$("#examdetailsform").find('input, button').prop("disabled", false);
						$('#examSwitch').bootstrapSwitch('setState', false);
					}		
				}, "json");
			}
			
			$('#editExamTab').on('shown', function() {
				refreshExamInfo();
			});
			
			$('#manageExamNav').on('shown', function() {
				refreshExamInfo();
			});
			
			$("#examdetailsform").validate ({		
				rules: {
					exam_title: {
						minlength: 2,
						required: true
					},
					exam_no_of_qs: {
						number: true,
						minlength: 1,
						required: true
					},
					exam_time: {
						number: true,
						minlength: 1,
						required: true
					},
					pos_marks: {
						number: true,
						minlength: 1,
						required: true
					},
					neg_marks: {
						number: true,
						minlength: 1,
						required: true
					}
				},
				
				errorClass:'error help-inline',
				errorElement:'span',
				highlight: function (element, errorClass) { 
					$(element).parents("div.control-group").addClass(errorClass); 

				}, 
				unhighlight: function (element, errorClass, validClass) { 
					$(element).parents("div.control-group").removeClass(errorClass); 
				},
				
				submitHandler: function(form) {
					$.post("includes/admin-process.php", $("#examdetailsform").serialize(), function(data) {
						if(data.status == '0')
						{
							showalert('error', data.message);
						}
						else
						{
							showalert('success', data.message);							
						}
					}, "json");
				}				
			});
			
			$('#examSwitch').on('switch-change', function (e, data) {
				$.post("includes/admin-process.php", { value: data.value, mode: 'examtoggle' }, function(data){
					if(data.status == '0')
					{
						showalert('error', data.message);
					}
				}, "json");
			});
			
			/* Show top 5 results */
			
			$.post("includes/admin-process.php", { mode: 'top5res' }, function(data){ $("#userresresults").html(data); });
			
			$("#ressearchform").submit(function(event) {  
				
				 /* stop form from submitting normally */
				event.preventDefault();
				var fname = $("#ressearchform #fname").val();
				var lname = $("#ressearchform #lname").val();
				var email = $("#ressearchform #email").val();
				if(fname == '' && lname == '' && email == '')
				{
					showalert('error','Specify at least one criteria before searching.');
					return false;
				}
				
				$.post("includes/admin-process.php", $(this).serialize(), function(data){ $("#userresresults").html(data); });
				return false;
			});
			
			$(document).on("click", ".uresselectbtn", function () {
				$.post("includes/admin-process.php",  { user_id: $(this).data('id'), mode: 'showmainres' }, function(data){ $("#mainres").html(data); console.log(data);});
				$.post("includes/admin-process.php",  { user_id: $(this).data('id'), mode: 'showdetailres' }, function(data){ $("#detailres").html(data); console.log(data); });
			});
			
			$(document).on("click", ".uresdelselectbtn", function () {			
				$("#uresdelform input[name=user_id]").val($(this).data('id'));
			});
			
			$("#uresdelform").submit(function(event) { 
				event.preventDefault();
				$.post("includes/admin-process.php", $(this).serialize(), function(data) {
					if(data.status == '0')
					{
						showalert('error', data.message);
					}
					else
					{
						$('#uresdelModal').modal('hide');
						$('#ressearchform').submit();
						showalert('success', data.message);					
					}
				}, "json");
			});
			
			$(document).on("click", ".udelselectbtn", function () {			
				$("#udelform input[name=user_id]").val($(this).data('id'));
			});
			
			$("#udelform").submit(function(event) { 
				event.preventDefault();
				$.post("includes/admin-process.php", $(this).serialize(), function(data) {
					if(data.status == '0')
					{
						showalert('error', data.message);
					}
					else
					{
						$('#udelModal').modal('hide');
						$('#usersearchform').submit();
						showalert('success', data.message);					
					}
				}, "json");
			});
			
			$(document).on("click", ".ubanselectbtn", function () {			
				$("#ubanform input[name=user_id]").val($(this).data('id'));
				$.post("includes/admin-process.php", { user_id: $(this).data('id'), mode: 'getbanstatus' }, function(data) {
					$("#ubanform select[name=user_allow]").val(data.user_allow);
				}, "json");
			});
			
			$(document).on("click", "#ubanBtn", function () {
				$.post("includes/admin-process.php", $("#ubanform").serialize(), function(data) {
					console.log(data);
					if(data.status == '0')
					{
						showalert('error', data.message);
					}
					else
					{
						$('#ubanModal').modal('hide');
						showalert('success', data.message);							
					}					
				}, "json");
			});
			
			$(document).on("click", ".umakeadmselectbtn", function () {			
				$("#umakeadmform input[name=user_id]").val($(this).data('id'));
				$.post("includes/admin-process.php", { user_id: $(this).data('id'), mode: 'getadmstatus' }, function(data) {
					$("#umakeadmform select[name=user_role]").val(data.user_role);
				}, "json");
			});
			
			$(document).on("click", "#umakeadmBtn", function () {
				$.post("includes/admin-process.php", $("#umakeadmform").serialize(), function(data) {
					if(data.status == '0')
					{
						showalert('error', data.message);
					}
					else
					{
						$('#umakeadmModal').modal('hide');
						showalert('success', data.message);							
					}					
				}, "json");
			});
		});
		
		function showalert(type, message) {
			$('.center').notify({
						type: type,
						message: { text: message },
						fadeOut: { enabled: true, delay: 3000 }
						}).show();
		}
		
		function resetForm($form) {
			$form.find('input:text, input:password, input:file, select, textarea').val('');
			$form.find('input:radio, input:checkbox')
				 .removeAttr('checked').removeAttr('selected');
		}
		
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
