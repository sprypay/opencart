<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_shop; ?></td>
            <td><input type="text" name="sprypay_shop" value="<?php echo $sprypay_shop; ?>" />
              <?php if ($error_shop) { ?>
              <span class="error"><?php echo $error_shop; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_secret; ?></td>
            <td><input type="text" name="sprypay_secret" value="<?php echo $sprypay_secret; ?>" />
              <?php if ($error_secret) { ?>
              <span class="error"><?php echo $error_secret; ?></span>
              <?php } ?></td>
          </tr>
           <tr>
            <td><?php echo $entry_script; ?></td>
            <td>
				<p><input type="radio" name="sprypay_confirm_status"
				value="before" id="sprypay_confirm_status_before"
				<?php if( $sprypay_confirm_status == 'before' ) { ?> checked <?php } ?>
				onclick="show_hide_block('before', 0)"
				><label for="sprypay_confirm_status_before"><?php echo $entry_script_before; ?></label></p>

				<p><input type="radio" name="sprypay_confirm_status"
				value="after" id="sprypay_confirm_status_after"
				<?php if( $sprypay_confirm_status == 'after' ) { ?> checked <?php } ?>
				onclick="show_hide_block('after', 0)"
				><label for="sprypay_confirm_status_after"
				><?php echo $entry_script_after; ?></label></p>

			</td>
          </tr>
                </table>
          <table id="block_before" class="form" width=100%>
		   <tr>
            <td><?php echo $entry_preorder_status; ?></td>
            <td><select name="sprypay_preorder_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $sprypay_preorder_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
		  </table>

		  <div id="block_after" ></div>

           <table class="form">
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="sprypay_order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $sprypay_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="sprypay_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $sprypay_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="sprypay_status">
                <?php if ($sprypay_status) { ?>
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
            <td><input type="text" name="sprypay_sort_order" value="<?php echo $sprypay_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
      <script>
      function show_hide_block(value)
	{
		if( value=='before' )
		{
			document.getElementById('block_before').style.display = 'block';
			document.getElementById('block_after').style.display = 'none';

		}
		else if( value=='after' )
		{
			document.getElementById('block_before').style.display = 'none';
			document.getElementById('block_after').style.display = 'block';
		}

	}

	//alert(document.getElementById('sprypay_confirm_status').value);

	show_hide_block( '<?php echo $sprypay_confirm_status; ?>', 0 );
      </script>

    </div>
  </div>
</div>
<?php echo $footer; ?>
