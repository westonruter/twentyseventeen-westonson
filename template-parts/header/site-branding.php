<?php
/**
 * Displays header site branding with stream boundary.
 *
 * @package Twenty_Seventeen_Westonson
 */

require get_template_directory() . '/template-parts/header/site-branding.php';

if ( ! class_exists( 'WP_Service_Worker_Navigation_Routing_Component' ) ) {
	return;
}

/*
 * Add loading indicator when serving the streaming header.
 * The following code is copied from Material Components for the web (MIT license).
 * See https://material-components.github.io/material-components-web-catalog/#/component/linear-progress-indicator
 */

if ( WP_Service_Worker_Navigation_Routing_Component::is_streaming_header() ) {
	?>
	<style>
		#stream-loading-progressbar {
			position: absolute;
			bottom: 0;
		}

		@-webkit-keyframes primary-indeterminate-translate {
			0% {-webkit-transform: translateX(0);transform: translateX(0)}
			20% {-webkit-animation-timing-function: cubic-bezier(.5, 0, .70173, .49582);animation-timing-function: cubic-bezier(.5, 0, .70173, .49582);-webkit-transform: translateX(0);transform: translateX(0)}
			59.15% {-webkit-animation-timing-function: cubic-bezier(.30244, .38135, .55, .95635);animation-timing-function: cubic-bezier(.30244, .38135, .55, .95635);-webkit-transform: translateX(83.67142%);transform: translateX(83.67142%)}
			to {-webkit-transform: translateX(200.61106%);transform: translateX(200.61106%)}
		}

		@keyframes primary-indeterminate-translate {
			0% {-webkit-transform: translateX(0);transform: translateX(0)}
			20% {-webkit-animation-timing-function: cubic-bezier(.5, 0, .70173, .49582);animation-timing-function: cubic-bezier(.5, 0, .70173, .49582);-webkit-transform: translateX(0);transform: translateX(0)}
			59.15% {-webkit-animation-timing-function: cubic-bezier(.30244, .38135, .55, .95635);animation-timing-function: cubic-bezier(.30244, .38135, .55, .95635);-webkit-transform: translateX(83.67142%);transform: translateX(83.67142%)}
			to {-webkit-transform: translateX(200.61106%);transform: translateX(200.61106%)}
		}

		@-webkit-keyframes primary-indeterminate-scale {
			0% {-webkit-transform: scaleX(.08);transform: scaleX(.08)}
			36.65% {-webkit-animation-timing-function: cubic-bezier(.33473, .12482, .78584, 1);animation-timing-function: cubic-bezier(.33473, .12482, .78584, 1);-webkit-transform: scaleX(.08);transform: scaleX(.08)}
			69.15% {-webkit-animation-timing-function: cubic-bezier(.06, .11, .6, 1);animation-timing-function: cubic-bezier(.06, .11, .6, 1);-webkit-transform: scaleX(.66148);transform: scaleX(.66148)}
			to {-webkit-transform: scaleX(.08);transform: scaleX(.08)}
		}

		@keyframes primary-indeterminate-scale {
			0% {-webkit-transform: scaleX(.08);transform: scaleX(.08)}
			36.65% {-webkit-animation-timing-function: cubic-bezier(.33473, .12482, .78584, 1);animation-timing-function: cubic-bezier(.33473, .12482, .78584, 1);-webkit-transform: scaleX(.08);transform: scaleX(.08)}
			69.15% {-webkit-animation-timing-function: cubic-bezier(.06, .11, .6, 1);animation-timing-function: cubic-bezier(.06, .11, .6, 1);-webkit-transform: scaleX(.66148);transform: scaleX(.66148)}
			to {-webkit-transform: scaleX(.08);transform: scaleX(.08)}
		}

		@-webkit-keyframes secondary-indeterminate-translate {
			0% {-webkit-animation-timing-function: cubic-bezier(.15, 0, .51506, .40969);animation-timing-function: cubic-bezier(.15, 0, .51506, .40969);-webkit-transform: translateX(0);transform: translateX(0)}
			25% {-webkit-animation-timing-function: cubic-bezier(.31033, .28406, .8, .73371);animation-timing-function: cubic-bezier(.31033, .28406, .8, .73371);-webkit-transform: translateX(37.65191%);transform: translateX(37.65191%)}
			48.35% {-webkit-animation-timing-function: cubic-bezier(.4, .62704, .6, .90203);animation-timing-function: cubic-bezier(.4, .62704, .6, .90203);-webkit-transform: translateX(84.38617%);transform: translateX(84.38617%)}
			to {-webkit-transform: translateX(160.27778%);transform: translateX(160.27778%)}
		}

		@keyframes secondary-indeterminate-translate {
			0% {-webkit-animation-timing-function: cubic-bezier(.15, 0, .51506, .40969);animation-timing-function: cubic-bezier(.15, 0, .51506, .40969);-webkit-transform: translateX(0);transform: translateX(0)}
			25% {-webkit-animation-timing-function: cubic-bezier(.31033, .28406, .8, .73371);animation-timing-function: cubic-bezier(.31033, .28406, .8, .73371);-webkit-transform: translateX(37.65191%);transform: translateX(37.65191%)}
			48.35% {-webkit-animation-timing-function: cubic-bezier(.4, .62704, .6, .90203);animation-timing-function: cubic-bezier(.4, .62704, .6, .90203);-webkit-transform: translateX(84.38617%);transform: translateX(84.38617%)}
			to {-webkit-transform: translateX(160.27778%);transform: translateX(160.27778%)}
		}

		@-webkit-keyframes secondary-indeterminate-scale {
			0% {-webkit-animation-timing-function: cubic-bezier(.20503, .05705, .57661, .45397);animation-timing-function: cubic-bezier(.20503, .05705, .57661, .45397);-webkit-transform: scaleX(.08);transform: scaleX(.08)}
			19.15% {-webkit-animation-timing-function: cubic-bezier(.15231, .19643, .64837, 1.00432);animation-timing-function: cubic-bezier(.15231, .19643, .64837, 1.00432);-webkit-transform: scaleX(.4571);transform: scaleX(.4571)}
			44.15% {-webkit-animation-timing-function: cubic-bezier(.25776, -.00316, .21176, 1.38179);animation-timing-function: cubic-bezier(.25776, -.00316, .21176, 1.38179);-webkit-transform: scaleX(.72796);transform: scaleX(.72796)}
			to {-webkit-transform: scaleX(.08);transform: scaleX(.08)}
		}

		@keyframes secondary-indeterminate-scale {
			0% {-webkit-animation-timing-function: cubic-bezier(.20503, .05705, .57661, .45397);animation-timing-function: cubic-bezier(.20503, .05705, .57661, .45397);-webkit-transform: scaleX(.08);transform: scaleX(.08)}
			19.15% {-webkit-animation-timing-function: cubic-bezier(.15231, .19643, .64837, 1.00432);animation-timing-function: cubic-bezier(.15231, .19643, .64837, 1.00432);-webkit-transform: scaleX(.4571);transform: scaleX(.4571)}
			44.15% {-webkit-animation-timing-function: cubic-bezier(.25776, -.00316, .21176, 1.38179);animation-timing-function: cubic-bezier(.25776, -.00316, .21176, 1.38179);-webkit-transform: scaleX(.72796);transform: scaleX(.72796)}
			to {-webkit-transform: scaleX(.08);transform: scaleX(.08)}
		}
		.mdc-linear-progress {position: relative;width: 100%;height: 4px;-webkit-transform: translateZ(0);transform: translateZ(0);-webkit-transition: opacity .25s cubic-bezier(.4, 0, .6, 1) 0ms;-o-transition: opacity .25s 0ms cubic-bezier(.4, 0, .6, 1);transition: opacity .25s cubic-bezier(.4, 0, .6, 1) 0ms;overflow: hidden}

		.mdc-linear-progress__bar {-webkit-transform-origin: top left;-ms-transform-origin: top left;transform-origin: top left;-webkit-transition: -webkit-transform .25s cubic-bezier(.4, 0, .6, 1) 0ms;transition: -webkit-transform .25s cubic-bezier(.4, 0, .6, 1) 0ms;-o-transition: transform .25s 0ms cubic-bezier(.4, 0, .6, 1);transition: transform .25s cubic-bezier(.4, 0, .6, 1) 0ms;transition: transform .25s cubic-bezier(.4, 0, .6, 1) 0ms, -webkit-transform .25s cubic-bezier(.4, 0, .6, 1) 0ms}

		.mdc-linear-progress__bar, .mdc-linear-progress__bar-inner {position: absolute;width: 100%;height: 100%;-webkit-animation: none;animation: none}

		.mdc-linear-progress__bar-inner {display: inline-block}

		.mdc-linear-progress__buffering-dots {position: absolute;width: 100%;height: 100%;-webkit-animation: buffering .25s infinite linear;animation: buffering .25s infinite linear;background-repeat: repeat-x;background-size: 10px 4px}

		.mdc-linear-progress__buffer {position: absolute;width: 100%;height: 100%;-webkit-transform-origin: top left;-ms-transform-origin: top left;transform-origin: top left;-webkit-transition: -webkit-transform .25s cubic-bezier(.4, 0, .6, 1) 0ms;transition: -webkit-transform .25s cubic-bezier(.4, 0, .6, 1) 0ms;-o-transition: transform .25s 0ms cubic-bezier(.4, 0, .6, 1);transition: transform .25s cubic-bezier(.4, 0, .6, 1) 0ms;transition: transform .25s cubic-bezier(.4, 0, .6, 1) 0ms, -webkit-transform .25s cubic-bezier(.4, 0, .6, 1) 0ms}

		.mdc-linear-progress__primary-bar {-webkit-transform: scaleX(0);-ms-transform: scaleX(0);transform: scaleX(0)}

		.mdc-linear-progress__secondary-bar {visibility: hidden}

		.mdc-linear-progress--indeterminate .mdc-linear-progress__bar {-webkit-transition: none;-o-transition: none;transition: none}

		.mdc-linear-progress--indeterminate .mdc-linear-progress__primary-bar {left: -145.166611%;-webkit-animation: primary-indeterminate-translate 2s infinite linear;animation: primary-indeterminate-translate 2s infinite linear}

		.mdc-linear-progress--indeterminate .mdc-linear-progress__primary-bar > .mdc-linear-progress__bar-inner {-webkit-animation: primary-indeterminate-scale 2s infinite linear;animation: primary-indeterminate-scale 2s infinite linear}

		.mdc-linear-progress--indeterminate .mdc-linear-progress__secondary-bar {left: -54.888891%;-webkit-animation: secondary-indeterminate-translate 2s infinite linear;animation: secondary-indeterminate-translate 2s infinite linear;visibility: visible}

		.mdc-linear-progress--indeterminate .mdc-linear-progress__secondary-bar > .mdc-linear-progress__bar-inner {-webkit-animation: secondary-indeterminate-scale 2s infinite linear;animation: secondary-indeterminate-scale 2s infinite linear}

		.mdc-linear-progress__bar-inner {background-color: darkblue;}

		.mdc-linear-progress__buffering-dots {background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 5 2' preserveAspectRatio='none slice'%3E%3Ccircle cx='1' cy='1' r='1' fill='%23e6e6e6'/%3E%3C/svg%3E")}

		.mdc-linear-progress__buffer {background-color: #e6e6e6}

		.mdc-linear-progress--indeterminate.mdc-linear-progress--reversed .mdc-linear-progress__primary-bar {right: -145.166611%;left: auto}

		.mdc-linear-progress--indeterminate.mdc-linear-progress--reversed .mdc-linear-progress__secondary-bar {right: -54.888891%;left: auto}
	</style>
	<?php
}

// This function is called in requests both for the header stream fragment and the body stream fragment, thus no check for is_streaming_header.
WP_Service_Worker_Navigation_Routing_Component::print_stream_boundary(
	'<div id="stream-loading-progressbar" role="progressbar" class="mdc-linear-progress mdc-linear-progress--indeterminate"><div class="mdc-linear-progress__buffering-dots"></div><div class="mdc-linear-progress__buffer"></div><div class="mdc-linear-progress__bar mdc-linear-progress__primary-bar"><span class="mdc-linear-progress__bar-inner"></span></div><div class="mdc-linear-progress__bar mdc-linear-progress__secondary-bar"><span class="mdc-linear-progress__bar-inner"></span></div></div>'
);
