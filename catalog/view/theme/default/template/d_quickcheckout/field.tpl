<?php $col = "col-xs-6";
if($settings['design']['block_style'] == 'block') { 
  $col = "col-xs-12";
} ?>
<?php foreach($fields as $f){ ?>
  <?php if(isset($f['type'])) { ?>
    <?php $display = ((isset($f['display']) && $f['display'] == 1) 
    || (is_array($f['display']) && $f['display'][$customer_group_id])) 
    ? true : false; ?>
    <?php $require = ((isset($f['require']) && $f['require'] == 1) 
    || (isset($f['require']) && is_array($f['require']) && $f['require'][$customer_group_id])) 
    ? true : false; ?>
    <?php $refresh = isset($f['refresh'])? $f['refresh'] : 0; ?>

<?php switch ($f['type']) {  
      case "heading": ?>
        <?php if($display) { ?>
      <div class="clear"></div>
    </div>
  </div>
</div>
<div id="<?php echo $f['id']; ?>_input" class="panel panel-default sort-item <?php echo $f['id']; ?> <?php echo ($f['class'])? $f['class'] : ''; ?>" data-sort="<?php echo $f['sort_order']; ?>">
  <div class="panel-heading">
    <span class="wrap">
      <span class="fa fa-fw qc-icon-payment-address"></span>
    </span> 
    <span><?php echo $f['title']; ?></span>
  </div>
  <div class="panel-body">
    <div class="form-horizontal">
<?php } ?>
<?php break; ?>
<?php case "label": ?>
      <div id="<?php echo $f['id']; ?>_input" 
        class="label-input form-group  sort-item <?php echo (!$display)? 'qc-hide' : ''; ?> <?php echo ($f['class'])? $f['class'] : ''; ?> <?php echo ($require) ? 'required' : ''; ?>" 
        data-sort="<?php echo $f['sort_order']; ?>">
        <div class="col-xs-12">
          <label class="control-label" for="<?php echo $name; ?>_<?php echo $f['id']; ?>">
            <span class="text"><?php echo $f['title']; ?></span>
          </label>

          <p name="<?php echo $name; ?>[<?php echo $f['id']; ?>]" id="<?php echo $name; ?>_<?php echo $f['id']; ?>" class="label-text" />
          <?php echo isset($f['value'])? $f['value'] : ''; ?>
          </p>
        <div class="col-xs-12">
      </div>
<?php break; ?>
<?php case "radio": ?>

      <?php if(isset($f['options'])){ ?>
      <div id="<?php echo $f['id']; ?>_input" 
        class="radio-input form-group  sort-item <?php echo (!$display)? 'qc-hide' : ''; ?> <?php echo ($f['class'])? $f['class'] : ''; ?> <?php echo ($require) ? 'required' : ''; ?>" 
        data-sort="<?php echo $f['sort_order']; ?>">
        <div class="<?php echo $col; ?>">
          <label class="title control-label">
            <span class="text" <?php echo ($f['tooltip'])? 'data-toggle="tooltip"' : '';?> title="<?php echo $f['tooltip']; ?>"><?php echo $f['title']; ?></span> 
          </label>
        </div>
        <div class="<?php echo $col; ?>">

            <?php foreach ($f['options'] as $option) { ?>
            <div class="radio">
              <label for="<?php echo $name; ?>_<?php echo $f['id'].$option['value']; ?>">
                <input type="radio" 
                name="<?php echo $name; ?>[<?php echo $f['id']; ?>]" 
                value="<?php echo $option['value']; ?>" 
                data-require="<?php echo ($require) ? 'require' : ''; ?>" 
                data-refresh="<?php echo $refresh; ?>" 
                id="<?php echo $name; ?>_<?php echo $f['id'].$option['value']; ?>" 
                <?php echo ($option['value'] == $f['value'])? 'checked="checked"' : ''; ?>  
                class=""  
                autocomplete='off'/>
              
                <?php echo $option['title']; ?></label>
            </div>
            <?php } ?>
        </div>
      </div>
      <?php } ?>

<?php break; case "checkbox": ?>

  <div id="<?php echo $f['id']; ?>_input" 
    class="checkbox-input form-group checkbox  sort-item <?php echo (!$display)? 'qc-hide' : ''; ?> <?php echo ($f['class'])? $f['class'] : ''; ?> <?php echo ($require) ? 'required' : ''; ?>" 
    data-sort="<?php echo $f['sort_order']; ?>">
    <div class="col-xs-12">
      <label for="<?php echo $name; ?>_<?php echo $f['id']; ?>" class="control-label">
          <input type="checkbox" 
          name="<?php echo $name; ?>[<?php echo $f['id']; ?>]" 
          id="<?php echo $name; ?>_<?php echo $f['id']; ?>" 
          data-require="<?php echo ($require) ? 'require' : ''; ?>" 
          data-refresh="<?php echo $refresh; ?>"  
          <?php if (isset($f['value']) && $f['value']) { ?> 
            value="1" 
            checked="checked" 
          <?php }else{ ?> 
            value="0" 
          <?php } ?> 
          class="styled" 
          autocomplete='off' />

          <span class="text" <?php echo ($f['tooltip'])? 'data-toggle="tooltip"' : '';?> title="<?php echo $f['tooltip']; ?>"><?php echo $f['title']; ?></span> 
        </label>
      </div>
    </div>

<?php break; case "select": ?>

      <div id="<?php echo $f['id']; ?>_input" 
        class="select-input form-group  sort-item <?php echo (!$f['display'])? 'qc-hide' : ''; ?> <?php echo ($f['class'])? $f['class'] : ''; ?> <?php echo ($require) ? 'required' : ''; ?>" 
        data-sort="<?php echo $f['sort_order']; ?>">
        <div class="<?php echo $col; ?>">
          <label class="control-label" for="<?php echo $name; ?>_<?php echo $f['id']; ?>"> 
            <span class="text" <?php echo ($f['tooltip'])? 'data-toggle="tooltip"' : '';?> title="<?php echo $f['tooltip']; ?>"><?php echo $f['title']; ?></span>
          </label>
        </div>
        <div class="<?php echo $col; ?>">
          <select name="<?php echo $name; ?>[<?php echo $f['id']; ?>]" 
            data-require="<?php echo ($require) ? 'require' : ''; ?>" 
            data-refresh="<?php echo $refresh; ?>" 
            id="<?php echo $name; ?>_<?php echo $f['id']; ?>"
            class="form-control">
            <option value=""><?php echo $text_select; ?></option>
            <?php if(!empty($f['options'])) { ?>
                <?php foreach ($f['options'] as $option) { ?>
                    <?php if ($option['value'] == $f['value']) { ?>
                    <option value="<?php echo $option['value']; ?>" selected="selected"><?php echo $option['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $option['value']; ?>"><?php echo $option['name']; ?></option>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>

<?php break;  case "password": ?>

      <div id="<?php echo $f['id']; ?>_input" 
        class="password-input form-group  sort-item <?php echo (!$f['display'])? 'qc-hide' : ''; ?> <?php echo ($f['class'])? $f['class'] : ''; ?> <?php echo ($require) ? 'required' : ''; ?>" 
        data-sort="<?php echo $f['sort_order']; ?>">
        <div class="<?php echo $col; ?>">
          <label class="control-label" for="<?php echo $name; ?>_<?php echo $f['id']; ?>"> 
            <span class="text" <?php echo ($f['tooltip'])? 'data-toggle="tooltip"' : '';?> title="<?php echo $f['tooltip']; ?>"><?php echo $f['title']; ?></span> 
          </label>
        </div>
        <div class="<?php echo $col; ?>">
          <input type="password" 
          name="<?php echo $name; ?>[<?php echo $f['id']; ?>]" 
          id="<?php echo $name; ?>_<?php echo $f['id']; ?>" 
          data-require="<?php echo ($require) ? 'require' : ''; ?>" 
          data-refresh="<?php echo $refresh; ?>" 
          value="<?php echo isset($f['value'])? $f['value'] : ''; ?>"
          class="form-control" 
          placeholder="<?php echo ($require) ? '*' : ''; ?> <?php echo str_replace(':', '', $f['title']); ?>"/>
        </div>
      </div>

<?php break; case "textarea": ?>

      <div id="<?php echo $f['id']; ?>_input" 
        class="textarea-input form-group sort-item <?php echo (!$display)? 'qc-hide' : ''; ?> <?php echo ($f['class'])? $f['class'] : ''; ?> <?php echo ($require) ? 'required' : ''; ?>" 
        data-sort="<?php echo $f['sort_order']; ?>">
          <div  class="col-xs-12">
            <label class="control-label" for="<?php echo $name; ?><?php echo $f['id']; ?>"> 
              <span class="text" <?php echo ($f['tooltip'])? 'data-toggle="tooltip"' : '';?> title="<?php echo $f['tooltip']; ?>"><?php echo $f['title']; ?></span> 
            </label>
            <textarea name="<?php echo $name; ?>[<?php echo $f['id']; ?>]" 
              id="<?php echo $name; ?>_<?php echo $f['id']; ?>" 
              data-require="<?php echo ($require) ? 'require' : ''; ?>" 
              data-refresh="<?php echo $refresh; ?>" 
              class="form-control" 
              placeholder="<?php echo ($require) ? '*' : ''; ?> <?php echo str_replace(':', '', $f['title']); ?>"><?php echo isset($f['value'])? $f['value'] : ''; ?></textarea>
          </div>
      </div>
      
<?php break;  default: ?>

      <div id="<?php echo $f['id']; ?>_input" 
        class="text-input form-group  sort-item <?php echo (!$f['display'])? 'qc-hide' : ''; ?> <?php echo ($f['class'])? $f['class'] : ''; ?> <?php echo ($require) ? 'required' : ''; ?>" 
        data-sort="<?php echo $f['sort_order']; ?>">
        <div class="<?php echo $col; ?>">
          <label class="control-label" for="<?php echo $name; ?>_<?php echo $f['id']; ?>"> 
            <span class="text" <?php echo ($f['tooltip'])? 'data-toggle="tooltip"' : '';?> title="<?php echo $f['tooltip']; ?>"><?php echo $f['title']; ?></span> 
          </label>
        </div>
        <div class="<?php echo $col; ?>"> 
          <input type="text" 
            name="<?php echo $name; ?>[<?php echo $f['id']; ?>]" 
            id="<?php echo $name; ?>_<?php echo $f['id']; ?>" 
            data-require="<?php echo ($require) ? 'require' : ''; ?>" 
            data-refresh="<?php echo $refresh; ?>" 
            value="<?php echo isset($f['value'])? $f['value'] : ''; ?>" 
            class="form-control" 
            autocomplite="on" 
            placeholder="<?php echo ($require) ? '*' : ''; ?> <?php echo str_replace(':', '', $f['title']); ?>"/>
        </div>
      </div>
    <?php } //switch ?>
  <?php } //if ?>
<?php } //foreach ?>
<div class="clear"></div>