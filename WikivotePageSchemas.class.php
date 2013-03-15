<?php
/**
* Class declaration for mediawiki extension WikivotePageSchemas
*
* @file WikivotePageSchemas.class.php
* @ingroup WikivotePageSchemas
*/

class WikivotePageSchemas extends PSExtensionHandler
{

	public static function registerClass()
	{
		global $wgPageSchemasHandlerClasses, $wgPageSchemasEventHooks;
		$wgPageSchemasHandlerClasses[] = 'WikivotePageSchemas';
		$wgPageSchemasEventHooks['SfTemplateBefore'][] = 'WikivotePageSchemas::onSfTemplateBefore';
		$wgPageSchemasEventHooks['SfTemplateAfter'][] = 'WikivotePageSchemas::onSfTemplateAfter';
		$wgPageSchemasEventHooks['SfFieldBefore'][] = 'WikivotePageSchemas::onSfFieldBefore';
		$wgPageSchemasEventHooks['SfFieldAfter'][] = 'WikivotePageSchemas::onSfFieldAfter';
		$wgPageSchemasEventHooks['SfFieldArrayMap'][] = 'WikivotePageSchemas::onSfFieldArrayMap';
		return true;
	}

	public static function onSfFieldArrayMap( $psField, $arraymaptext ) {
		//Options initialization:
		$listTemplate = false;
		if ( !is_null( $psField ) ) {
			$optsArray = $psField->getObject( 'wikivoteps_FieldOptions' );
			if ( !is_null( $optsArray ) ) {
				//Options:
				$listTemplate = PageSchemas::getValueFromObject( $optsArray, 'ListTemplate' );
				if($listTemplate == 'NONE' || $listTemplate == null) {
					$listTemplate = false;
				}
			}
		}
		return $listTemplate;
	}

	public static function onSfTemplateBefore( $psTemplate )
	{
		//Options initialization:
		$addTextBefore = '';
		$hideTemplate = '';
		if ( !is_null( $psTemplate ) ) {
			$optsArray = $psTemplate->getObject( 'wikivoteps_TemplateOptions' );
			if ( !is_null( $optsArray ) ) {
				//Options:
				$addTextBefore = PageSchemas::getValueFromObject( $optsArray, 'TextBefore' );
				$hideTemplate = PageSchemas::getValueFromObject( $optsArray, 'HideTemplate' );
			}
		}
		if ( $hideTemplate === 'on' ) {
			$addTextBefore = '<div style="display:none">' . $addTextBefore;
		}
		return $addTextBefore;
	}

	public static function onSfTemplateAfter( $psTemplate )
	{
		//Options initialization:
		$addTextAfter = '';
		$hideTemplate = '';
		if ( !is_null( $psTemplate ) ) {
			$optsArray = $psTemplate->getObject( 'wikivoteps_TemplateOptions' );
			if ( !is_null( $optsArray ) ) {
				//Options:
				$addTextAfter = PageSchemas::getValueFromObject( $optsArray, 'TextAfter' );
				$hideTemplate = PageSchemas::getValueFromObject( $optsArray, 'HideTemplate' );
			}
		}
		if ( $hideTemplate === 'on' ) {
			$addTextAfter = $addTextAfter . '</div>';
		}
		return $addTextAfter;
	}

	public static function onSfFieldBefore( $psField )
	{
		//Options initialization:
		$addTextBefore = '';
		$EnableVoting = '';
		$VotingGroup = '';
		if ( !is_null( $psField ) ) {
			$optsArray = $psField->getObject( 'wikivoteps_FieldOptions' );
			if ( !is_null( $optsArray ) ) {
				//Options:
				$addTextBefore = PageSchemas::getValueFromObject( $optsArray, 'TextBefore' );
				$EnableVoting = PageSchemas::getValueFromObject( $optsArray, 'EnableVoting' );
				$VotingGroup = PageSchemas::getValueFromObject( $optsArray, 'VotingGroup' );
			}
		}
		if ( $EnableVoting && !empty($VotingGroup) ) {
			// Add voting
			$addTextBefore = '<span data-group="' . $VotingGroup . '" class="wcv-object">'
				. '<span style="display:none">'
				. $addTextBefore;
		}
		return $addTextBefore;
	}

	public static function onSfFieldAfter( $psField )
	{
		//Options initialization:
		$addTextAfter = '';
		$EnableVoting = '';
		$VotingGroup = '';
		if ( !is_null( $psField ) ) {
			$optsArray = $psField->getObject( 'wikivoteps_FieldOptions' );
			if ( !is_null( $optsArray ) ) {
				//Options:
				$addTextAfter = PageSchemas::getValueFromObject( $optsArray, 'TextAfter' );
				$EnableVoting = PageSchemas::getValueFromObject( $optsArray, 'EnableVoting' );
				$VotingGroup = PageSchemas::getValueFromObject( $optsArray, 'VotingGroup' );
			}
		}
		if ( $EnableVoting && !empty($VotingGroup) ) {
			// Add voting

			//Fetch group
			$vg = new CustomVoting_Model_Group($VotingGroup);
			$vgCode = '';
			if ( !$vg->error ) {
				$vgCode = $vg->code;
			}

			$addTextAfter = $addTextAfter . '</span>{{#show: {{PAGENAME}}|?' . $vgCode . ' leader}}</span>';
		}
		return $addTextAfter;
	}

	public static function getTemplateValues( $psTemplate )
	{
		$values = array();
		if ( $psTemplate instanceof PSTemplate ) {
			$psTemplate = $psTemplate->getXML();
		}
		foreach ( $psTemplate->children() as $tag => $child ) {
			if ( $tag == "wikivoteps_TemplateOptions" ) {
				foreach ( $child->children() as $prop ) {
					$values[$prop->getName()] = (string)$prop;
				}
			}
		}
		return $values;
	}

	/**
	* Creates an object to hold form-wide information, based on an XML
	* object from the Page Schemas extension.
	*/
	public static function createPageSchemasObject( $tagName, $xml )
	{
		$result = array();
		if ( $tagName == "wikivoteps_TemplateOptions" ) {
			foreach ( $xml->children() as $tag => $child ) {
				if ( $tag == $tagName ) {
					foreach ( $child->children() as $tag => $elem ) {
						$result[$tag] = (string)$elem;
					}
					return $result;
				}
			}
		}
		if ( $tagName == "wikivoteps_FieldOptions" ) {
			foreach ( $xml->children() as $tag => $child ) {
				if ( $tag == $tagName ) {
					foreach ( $child->children() as $tag => $elem ) {
						$result[$tag] = (string)$elem;
					}
					return $result;
				}
			}
		}
		return null;
	}

	public static function getSchemaEditingHTML( $pageSchemaObj )
	{

		$text = '';
		$hasValues = false;

		return null; //array($text, $hasValues);

	}

	public static function getTemplateDisplayString()
	{
		return wfMsg( 'wikivotepageschemas-template-text-1' );
	}

	/**
	* Displays form details for one template in the Page Schemas XML.
	*/
	public static function getTemplateDisplayValues( $templateXML )
	{
		$templateValues = self::getTemplateValues( $templateXML );
		if ( count( $templateValues ) == 0 ) {
			return null;
		}
		$displayValues = array();
		foreach ( $templateValues as $key => $value ) {
			//Opitions:
			$displayValues[$key] = $value;
		}
		return array( null, $displayValues );
	}

	public static function getTemplateEditingHTML( $psTemplate )
	{

		$text = '';
		$hasValues = false;

		//Options initialization:
		$addTextBefore = null;
		$addTextAfter = null;
		$hideTemplate = 'on';

		if ( !is_null( $psTemplate ) ) {
			$optsArray = $psTemplate->getObject( 'wikivoteps_TemplateOptions' );
			if ( !is_null( $optsArray ) ) {
				$hasValues = true;
				//Options:
				$addTextBefore = html_entity_decode( PageSchemas::getValueFromObject( $optsArray, 'TextBefore' ) );
				$addTextAfter = html_entity_decode( PageSchemas::getValueFromObject( $optsArray, 'TextAfter' ) );
				$hideTemplate = html_entity_decode( PageSchemas::getValueFromObject( $optsArray, 'HideTemplate' ) );
			}
		}

		$text = "\t<p>" . "The following fields are useful if you want custom wiki-text before and after template." . "</p>\n";
		$text .= "\t<p>" . 'Text before template:<br/>' . ' ' . Html::textarea( 'wikivoteps_text_before_num', $addTextBefore, array( 'rows' => 5, 'style' => 'width:100%' ) ) . "</p>\n";
		$text .= "\t<p>" . 'Text after template:<br/>' . ' ' . Html::textarea( 'wikivoteps_text_after_num', $addTextAfter, array( 'rows' => 5, 'style' => 'width:100%' ) ) . "</p>\n";
		$text .= "\t<p>" . Html::input( 'wikivoteps_hide_num', $hideTemplate, 'checkbox', (($hideTemplate === 'on') ? array( 'checked' => 'checked' ) : array()) ) . "Hide template </p>\n";

		return array( $text, $hasValues );

	}

	/**
	* Creates Page Schemas XML from form information on templates.
	*/
	public static function createTemplateXMLFromForm()
	{
		global $wgRequest;

		$xmlPerTemplate = array();
		$templateNum = -1;

		$tagOpened = false;

		foreach ( $wgRequest->getValues() as $var => $val ) {

			$val = PageSchemas::xmlEscape( $val );

			if ( !$tagOpened && (substr( $var, 0, 11 ) == 'wikivoteps_') && (substr( $var, 0, 16 ) != 'wikivoteps_field') ) {
				$xml = '<wikivoteps_TemplateOptions>';
				$templateNum = substr( $var, 23 );
				$tagOpened = true;
			}
			if ( $tagOpened && ((substr( $var, 0, 11 ) != 'wikivoteps_') || (substr( $var, 0, 16 ) == 'wikivoteps_field')) ) {
				$xml .= '</wikivoteps_TemplateOptions>';
				$xmlPerTemplate[$templateNum] = $xml;
				$tagOpened = false;
			}

			if ( substr( $var, 0, 23 ) == 'wikivoteps_text_before_' ) {
				$templateNum = substr( $var, 23 );
				//$xml = '<wikivoteps_TemplateOptions>';
				if ( !empty($val) ) {
					$xml .= "<TextBefore>$val</TextBefore>";
				}
			} elseif ( substr( $var, 0, 22 ) == 'wikivoteps_text_after_' ) {
				if ( !empty($val) ) {
					$xml .= "<TextAfter>$val</TextAfter>";
				}
			} elseif ( substr( $var, 0, 16 ) == 'wikivoteps_hide_' ) {
				if ( !empty($val) ) {
					$xml .= "<HideTemplate>$val</HideTemplate>";
				}
				//$xml .= '</wikivoteps_TemplateOptions>';
			}
		}
		return $xmlPerTemplate;
	}

	public static function getFieldDisplayString()
	{
		return wfMsg( 'wikivotepageschemas-template-text-1' );
	}

	/**
	* Returns the HTML for inputs to define a single form field,
	* within the Page Schemas 'edit schema' page.
	*/
	public static function getFieldEditingHTML( $psField )
	{

		$text = '';
		$hasValues = false;

		//Options initialization:
		$addTextBefore = null;
		$addTextAfter = null;
		$enableVoting = null;
		$votingGroup = null;
		$listTemplate = null;

		if ( !is_null( $psField ) ) {
			$optsArray = $psField->getObject( 'wikivoteps_FieldOptions' );
			if ( !is_null( $optsArray ) ) {
				$hasValues = true;
				//Options:
				$addTextBefore = html_entity_decode( PageSchemas::getValueFromObject( $optsArray, 'TextBefore' ) );
				$addTextAfter = html_entity_decode( PageSchemas::getValueFromObject( $optsArray, 'TextAfter' ) );
				$enableVoting = html_entity_decode( PageSchemas::getValueFromObject( $optsArray, 'EnableVoting' ) );
				$votingGroup = html_entity_decode( PageSchemas::getValueFromObject( $optsArray, 'VotingGroup' ) );
				$listTemplate = html_entity_decode( PageSchemas::getValueFromObject( $optsArray, 'ListTemplate' ) );
			}
		}

		$text = "\t<p>" . "The following fields are useful if you want custom wiki-text before and after field." . "</p>\n";
		$text .= "\t<p>" . 'Text before field:<br/>' . ' ' . Html::textarea( 'wikivoteps_field_before_num', $addTextBefore, array( 'rows' => 5, 'style' => 'width:100%' ) ) . "</p>\n";
		$text .= "\t<p>" . 'Text after field:<br/>' . ' ' . Html::textarea( 'wikivoteps_field_after_num', $addTextAfter, array( 'rows' => 5, 'style' => 'width:100%' ) ) . "</p>\n";

		if ( class_exists( 'WikivoteCustomVoting' ) ) {
			// CustomVoting
			$text .= "\t<p>" . Html::input( 'wikivoteps_field_wcv_on_num', 'on', 'checkbox', ($enableVoting == 'on') ? array( 'checked' => 'checked' ) : array() ) . 'Enable for custom voting';

			$wcvGroupsModels = WikivoteCustomVoting::getGroups();
			$wcvGroups = '';
			foreach ( $wcvGroupsModels as $group ) {

				$wcvGroups .= '<option value="' . $group->getId() . '" ' . (($votingGroup == $group->getId()) ? 'selected' : '') . '>';
				$wcvGroups .= $group->name . '(' . $group->code . ')';
				$wcvGroups .= '</option>';

			}

			$text .= "\t<p>" . 'Group: ' . Html::rawElement( 'select', array( 'name' => 'wikivoteps_field_wcv_group_num' ), $wcvGroups );
		}

		$sfTemplates = SFUtils::getAllPagesForNamespace('Template');
		$mwTemplates = '<option value="NONE">—</option>';

		if(count($sfTemplates)) {

			foreach($sfTemplates as  $key => $template) {
				$pagekey = str_replace( '_', ' ', $template );
				$mwTemplates .= '<option value="' . $pagekey . '" ' . (($listTemplate == $pagekey) ? 'selected' : '') . '>';
				$mwTemplates .= $pagekey;
				$mwTemplates .= '</option>';
			}

			$text .= "\t<p>Use specified template for list values: " . Html::rawElement('select', array('name'=>'wikivoteps_field_wcv_listtemplate_num'), $mwTemplates ) . "</p>";
		}

		return array( $text, $hasValues );

	}

	public static function createFieldXMLFromForm()
	{
		global $wgRequest;

		$xmlPerTemplate = array();
		$templateNum = -1;

		$tagOpened = false;

		foreach ( $wgRequest->getValues() as $var => $val ) {

			$val = PageSchemas::xmlEscape( $val );

			if ( !$tagOpened && (substr( $var, 0, 16 ) == 'wikivoteps_field') ) {
				$xml = '<wikivoteps_FieldOptions>';
				$templateNum = substr( $var, 23 );
				$tagOpened = true;
			}
			if ( $tagOpened && (substr( $var, 0, 16 ) != 'wikivoteps_field') ) {
				$xml .= '</wikivoteps_FieldOptions>';
				$xmlPerTemplate[$templateNum] = $xml;
				$tagOpened = false;
			}

			if ( substr( $var, 0, 24 ) == 'wikivoteps_field_before_' ) {
				$templateNum = substr( $var, 24 );
				if ( !empty($val) ) {
					$xml .= "<TextBefore>$val</TextBefore>";
				}
			} elseif ( substr( $var, 0, 23 ) == 'wikivoteps_field_after_' ) {
				if ( !empty($val) ) {
					$xml .= "<TextAfter>$val</TextAfter>";
				}
			} elseif ( substr( $var, 0, 24 ) == 'wikivoteps_field_wcv_on_' ) {
				if ( !empty($val) ) {
					$xml .= "<EnableVoting>$val</EnableVoting>";
				}
			} elseif ( substr( $var, 0, 27 ) == 'wikivoteps_field_wcv_group_' ) {
				if ( !empty($val) ) {
					$xml .= "<VotingGroup>$val</VotingGroup>";
				}
			} elseif ( substr( $var, 0, 34 ) == 'wikivoteps_field_wcv_listtemplate_' ) {
				if ( !empty($val) && ($val != 'NONE') ) {
					$xml .= "<ListTemplate>$val</ListTemplate>";
				}
			}
		}
		return $xmlPerTemplate;
	}

	/**
	* Displays data on a single form input in the Page Schemas XML.
	*/
	public static function getFieldDisplayValues( $fieldXML )
	{
		foreach ( $fieldXML->children() as $tag => $child ) {
			if ( $tag == "wikivoteps_FieldOptions" ) {
				$values = array();
				foreach ( $child->children() as $prop ) {
					$propName = (string)$prop->attributes()->name;
					$values[$propName] = (string)$prop;
				}
				return array( null, $values );
			}
		}
		return null;
	}

	/**
	* Return the list of pages that Semantic Forms could generate from
	* the current Page Schemas schema.
	*/
	public static function getPagesToGenerate( $pageSchemaObj )
	{
		return array();
	}

	/**
	* Generate pages (form and templates) specified in the list.
	*/
	public static function generatePages( $pageSchemaObj, $selectedPages )
	{

		$psTemplates = $pageSchemaObj->getTemplates();


	}

}