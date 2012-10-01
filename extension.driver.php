<?php
 Class extension_cookie_law extends Extension
 {
	 public function about() {
		 return array(
			 'name' => 'Cookie Law',
			 'version' => '1.1',
			 'release-date' => '2012-10-01',
			 'author' => array(
				 'name'     => '<a href="http://gielberkers.com">Giel Berkers</a>'
			 ),
			 'description' => 'Inject Javascript as soon as the user accepts the cookie law.'
		 );
	 }

	 public function getSubscribedDelegates(){
		 return array(
			 array('page'		=> '/system/preferences/',
				 'delegate'	=> 'AddCustomPreferenceFieldsets',
				 'callback'	=> 'appendPresets'),
			 array('page'		=> '/system/preferences/',
				 'delegate'	=> 'Save',
				 'callback'	=> 'savePresets')
		 );
	 }

	 public function appendPresets($context)
	 {
		 $wrapper = $context['wrapper'];

		 $fieldset = new XMLElement('fieldset', '', array('class'=>'settings'));
		 $fieldset->appendChild(new XMLElement('legend', __('Cookie Law')));

		 $label = Widget::Label(__('Javascript code to inject when the user accepts the cookie law:'));
		 $value = Symphony::Configuration()->get('javascript', 'cookie_law');
		 $label->appendChild(Widget::Textarea('settings[cookie_law][javascript]', 15, 50, $value));
		 $fieldset->appendChild($label);

		 $label = Widget::Label();
		 $value = Symphony::Configuration()->get('default_styling', 'cookie_law');
		 if(empty($value)) { $value = 'yes'; }
		 $input = Widget::Input('settings[cookie_law][default_styling]', 'yes' , 'checkbox', ($value == 'yes' ? array('checked'=>'checked') : null));
		 $label->setValue($input->generate() . ' ' . __('Include default styling'));
		 $fieldset->appendChild($label);

		 $wrapper->appendChild($fieldset);
	 }

	 public function savePresets($context)
	 {
		 $data = $context['settings']['cookie_law'];
		 if(!isset($data['default_styling'])) { $data['default_styling'] = 'no'; }
		 foreach($data as $key => $value)
		 {
			 Symphony::Configuration()->set($key, $value, 'cookie_law');
		 }
		 if(version_compare(Administration::Configuration()->get('version', 'symphony'), '2.2.5', '>'))
		 {
			 // S2.3+
		 	 Symphony::Configuration()->write();
		 } else {
			 // S2.2.5-
			 Administration::instance()->saveConfig();
		 }
	 }

 }