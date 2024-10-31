<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<h1> <?php echo __rcg_lang('DesignForm');?> </h1>
<?php if(get_transient('formMessage')){ 
$data = json_decode(get_transient('formMessage'),true); ?>
<div class="wrap">
  <?php if(!empty($data['successMessages'])){ ?>
  <?php foreach($data['successMessages'] as $successMessage){ ?>
  <div class="updated">
    <h4><?php echo $successMessage; ?></h4>
  </div>
  <?php } ?>
  <?php } ?>
  <?php if(!empty($data['errorMessages'])){ ?>
  <?php foreach($data['errorMessages'] as $errorMessage){ ?>
  <div class="error">
    <h4><?php echo $errorMessage; ?></h4>
  </div>
  <?php } ?>
  <?php } ?>
</div>
<?php delete_transient('formMessage')?>
<?php } ?>
<div id="FormBuilderPanel">
  <div class="wrap" style="background-color:#E5E5E5;padding:20px;border: 1px solid #ccc;">
    <table>
      <tbody>
        <tr>
          <th width="30%" align="left"><?php echo __rcg_lang('FormName');?> <span style="color:#F00">*</span></th>
          <td align="left"><input type="text" name="form_name" id="form_name"  value="" required="required" style="width:400px"></td>
        </tr>
        <tr>
          <th width="30%" align="left"><?php echo __rcg_lang('willShow');?></th>
          <td align="left"><input type="checkbox" name="show_name" id="show_name"  value=""></td>
        </tr>
      </tbody>
    </table>
  </div>
  
  <div class="wrap" style="background-color:#E5E5E5;padding:20px 20px 54px;border: 1px solid #ccc;">
    <div class="build-form" style=" border:2px dashed #ccc;padding:10px;">
      <textarea id="fb-template"></textarea>
    </div>
  </div>
</div>
