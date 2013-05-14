<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
  	<p><?php echo $text_signup_notice;?></p>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_api_id; ?></td>
          <td><input type="text" name="bitcointhai_api_id" value="<?php echo $bitcointhai_api_id; ?>" style="width:300px;" />
            <?php if ($error_api_id) { ?>
            <span class="error"><?php echo $error_api_id; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_api_key; ?></td>
          <td><input type="text" name="bitcointhai_api_key" value="<?php echo $bitcointhai_api_key; ?>" style="width:300px;" />
            <?php if ($error_api_key) { ?>
            <span class="error"><?php echo $error_api_key; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="bitcointhai_order_status_id">
              <?php foreach ($order_statuses as $order_status ) { ?>
              <?php if ($order_status['order_status_id'] == $bitcointhai_order_status_id || ($bitcointhai_order_status_id == 0 && strtolower($order_status['name']) == 'pending')) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select> <?php echo $entry_order_status_note;?></td>
        </tr>
        <tr>
          <td><?php echo $entry_order_status_after; ?></td>
          <td><select name="bitcointhai_order_status_id_after">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $bitcointhai_order_status_id_after || ($bitcointhai_order_status_id_after == 0 && strtolower($order_status['name']) == 'processing')) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select> <?php echo $entry_order_status_after_note;?></td>
        </tr>
        
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="bitcointhai_status"> 
              <?php if ($bitcointhai_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="bitcointhai_sort_order" value="<?php echo $bitcointhai_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>