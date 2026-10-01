<?php

namespace Stecman\Component\Symfony\Console\BashCompletion {
	class CompletionContext {
	}
}

namespace Stecman\Component\Symfony\Console\BashCompletion\Completion {
	interface CompletionAwareInterface {
		/**
		 * @param string $optionName
		 * @return array
		 */
		public function completeOptionValues($optionName, \Stecman\Component\Symfony\Console\BashCompletion\CompletionContext $context);

		/**
		 * @param string $argumentName
		 * @return array
		 */
		public function completeArgumentValues($argumentName, \Stecman\Component\Symfony\Console\BashCompletion\CompletionContext $context);
	}
}

namespace OC\Core\Command {
	use Stecman\Component\Symfony\Console\BashCompletion\Completion\CompletionAwareInterface;
	use Stecman\Component\Symfony\Console\BashCompletion\CompletionContext;

	class Base extends \Symfony\Component\Console\Command\Command implements CompletionAwareInterface {
		public const OUTPUT_FORMAT_PLAIN = 'plain';
		public const OUTPUT_FORMAT_JSON = 'json';
		public const OUTPUT_FORMAT_JSON_PRETTY = 'json_pretty';

		protected string $defaultOutputFormat = self::OUTPUT_FORMAT_PLAIN;

		/**
		 * @param string $optionName
		 * @return string[]
		 */
		public function completeOptionValues($optionName, CompletionContext $context) {
		}

		/**
		 * @param string $argumentName
		 * @return string[]
		 */
		public function completeArgumentValues($argumentName, CompletionContext $context) {
		}
	}
}
