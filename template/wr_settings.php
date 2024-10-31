<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
  <form action="?page=<?= $_GET['page']?>&noheader=true" method="post">
    <h2> <?php echo __rcg_lang('EnterRouteeApplicationDet'); ?> </h2>
    <?php if(($message = (isset($_GET['message']) ? $_GET['message'] : ''))){ ?>
    <div class="<?php echo ( ($message == 'success') ? 'updated' : 'error' )?>">
      <h4>
        <?php if($message === 'success'){ echo __rcg_lang('RouteeApplication'); }else{ echo __rcg_lang('pleaseCheckYourCredentails'); } ?>
      </h4>
    </div>
    <?php } ?>
    <table>
      <tbody>
        <tr>
          <th><?php echo __rcg_lang('RouteeAppId'); ?> <span style="color:#F00">*</span></th>
          <td><input type="text" name="wr[routee_app_id]" autocomplete="off" value="<?php echo $wr_config['routee_app_id'] ? $wr_config['routee_app_id'] : '';?>" required="required"></td>
        </tr>
        <tr>
          <th><?php echo __rcg_lang('RouteeAppSecret'); ?> <span style="color:#F00">*</span></th>
          <td><input type="password" name="wr[routee_app_secret]" autocomplete="off" value="<?php echo $wr_config['routee_app_secret'] ? $wr_config['routee_app_secret'] : '';?>" required="required"></td>
        </tr>
      </tbody>
    </table>
    <?php wp_nonce_field('wr_settings', '_wr_settings');  submit_button( __rcg_lang('Submit'), 'secondary', 'get_access_token'); ?>
  </form>
</div>
