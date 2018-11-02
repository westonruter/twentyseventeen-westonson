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
 * Ensures that the novalidate attribute is removed from the comment form because client-side validation needs to be
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
