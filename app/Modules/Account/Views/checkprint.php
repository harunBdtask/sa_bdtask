<?php 
use App\Libraries\Numberconverter;
$numberToword = new Numberconverter();
?>
<?php   $inwords =preg_replace( "/\r|\n/", "", $numberToword->AmountInWords($amount) ); 
 ?>
<script>
	// Check #1.
	
	var checkData1 = { 	
		"date"          : "<?php echo $date?>", // The date when the check is first eligible to be cashed/deposited. e.g. 05/01/2013
		"DateFormat"    :"",
		"payTo"         : "<?php echo $payto?>", // To whom the check should be made out. e.g. Michael Harry Scepaniak
		"Sortform"      : "or Bearer",
		"amountNbr"     : "<?php echo $amount?>", // The amount of the check, as a number. e.g. 13,100.00
		"amountTxt"     : "<?php echo $inwords?>", // The amount of the check, written out long-form. e.g. 
		"memo"          : "signature" // A short note to include on the check. e.g. Just a small thank you.
		};



		
	/*
	 * ============================================
	 * NO NEED TO MODIFY ANYTHING BELOW THIS POINT.
	 * ============================================
	 */

	jQuery(document).ready(function() {
		populateCheck(1, checkData1);
		
	})

	var populateCheck = function(checkNbr, checkData)
	{
		jQuery("div#check-" + checkNbr + "-date-box").html(checkData.date);
		jQuery("div#check-" + checkNbr + "-pay-to-box").html(checkData.DateFormat);
		jQuery("div#checkname").html(checkData.payTo);
		jQuery("div#checknum").html(checkData.Sortform);
		jQuery("div#amountnmb").html(checkData.amountNbr);
		jQuery("div#amounttxt").html(checkData.amountTxt);
		
		jQuery("div#check-" + checkNbr + "-memo-box").html(checkData.memo);
	}
	

</script>

<style>
	
      div#printcheck{
            position: absolute;
            width: 800px;
      }
	

	
	div#check-1-amount-nbr-box
	{
		left: 600px;
		top: 45px;
	}
	
	div#check-1-amount-txt-box
	{
		left: 0px;
		top: 83px;
	}
	
	div#check-1-pay-to-address-box
	{
		left: 30px;
		top: 98px;
	}
	


	

      pre {
    background-color: #fff;
    color: #000;
    padding: 10px;
    border-radius: 5px;
    text-align: left;
    font-size: 14px;
    overflow: hidden;
    border: 0px solid #2c3136;
}
</style>
<div class="col-sm-12 text-right">
<div class="row align-items-center" style="padding-top: 10px;">
<button class="btn btn-warning" onclick="printContent('printcheck');">print</button> <a href="<?php echo base_url('account/accounts/add_checkinfo'); ?>" class="btn btn-info btn-sm mr-1 ml-2"><i class="fas fa-angle-double-left mr-1"></i><?php echo get_phrases(['back']);?></a></div></div>
<hr>
<div class="row" >
    <div class="col-sm-12" id="printcheck">
 <div class="card">
      <div class="card-body">
      
      <div class="text-right">
	<div id="check-1-date-box" class="checkprint" style="left: 600px;top: 30px;margin-top:40px;letter-spacing: 11px;font-weight:bold;font-size:18px;">
	
	</div>
	<div id="check-1-pay-to-box" class="checkprint" style="left: 30px;top: 50px;letter-spacing: 9.5px;font-size:18px;">
	
	</div>
	<div id="check--pay-to-box" class="checkprint" style="margin-top:30px;">
		<div style="float:left;font-weight:bold;padding-left:30px;font-size:18px;" id="checkname"></div>
		<div style="float:right" id="checknum"></div>
	
	</div>
      </div>
<br>
	<div id="check--pay-to-box" class="checkprint" style="margin-top:15px;">
	      <div style="float:left;width:70%;padding-left:100px;font-weight:bold;font-size:18px;" id="amounttxt"></div>
		<div style="float:right;width:30%;padding-right:70px;padding-top:10px;font-weight:bold;font-size:18px;" id="amountnmb" class="text-right"></div>	
	</div>
	<br>
	<br>
	<div id="check--pay-to-box" class="checkprint" style="margin-top:30px;margin-bottom:20px;float:right;padding-right:20px;">
	<div id="check-1-memo-box">
	
	</div>
	</div>
	
     
      </div>
</div>
</div>

</div>


