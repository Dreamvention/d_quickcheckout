<?php
//instruction
$_['tab_instruction']                               = 'Instructions';
$_['text_instruction']                              = '

<div class="row">
	<div class="col-md-2">

<ul class="nav nav-pills nav-stacked">
	<li class="active"><a href="#in_install"  data-toggle="tab">Installation and Updating</a></li>
	<li><a href="#in_setup"  data-toggle="tab">Basic setup & General</a></li>
	<li><a href="#in_login"  data-toggle="tab">Login</a></li>
	<li><a href="#in_address"  data-toggle="tab">Payment & Shipping address</a></li>
	<li><a href="#in_shipping"  data-toggle="tab">Shipping method</a></li>
	<li><a href="#in_payment"  data-toggle="tab">Payment method</a></li>
	<li><a href="#in_cart"  data-toggle="tab">Cart & Confirm</a></li>
	<li><a href="#in_design"  data-toggle="tab">Design</a></li>
	<li><a href="#in_analytics"  data-toggle="tab">Analytics</a></li>
	<li><a href="#in_debug"  data-toggle="tab">Debug</a></li>
	<li><a href="#in_development"  data-toggle="tab">Development</a></li>
	<li><a href="#in_qa"  data-toggle="tab">Q&A</a></li>
</ul>
	</div>
	<div class="col-md-10">
<div class="tab-content">
	<div id="in_install" class="tab-pane active">
		<div class="tab-body">
			<h3>Installation</h3>
			<ol>
				<li>Unzip distribution file</li>
				<li>Upload everything from the folder <code>UPLOAD</code> into the root folder of you shop</li>
				<li>Goto admin of your shop and navigate to extensions -> modules -> Ajax Quick Checkout </li>
				<li>Click install button</li>
			</ol>
			<div class="bs-callout bs-callout-info">
			<h4>Note!</h4>
			<p>Our installation process requires you to have access to the internet because we will install all the required dependencies before we install the module.</p>
			</div>
			<div class="bs-callout bs-callout-warning">
			<h4>Warning!</h4>
			<p>If you get an error on this step, be sure to make you <code>DOWNLOAD</code> folder (usually in system folder of you shop) writable.</p>
			</div>
			<h3>Updating</h3>
			<ol>
				<li>Before upgrade use Bulk Settings option in admin to save the settings of all checkout settings.</li>
				<li>Unzip distribution file</li>
				<li>Upload everything from the folder <code>UPLOAD</code> into the root folder of you shop</li>
				<li>Click overwrite for all files</li>
			</ol>
			<div class="bs-callout bs-callout-info">
			<h4>Note!</h4>
			<p>Although we follow strict standards that do not allow feature updates to cause a full reinstall of the module, still it may happen that major releases require you to uninstall/install the module again before new feature take place. </p>
			</div>
			<div class="bs-callout bs-callout-warning">
			<h4>Warning!</h4>
			<p>If you have made custom corrections to the code, your code will be rewritten and lost once you update the module. </p>
			</div>
			<div class="bs-callout bs-callout-info">
			<h4>Note!</h4>
			<p>We advise all developers to use Vqmod/Ocmod to add custom code to AQC or use a custom template file of the installed theme to add styling.</p>
			</div>
		</div>
	</div>
	<div id="in_setup" class="tab-pane">
		<div class="tab-body">
			<h3>Basic setup</h3>
			<p>Ajax Quick Checkout is built on <strong>backbone</strong> and <strong>Underscore</strong>. All the information is defined in a backbone model. </p>

			<p>The front-end checkout is build on <strong>8 blocks (steps)</strong>: <code>Login</code>, <code>Payment address</code>, <code>Shipping address</code>, <code>Shipping method</code>, <code>Payment method</code>, <code>Cart</code>, <code>Payment</code>, <code>Confirm</code>. Every block (step) has its own configurations which you be able to edit in the admin access. </p>

			<p>Once you install AQC, you will see a startup screen.</p> 
			<ol>
				<li>Click Create Setting</li>
				<li>Turn on the checkout by click Status ON</li>
				<li>Save setting by click Save or Save&Stay buttons on the top right</li>
			</ol>
			<div class="bs-callout bs-callout-info">
			<h4>Note!</h4>
			<p>Every new setting has a default value, which is already enough for your checkout to work. You can further customize you checkout as you wish.</p>
			</div>
			
			<p>On the home tab you will see all your current settings. Each block is a Checkout setting, which can be shown to any users. </p>

			<p>Use probability option to set the chances with which each checkout can be shown to a customer. This is the part of the AB testing feature which you can more about read here.</p>

			<div class="bs-callout bs-callout-info">
			<h4>Note!</h4>
			<p>To temporarily turn off the checkout setting you can set the probability to 0. </p>
			</div>

			<h3>General</h3>
			<ol>
				<li>Name. You can change the name of the active setting, which is colored in green.</li>
				<li>Google Analytics Events. Once this is turned on, you can receive valuable data on the customer behaviour on your checkout through the google analytics account. Read more here. </li>
				<li>Config file. You can create your own config files used by the AQC. They must be located in system/config folder and named <code>d_quickcheckout_PREFIX.xml</code> where PREFIX is you specific id (i.e. _custom will be <code>d_quickcheckout_custom.xml</code>). For developers.</li>
				<li>Delete Setting. You can delete the current setting with all the data and analytics. Total cleanup.</li>
				<li>Bulk settings. You can create a JSON string with all the settings for you current checkout and then use it to migrate to another shop, create a copy of the checkout setting or simply save the data before upgrade.</li>
			</ol>
			<div class="bs-callout bs-callout-info">
			<h4>Note!</h4>
			<p>Please read the tooltips to learn more about every option.</p>
			</div>

		</div>
	</div>
	<div id="in_login" class="tab-pane">
		<div class="tab-body">
			<h3>Login</h3>
			<p>Here you have the options to setup your login block. </p>
			<ol>
				<li>Display the login as a block or place it in a popup</li>
				<li>Hide/show the three available options</li>
				<li>Turn on or off the social logins (if installed)</li>
			</ol>

			<div class="bs-callout bs-callout-info">
				<h4>Note!</h4>
				<p>Only the Social Login module from Dreamvention is compatible with this feature.</p>
			</div>
		</div>
	</div>
	<div id="in_address" class="tab-pane">
		<div class="tab-body">
			<h3>Payment address and Shipping address</h3>
			<p>You can set the address fields as you like:</p>
			<ol>
				<li>Drag to sort them </li>
				<li>Hide/show or require/not as you wish </li> 
				<li>Set the default values if necessary</li>
				<li>Add mask if required. </li>
				<li>Add custom fields. Click the button to be redirected to a new custom filed option. Once you setup a custom field, return to the AQC menu and further customize the field. Click save. </li>
			</ol>
			<div class="bs-callout bs-callout-warning">
				<h4>Warning!</h4>
				<p>The custom fields will not show unless you click save in the AQC admin panel after you have created a custom field.</p>
			</div>
			<div class="bs-callout bs-callout-info">
				<h4>Note!</h4>
				<p>For mask option we are using this jquery.maskinput.js (<a href="https://github.com/digitalBush/jquery.maskedinput">https://github.com/digitalBush/jquery.maskedinput</a>)</p>
			</div>

			<p>Shipping address is very much the same as payment address, accept it does not include standard (like email, password etc.) account fields and custom fields set to account.</p>

			<div class="bs-callout bs-callout-info">
				<h4>Note!</h4>
				<p>You can set shipping address to always be required.  This means that even you have the option to set shipping address the same as payment, the shipping address block will still be shown to the customer and will be required.</p>
			</div>
		</div>
	</div>
	<div id="in_shipping" class="tab-pane">
		<div class="tab-body">
			<h3>Shipping method</h3>
			<p>Here you can customise the appearance of the sipping method block. You can hide it, or remove description or even the shipping methods. </p>

			<p>You can also set the title and the description of the block as for the rest of the blocks.</p>

			<div class="bs-callout bs-callout-info">
				<h4>Note!</h4>
				<p>If you want to the feature of setting specific payment methods to specific shipping methods – you will need to use a separate module for this.</p>
			</div>

		</div>
	</div>
	<div id="in_payment" class="tab-pane">
		<div class="tab-body">
			<h3>Payment method</h3>
			<p>You can set the appearance of the payment method block, title and description, as well as the default payment method.</p>

			<h4>Use Payment Popup screen</h4>
			<p>Also you can set the usage of the popup step for payment. In some cases this is a useful solution. Although it is an extra step for the customer, it may help your customer follow the steps faster. </p>

			<p>The popup for the payment can be set as a default value in case you didn’t specify for the selected payment method.</p>

			<div class="bs-callout bs-callout-info">
				<h4>Note!</h4>
				<p>Before asking for support, try this option and see if it solves the issue you are facing.</p>
			</div>
		</div>
	</div>
	<div id="in_cart" class="tab-pane">
		<div class="tab-body">
			<h3>Cart & Confirm</h3>
			<ol>
				<li>Set the columns for the cart</li>
				<li>Edit the title and description of the cart block</li>
			</ol>
			<div class="bs-callout bs-callout-info">
				<h4>Note!</h4>
				<p>If you want to hide the cart weight – navigate to system -> settings and edit your shop setting on tab Option for Display Weight on Cart Page </p>
			</div>
			<p>You also can set the Comment and agree fields here just like you would on the payment and shipping address step.</p>
		</div>
	</div>
	<div id="in_design" class="tab-pane">
		<div class="tab-body">
			<h3>Design</h3>
			<p>The design tab allows you to define basic design features like field appearance in payment and shipping address.</p>

			<ol>
				<li>Here you can select a style theme for you checkout. All the themes are CSS files, which are located in catalog/view/theme/default/stylesheet/d_quickcheckout/theme/</li>
				<li>Toggle placeholders for fields.</li>
				<li>Select address design: one row with radio buttons (Radio) and a list block which can be highlighted (List)</li>
			</ol>

			<div class="bs-callout bs-callout-info">
				<h4>Note!</h4>
				<p>If you are using a custome theme and your theme is lacking twitter bootstrap framework or its heavily altered, you can use the “force Bootstrap” feature to add a clean bootstrap style only to the AQC block.</p>
			</div>
			
			<p>Show only the checkout is a popular feature that allows you to hide all the distracting parts of the theme from the checkout allowing the customer to focus on the on the payment process.</p>

			<p>Design is the most recognizable feature of the AQC. It has been in the cart of our module. Here you can drag and drop all the blocks as you wish, sorting them and making your own unique checkout experience. </p>
		</div>
	</div>
	<div id="in_analytics" class="tab-pane">
		<div class="tab-body">
			<h3>Statistics, AB analysis, Google Analytics Events</h3>
			<p>As part of the new AQC since version 6.0.0 we have added a statistics feature that allows you to collect valuable information about each order, how many actions did the customer make before he made a successful purchase, as well as the time it took for him. </p>
			<div class="bs-callout bs-callout-info">
				<h4>Note!</h4>
				<p>The rating is a AQC grade of the easiness of the specific order made with this checkout setting. The less the customer had to make actions – the better the rating.</p>
			</div>

			<ul>
				<li><span class="label label-default">Gray count</span>: total amount of fields to fill.</li>
				<li><span class="label label-info">Blue count</span>: total amount of fields edited and updated.</li>
				<li><span class="label label-success">Green count</span>: total amount of buttons pressed.</li>
				<li><span class="label label-danger">Red count</span>: total amount of errors shown.</li>
			</ul>

			<p>With this data you can evaluate the quality of your checkout setting. </p>

			<h3>AB Testing</h3>

			<p>Once we have created the checkout rating, we added the feature of multiple checkout settings. For any webshop you can create an unlimited amount of settings to test different concepts. </p>

			<p>On the homepage you will see all your checkout settings and their probability, with which this setting may be shown to the customer. The larger the number of the probability – the better the chances of this setting to be shown.</p>

			<h4>Basic AB testing for AQC</h4>
			<ol>
				<li>Always test 2 checkouts at the same time. </li>
				<li>Start with setting two different color themes for the checkout.</li>
				<li>Run the test for a week and pick the best color theme. </li>
				<li>Then test the amount of fields. Select the maximum amount of fields and the minimum amount of fields. </li>
				<li>Run the test for a week and pick the best amount of fields. </li>
				<li>Then test the default payment and shipping methods. Run the test for a week.</li>
				<li>And finally test the layout of the blocks. </li>
			</ol>

			<div class="bs-callout bs-callout-info">
				<h4>Note!</h4>
				<p>Remember that if a customer has visited your shop and was already on the checkout page, he will continue to see that checkout setting unless you delete it or set the probability to 0.</p>
			</div>
		</div>
	</div>
	<div id="in_debug" class="tab-pane">
		<div class="tab-body">
			<h3>Debug</h3>
			<p>AQC has a powerful debugging option.  </p>

			<p>On the homepage turn on the debug mode and refresh the admin page. You will see a new tab “debug”. Now run your checkout on the shop. All the information will be logged into the debug log.</p>

			<p>For there you can locate which of the steps is causing a drop in speed, creating an error and more. </p>

			<div class="bs-callout bs-callout-info">
				<h4>Note!</h4>
				<p>Errors are still logged in the admin -> error logs.</p>
			</div>
		</div>
	</div>
	<div id="in_development" class="tab-pane">
		<div class="tab-body">
			<h3>Development</h3>
			<p>We strongly encourage future development to follow these rules:</p>
			<ol>
				<li>Do not alter the controllers and models of the module</li>
				<li>Create a new tpl file in your current theme. </li>
				<li>Add logic code via vqmod/ocmod</li>
				<li>Where possible, use the basic module development pattern. In many cases you do not need to alter AQC at all. </li>
				<li>Use the custom style option in admin -> AQC -> design to add custom styles. Do not alter the CSS files.</li>
				<li>If you want to create a unique style for the whole checkout, use the built-in theme manager. Simply create a file of your theme like so
			
					<blockquote>
						<p>catalog/view/theme/default/stylesheet/d_quickcheckout/theme/MYTHEME.css</p>
					</blockquote>

				and add the styles there. Then go to the admin and select this theme. </li>
			</ol>

			<div class="bs-callout bs-callout-info">
				<h4>Note!</h4>
				<p>In <code>system/mbooth/xml</code> you will find the <code>mbooth_d_quickcheckout.xml</code> file, which keeps trek of all the files and folders that are part of the module. Also you will more useful information there like the version, dependencies and updates for this module. </p>
			</div>
		</div>
	</div>
	<div id="in_qa" class="tab-pane">
		<div class="tab-body">
			<h3>Q&A</h3>
			<h4>Q: I have installed the checkout, but I don’t see it on my shop. Why?</h4>
			<p>A: There may be several reasons. </p>
			<ol>
				<li>Check that vqmod is installed</li>
				<li>Check that vqmod is working and is creating the required vq2-catalog_controller_checkout_checkout.php</li>
				<li>Check that you have turned on the checkout by sliding the Status to ON</li>
				<li>Check that you have saved the settings and that it is actually saved.</li>
			</ol>

			<h4>Q: I have another language (not English) How to change the titles and descriptions? </h4>
			<p>A: For this we have the title and description fields in each block in the admin panel of the checkout. Simply add your text to each of the languages available.</p>

			<h4>Q: After I have installed the AQC - i have started to receive dropped orders. Why so many? </h4>
			<p>A: Our module is a one-page checkout, and it loads all the fields as well as shipping and payment methods. So of the payment methods require the checkout to have a created order otherwise it will not work. That is why our checkout creates an order in the beginning of the load, and not like the default - after all the data has been entered. We keep trek of created orders and if the customer left the shop right after he loaded the checkout - the order will be created and set to dropped unlike in the default checkout - he would have had to fill in all the data. So in reality our checkout is showing you the real dropped orders and that is why you see them all and can make a better judgment.</p>

			<h4>Q: I have installed the social login module, but I don’t see them on the checkout step. Why?</h4>
			<p>A: There are several reasons:</p>
			<ol>
				<li>Check that you have turned on the social login module</li>
				<li>Check that you have saved the settings of the social login module</li>
				<li>Check that you have actually turned on the social login in the AQC login tab.</li>
				<li>When checking, be sure that you are not logged in. If you are – you will not see the social login option by defualt. </li>
			</ol>

			<h4>Q: I am editing the checkout settings, but the checkout is not changing. Why?</h4>
			<p>A: If you have check that you have correctly installed the checkout, be sure to edit the settings you are actually viewing on the webshop. For this go to admin -> AQC -> home tab and click on the eye of the checkout settings you want to view. </p>

			<h4>Q: My AQC is very slow. What is wrong? </h4>
			<p>A: Our checkout is the fastest on the market. But if this happens to you, there may be several reasons:</p>
			<ol>
				<li>You have a slow server – if the whole webshop is slow, do not expect the checkout fly. </li>
				<li>You may have a slow shipping method. If you are using a shipping method which is making CURL requests to another server, the checkout will have to wait every time the shipping method has to make a call. </li>
				<li>Your payment method is taking time. </li>
			</ol>

			<h4>Q: I have a third party module which works fine on the default checkout, but is not working with the AQC. What do I do?</h4>
			<p>A: We support our checkout to work with default payment and shipping methods, as well as the total modules.
			We do not promise that it will work with all of the thousands of methods out there. If you need to solve a compatibility issue, we suggest you contact the developer of the method or module to assist you with this. Our checkout solution is the number on the market. It has over 40 000 downloads. By adding the compatibility for our module the developer of the method will increase his customer base and his sales.
			If the developer refuses, we can offer premium support for an extra fee. Please keep in mind that the solution will be only for your checkout and you will lose the possibility for future update, which is a loss for you. That is why we suggest the developer of the third party module to add support. </p>
		</div>
	</div>
</div>';