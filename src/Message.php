<?php namespace Dotink\Flourish
{
	/**
	 * Provides session-based messaging for page-to-page communication
	 *
	 * @copyright Copyright (c) 2007-2015 Matthew J. Sahagian, others
	 * @author Matthew J. Sahagian [mjs] <msahagian@dotink.org>
	 *
	 * @license Please reference the LICENSE.md file at the root of this distribution
	 *
	 * @package Flourish
	 */
	class Message
	{
		/**
		 * The content of the message
		 *
		 * @access public
		 * @var string
		 */
		public $content = NULL;


		/**
		 * The name of the message
		 *
		 * @access public
		 * @var string
		 */
		public $name = NULL;


		/**
		 * Instantiate a new message
		 *
		 * @access public
		 * @param string $name The name of the message
		 * @param string $content The meessage content
		 * @return void
		 */
		public function __construct($name, $content)
		{
			$this->name    = $name;
			$this->content = $content;
		}


		/**
		 * Represents the content of the message
		 *
		 * @access public
		 * @return string The message content
		 */
		public function __toString()
		{
			return (string) $this->content;
		}
	}
}
