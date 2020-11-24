<?php
    require_once "dbconnect.php";
    require_once "util.php";
    require_once "update.php";

    if(isset($_POST['checkinEnter']))
    {
        insertCheckin();
    }
    if(isset($_POST['checkoutEnter']))
    {
        insertCheckout();
    }
?>

<div class="2u"></div>
<div class="8u">
    <button type="button" class="collapsible">Check-in/out</button>
    <div class="content">
        <div class="12u$">
			<div class='tabcontainer'>
				<div class="tab">
					<button id="checkInTab" class="tablinks active" onclick="changeTab(event, 'checkIn', false,'checkInTab')">Judge Check-In</button>
					<button id="checkOutTab" class="tablinks" onclick="changeTab(event, 'checkOut', false,'checkOutTab')">Judge Check-Out</button>
				</div>
				<div id="checkIn" class="tabcontent">
					<form method="post" action="">
						<strong>Click Below to Check In</strong>
						<ul class="actions">
							<input name="checkinEnter" class="btn special" type="submit" value="Check-In" />
						</ul>
					</form>
				</div>
				<div id="checkOut" class="tabcontent">
					<form method="post" action="">
						<strong>Click Below to Check Out</strong>
						<ul class="actions">
							<input name="checkoutEnter" class="btn special" type="submit" value="Check-Out" />
						</ul>
					</form>
				</div>
			</div>
        </div>
    </div>
</div>
<div class="2u$"></div>