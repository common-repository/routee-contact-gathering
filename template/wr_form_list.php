<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if(get_transient('forms_del_msg') ||  delete_transient('forms_del_msg') || get_transient('add_message')){ ?>
<div id="message" class="updated below-h2">
  <p> <?php echo $msg = get_transient('forms_del_msg') ,  delete_transient('forms_del_msg') , get_transient('add_message');?> </p>
</div>
<?php } ?>
<div class="wrap">
  <h2> <?php echo __rcg_lang('AllForms');?>&nbsp;&nbsp;&nbsp;<a href="<?php echo get_bloginfo('siteurl').'/wp-admin/admin.php?page=wr-form-generator';?>"><?php echo __rcg_lang('Createform');?></a></h2>
  <form id="form-filter" method="get">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <?php $listObject->display(); ?>
  </form>
</div>
