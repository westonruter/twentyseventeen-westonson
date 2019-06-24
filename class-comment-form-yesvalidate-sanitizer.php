<?php
/**
 * Class Comment_Form_YesValidate_Sanitizer.
 *
 * @package Twenty_Seventeen_Westonson
 */

namespace Twenty_Seventeen_Westonson;

/**
 * Class Comment_Form_YesValidate_Sanitizer
 *
 * Remove novalidate attribute from comment form to force client-side form validation to prevent relying on server-side
 * validation which will not be available during offline commenting. This ensures that background sync wont POST a
 * comment that we already know to be invalid.
 *
 * @todo Core should allow for this to be omitted removed.
 */
class Comment_Form_YesValidate_Sanitizer extends \AMP_Base_Sanitizer {

	/**
	 * Remove the novalidate attribute is removed from the comment form.
	 *
	 * This needs to be made part of the plugin.
	 */
	public function sanitize() {
		/**
		 * Comment form.
		 *
		 * @var \DOMElement $form
		 */
		$form = $this->dom->getElementById( 'commentform' );
		if ( $form ) {
			$form->removeAttribute( 'novalidate' );
		}
	}
}
