<?php if($settings['general']['debug']){?>
<div id="quickcheckout_debug">
  <div class="button-toggle">Show Hide Debug information</div>
  <ul class="debug-content qc-hide">
    <li>
      <div class="heading">General</div>
      <pre><?php print_r($settings['general']); ?></pre>
    </li>
    <li>
      <div class="heading">Step settings</div>
      <pre><?php print_r($settings['step']); ?></pre>
    </li>
    <li>
      <div class="heading">Session</div>
      <pre><?php print_r($checkout); ?></pre>
    </li>
    <br class="clear" />
    <li>
      <div class="heading">Option Register</div>
      <pre><?php print_r($settings['option']['register']); ?></pre>
    </li>
    <li>
      <div class="heading">Option Guest</div>
      <pre><?php print_r($settings['option']['guest']); ?></pre>
    </li>
    <li>
      <div class="heading">Option Logged</div>
      <pre><?php print_r($settings['option']['logged']); ?></pre>
    </li>
    <br class="clear" />
  </ul>
</div>
<?php } ?>