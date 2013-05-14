<?php
if(!$enabled){
?>
<p><?php echo $text_bitcoin_unavailable;?></p>
<?php
}else{
?>
<div class="content" id="payment">
  <input type="hidden" name="order_id" value="<?php echo $paybox->order_id; ?>">
  <div style="float:left; margin:10px;"><a href="<?php echo $btc_url;?>"><img src="data:image/png;base64,<?php echo $paybox->qr_data;?>" width="200" alt="Send to <?php echo $paybox->address;?>" border="0"></a></div>
  <h2 style="margin:10px 0px;"><?php echo $text_bitcoin_title;?></h2><p style="margin:10px 0px;"><?php echo sprintf($text_pay_msg,$paybox->btc_amount,$paybox->address);?></p><p style="margin:10px 0px;"><?php echo $text_afterpay;?></p><p style="margin:10px 0px;"><?php echo $api->countDown($paybox->expire,'div.payment',$text_countdown, $text_countdown_exp);?></p>
</div>
<div class="buttons">
  <div class="right"><input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" /></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		url: 'index.php?route=payment/bitcointhai/send',
		type: 'post',
		data: $('#payment :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			$('.attention, .warning').remove();
			$('#payment').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-confirm').attr('disabled', false);
			$('.attention').remove();
		},				
		success: function(json) {
			if (json['error']) {
				$('#payment').before('<div class="warning">'+json['error']+'</div>');
			}
			if (json['success']) {
				location = json['success'];
			}
		}
	});
});
//--></script> 
<?php
}
?>