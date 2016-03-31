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
	class Messenger
	{
		const DEFAULT_NAME   = 'notice';
		const DEFAULT_DOMAIN = 'default';
		const DEFAULT_FORMAT = '<div class="%n">%m</div>';
		const KEY_SEPARATOR  = '::';


		/**
		 * A callable formatter for formatting validation messages
		 *
		 * @access protected
		 * @var callable
		 */
		protected $formatter = NULL;


		/**
		 * Get a normalized key for the message
		 *
		 * @access protected
		 * @param string $domain The domain of the message, default NULL
		 * @param string $name The name of the message
		 * @return string The normalized key
		 */
		static protected function getKey($domain, $name)
		{
			if ($domain === NULL) {
				$domain = self::DEFAULT_DOMAIN;
			}

			if ($name === NULL) {
				$name = self::DEFAULT_NAME;
			}

			return implode(self::KEY_SEPARATOR, array(__CLASS__, $domain, $name));
		}


		/**
		 *
		 */
		public function __construct(callable $formatter = NULL)
		{
			$this->formatter = $formatter ?: function($domain, $name, $content) {
				echo sprintf('%s: %s', $name, (string) $content);
			};
		}


		/**
		 * Record a new message in the session and return it for immediate use
		 *
		 * @access public
		 * @param string $domain The domain of the message
		 * @param string $name The name of the message
		 * @param string $content The message content
		 * @return void
		 */
		public function record($domain, $name = NULL, $content = NULL)
		{
			if (func_num_args() == 1) {
				$content = func_get_arg(0);
				$name    = NULL;
				$domain  = NULL;

			} elseif (func_num_args() == 2) {
				$content = func_get_arg(1);
				$name    = func_get_arg(0);
				$domain  = NULL;

			}

			return $_SESSION[self::getKey($name, $domain)] = new Message($name, $content);;
		}


		/**
		 * Composes a message
		 *
		 * @access public
		 * @param string $domain The domain of the message
		 * @param string $name The name of the message
		 * @return string The formatted text of the message
		 */
		public function compose($domain, $name= NULL)
		{
			$formatter = $this->formatter;

			if (func_num_args() == 1) {
				$name   = func_get_arg(0);
				$domain = NULL;
			}

			if ($message = $this->retrieve($domain, $name)) {
				ob_start();
				$formatter($domain, $name, $message->content);

				return ob_get_clean();
			}

			return NULL;
		}


		/**
		 * Checks to see if a message exists in a given domain
		 *
		 * @access public
		 * @param string $domain The domain of the message
		 * @param string $name The name of the message
		 * @return boolean TRUE if a message of the name and domain exists, FALSE otherwise
		 */
		public function exists($domain, $name = NULL)
		{
			if (func_num_args() == 1) {
				$name   = func_get_arg(0);
				$domain = NULL;
			}

			return isset($_SESSION[self::getKey($name, $domain)])
				? TRUE
				: FALSE;
		}


		/**
		 * Redirects a message from the current one domain to another
		 *
		 * @access public
		 * @param string $source_domain The domain where the original message is
		 * @param string $name The name of the message
		 * @param string $target_domain The domain to redirect the message to
		 * @return Message The message object which has been redirected, NULL if it does not exist
		 */
		public function redirect($source_domain, $name, $target_domain)
		{
			if ($message = $this->retrieve($source_domain, $name)) {
				return $this->record($target_domain, $name, $message->content);
			}

			return NULL;
		}


		/**
		 * Retrieves a message from a particular domain
		 *
		 * @access public
		 * @param string $domain The domain to retrieve the message from
		 * @param string $name The name of the message
		 * @return Message The message object by name and/or domain, NULL if it does not exist
		 */
		public function retrieve($domain = NULL, $name = NULL)
		{
			$message = NULL;

			if (func_num_args() == 1) {
				$name   = func_get_arg(0);
				$domain = NULL;
			}

			if ($this->exists($domain, $name)) {
				$content_key = self::getKey($name, $domain);
				$message     = $_SESSION[$content_key];

				unset($_SESSION[$content_key]);
			}

			return $message;
		}
	}
}
