<div class="text-center well well-small"><a class="btn btn-large btn-danger" href="#endexamModal" data-toggle="modal">End exam</a></div>
Sort by -
<ul class="nav nav-tabs" id="myTab">
	<li  class="active"><a href="#order" data-toggle="tab">Order</a></li>
	<li><a href="#answered" data-toggle="tab">Answered</a></li>
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active" id="order">
		<div class="accordion" id="accordion1">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
						Questions
					</a>
				</div>
				<div id="collapseOne" class="accordion-body collapse in">
					<div class="accordion-inner">
						<ul>
							
<?php
for($i=0; $i < count($_SESSION["qs"]); $i++)
{
	if(array_key_exists($i, $_SESSION["ans"])) // If answered
	{
		echo '<li><a href="./exam.php?qno=' . ($i + 1) . '">Question ' . ($i + 1) . '</a> <i class="icon-ok"></i></li>';
	}
	elseif(!array_key_exists($i, $_SESSION["viewed"])) // If not viewed
	{
		echo '<li> <a href="./exam.php?qno=' . ($i + 1) . '"><strong>Question ' . ($i + 1) . '</strong></a></li>';
	}
	else
	{
		echo '<li> <a href="./exam.php?qno=' . ($i + 1) . '">Question ' . ($i + 1) . '</a></li>';	
	}
}
?>	
							
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="tab-pane fade in" id="answered">
		<div class="accordion" id="accordion2">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
						Answered
					</a>
				</div>
				<div id="collapseTwo" class="accordion-body collapse">
					<div class="accordion-inner">
						<ul>
							
<?php
ksort($_SESSION["ans"]);
foreach($_SESSION["ans"] as $key => $value)
{
	echo '<li><a href="./exam.php?qno=' . ($key + 1) . '">Question ' . ($key + 1) . '</a> <i class="icon-ok"></i></li>';
}
?>	
							
						</ul>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapseThree">
						Unanswered
					</a>
				</div>
				<div id="collapseThree" class="accordion-body collapse in">
					<div class="accordion-inner">
						<ul>
							
<?php
for($i=0; $i < count($_SESSION["qs"]); $i++)
{
	if(!array_key_exists($i, $_SESSION["ans"])) // If unanswered
	{
		if(!array_key_exists($i, $_SESSION["viewed"])) // If not viewed
		{
			echo '<li> <a href="./exam.php?qno=' . ($i + 1) . '"><strong>Question ' . ($i + 1) . '</strong></a></li>';
		}
		else
		{
			echo '<li> <a href="./exam.php?qno=' . ($i + 1) . '">Question ' . ($i + 1) . '</a></li>';
		}
	}
}
?>	
							
						</ul>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
