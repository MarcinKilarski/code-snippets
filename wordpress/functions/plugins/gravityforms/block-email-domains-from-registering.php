<?php

/**
 * Prevent users from using personal or competitor emails
 */
class GW_Email_Domain_Validator {

	private $_args;

	function __construct($args) {

			$this->_args = wp_parse_args( $args, array(
					'form_id' => 2,
					'field_id' => false,
					'domains' => false,
					'validation_message' => __( 'Sorry, <strong>%s</strong> email accounts are not eligible for this form.' ),
					'mode' => 'ban' // also accepts "limit"
			) );

	// convert field ID to an array for consistency, it can be passed as an array or a single ID
			if($this->_args['field_id'] && !is_array($this->_args['field_id']))
					$this->_args['field_id'] = array($this->_args['field_id']);

			$form_filter = $this->_args['form_id'] ? "_{$this->_args['form_id']}" : '';

			add_filter("gform_validation{$form_filter}", array($this, 'validate'));

	$this->_args = wp_parse_args( $args, array(
					'form_id' => 4,
					'field_id' => false,
					'domains' => false,
					'validation_message' => __( 'Sorry, <strong>%s</strong> email accounts are not eligible for this form.' ),
					'mode' => 'ban' // also accepts "limit"
			) );

	// convert field ID to an array for consistency, it can be passed as an array or a single ID
			if($this->_args['field_id'] && !is_array($this->_args['field_id']))
					$this->_args['field_id'] = array($this->_args['field_id']);

			$form_filter = $this->_args['form_id'] ? "_{$this->_args['form_id']}" : '';

			add_filter("gform_validation{$form_filter}", array($this, 'validate'));

	$this->_args = wp_parse_args( $args, array(
					'form_id' => 8,
					'field_id' => false,
					'domains' => false,
					'validation_message' => __( 'Sorry, <strong>%s</strong> email accounts are not eligible for this form.' ),
					'mode' => 'ban' // also accepts "limit"
			) );

			// convert field ID to an array for consistency, it can be passed as an array or a single ID
			if($this->_args['field_id'] && !is_array($this->_args['field_id']))
					$this->_args['field_id'] = array($this->_args['field_id']);

			$form_filter = $this->_args['form_id'] ? "_{$this->_args['form_id']}" : '';

			add_filter("gform_validation{$form_filter}", array($this, 'validate'));

	$this->_args = wp_parse_args( $args, array(
					'form_id' => 12,
					'field_id' => false,
					'domains' => false,
					'validation_message' => __( 'Sorry, <strong>%s</strong> email accounts are not eligible for this form.' ),
					'mode' => 'ban' // also accepts "limit"
			) );

	// convert field ID to an array for consistency, it can be passed as an array or a single ID
			if($this->_args['field_id'] && !is_array($this->_args['field_id']))
					$this->_args['field_id'] = array($this->_args['field_id']);

			$form_filter = $this->_args['form_id'] ? "_{$this->_args['form_id']}" : '';

			add_filter("gform_validation{$form_filter}", array($this, 'validate'));

	$this->_args = wp_parse_args( $args, array(
					'form_id' => 16,
					'field_id' => false,
					'domains' => false,
					'validation_message' => __( 'Sorry, <strong>%s</strong> email accounts are not eligible for this form.' ),
					'mode' => 'ban' // also accepts "limit"
			) );

			// convert field ID to an array for consistency, it can be passed as an array or a single ID
			if($this->_args['field_id'] && !is_array($this->_args['field_id']))
					$this->_args['field_id'] = array($this->_args['field_id']);

			$form_filter = $this->_args['form_id'] ? "_{$this->_args['form_id']}" : '';

			add_filter("gform_validation{$form_filter}", array($this, 'validate'));

	$this->_args = wp_parse_args( $args, array(
					'form_id' => 13,
					'field_id' => false,
					'domains' => false,
					'validation_message' => __( 'Sorry, <strong>%s</strong> email accounts are not eligible for this form.' ),
					'mode' => 'ban' // also accepts "limit"
			) );

			// convert field ID to an array for consistency, it can be passed as an array or a single ID
			if($this->_args['field_id'] && !is_array($this->_args['field_id']))
					$this->_args['field_id'] = array($this->_args['field_id']);

			$form_filter = $this->_args['form_id'] ? "_{$this->_args['form_id']}" : '';

			add_filter("gform_validation{$form_filter}", array($this, 'validate'));

	$this->_args = wp_parse_args( $args, array(
					'form_id' => 14,
					'field_id' => false,
					'domains' => false,
					'validation_message' => __( 'Sorry, <strong>%s</strong> email accounts are not eligible for this form.' ),
					'mode' => 'ban' // also accepts "limit"
			) );

			// convert field ID to an array for consistency, it can be passed as an array or a single ID
			if($this->_args['field_id'] && !is_array($this->_args['field_id']))
					$this->_args['field_id'] = array($this->_args['field_id']);

			$form_filter = $this->_args['form_id'] ? "_{$this->_args['form_id']}" : '';

			add_filter("gform_validation{$form_filter}", array($this, 'validate'));

	}

	function validate($validation_result) {

			$form = $validation_result['form'];

			foreach($form['fields'] as &$field) {

					// if this is not an email field, skip
					if(RGFormsModel::get_input_type($field) != 'email')
							continue;

					// if field ID was passed and current field is not in that array, skip
					if($this->_args['field_id'] && !in_array($field['id'], $this->_args['field_id']))
							continue;

					$page_number = GFFormDisplay::get_source_page( $form['id'] );
					if( $page_number > 0 && $field->pageNumber != $page_number ) {
							continue;
					}

					if( GFFormsModel::is_field_hidden( $form, $field, array() ) ) {
						continue;
					}

					$domain = $this->get_email_domain($field);

					// if domain is valid OR if the email field is empty, skip
					if($this->is_domain_valid($domain) || empty($domain))
							continue;

					$validation_result['is_valid'] = false;
					$field['failed_validation'] = true;
					$field['validation_message'] = sprintf($this->_args['validation_message'], $domain);

			}

			$validation_result['form'] = $form;
			return $validation_result;
	}

	function get_email_domain( $field ) {
			$email = explode( '@', rgpost( "input_{$field['id']}" ) );
			return trim( rgar( $email, 1 ) );
	}

	function is_domain_valid( $domain ) {

			$mode   = $this->_args['mode'];
		$domain = strtolower( $domain );

			foreach( $this->_args['domains'] as $_domain ) {

				$_domain = strtolower( $_domain );

					$full_match   = $domain == $_domain;
					$suffix_match = strpos( $_domain, '.' ) === 0 && $this->str_ends_with( $domain, $_domain );
					$has_match    = $full_match || $suffix_match;

					if( $mode == 'ban' && $has_match ) {
							return false;
					} else if( $mode == 'limit' && $has_match ) {
							return true;
					}

			}

			return $mode == 'limit' ? false : true;
	}

	function str_ends_with( $string, $text ) {

			$length      = strlen( $string );
			$text_length = strlen( $text );

			if( $text_length > $length ) {
					return false;
			}

			return substr_compare( $string, $text, $length - $text_length, $text_length ) === 0;
	}

}

# Configuration
new GW_Email_Domain_Validator( array(
	'domains' => array('gmail.com', 'hotmail.com', 'yahoo.com', 'yahoo.co.uk', 'outlook.com', 'outlook.co.uk'. 'outlook.it', 'live.com', 'live.co', 'live.co.uk', 'msn.com', 'me.com', 'icloud.com', 'yahoo.co.in' ),
	'validation_message' => __( 'Please use your company email.' ),
) );

new GW_Email_Domain_Validator( array(
	'domains' => array('blueyonder.com', 'openglobal.cl', 'ttirv.org', 'oriwijn.com', 'iillii.org', 'guerrillamail.info', 'sharklasers.com', 'dsecurelyx.com', 'enayu.com', 'opten.email', 'live.com', 'logility.com', 'kinaxis.com', 'ompartners.com', 'sap.com', 'quintiq.com', 'jda.com',  'e2open.com', 'arkieva.com', 'toolsgroup.com', 'anaplan.com', 'oracle.com', 'demandsolutions.com', 'adexa.com', 'riverlogic.com', 'sas.com', '2open.com', 'wefoinw.com', '3scsolution.com', 'tredence.com', 'stefanini.com', 'smartchainllp.com', 'lntinfotech.com', 'solvoyo.com', 'dcrasolutions.com', 'enquero.com', 'manh.com' , 'fredfontes.co', 'camelot-itlab.com', 'llamasoft.com', 'sree.hameed@aveva.com', 'infosys.com', 'nextorbit.com', 'techmahindra.com', 'futurmaster.com', 'relexsolutions.com'),
	'validation_message' => __( 'Not valid.' ),
) );
