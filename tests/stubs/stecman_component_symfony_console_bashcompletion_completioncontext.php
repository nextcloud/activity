<?php

namespace Stecman\Component\Symfony\Console\BashCompletion;

/**
 * Command line context for completion
 *
 * Represents the current state of the command line that is being completed
 */
class CompletionContext {
	/**
	 * The current contents of the command line as a single string
	 *
	 * Bash equivalent: COMP_LINE
	 *
	 * @var string
	 */
	protected $commandLine;

	/**
	 * The index of the user's cursor relative to the start of the command line.
	 *
	 * If the current cursor position is at the end of the current command,
	 * the value of this variable is equal to the length of $this->commandLine
	 *
	 * Bash equivalent: COMP_POINT
	 *
	 * @var int
	 */
	protected $charIndex = 0;

	/**
	 * An array of the individual words in the current command line.
	 *
	 * This is not set until $this->splitCommand() is called, when it is populated by
	 * $commandLine exploded by $wordBreaks
	 *
	 * Bash equivalent: COMP_WORDS
	 *
	 * @var string[]|null
	 */
	protected $words = null;

	/**
	 * Words from the currently command-line before quotes and escaping is processed
	 *
	 * This is indexed the same as $this->words, but in their raw input terms are in their input form, including
	 * quotes and escaping.
	 *
	 * @var string[]|null
	 */
	protected $rawWords = null;

	/**
	 * The index in $this->words containing the word at the current cursor position.
	 *
	 * This is not set until $this->splitCommand() is called.
	 *
	 * Bash equivalent: COMP_CWORD
	 *
	 * @var int|null
	 */
	protected $wordIndex = null;

	/**
	 * Characters that $this->commandLine should be split on to get a list of individual words
	 *
	 * Bash equivalent: COMP_WORDBREAKS
	 *
	 * @var string
	 */
	protected $wordBreaks = "= \t\n";

	/**
	 * Set the whole contents of the command line as a string
	 *
	 * @param string $commandLine
	 */
	public function setCommandLine($commandLine) {
	}

	/**
	 * Return the current command line verbatim as a string
	 *
	 * @return string
	 */
	public function getCommandLine() {
	}

	/**
	 * Return the word from the command line that the cursor is currently in
	 *
	 * Most of the time this will be a partial word. If the cursor has a space before it,
	 * this will return an empty string, indicating a new word.
	 *
	 * @return string
	 */
	public function getCurrentWord() {
	}

	/**
	 * Return the unprocessed string for the word under the cursor
	 *
	 * This preserves any quotes and escaping that are present in the input command line.
	 *
	 * @return string
	 */
	public function getRawCurrentWord() {
	}

	/**
	 * Return a word by index from the command line
	 *
	 * @see $words, $wordBreaks
	 * @param int $index
	 * @return string
	 */
	public function getWordAtIndex($index) {
	}

	/**
	 * Get the contents of the command line, exploded into words based on the configured word break characters
	 *
	 * @see $wordBreaks, setWordBreaks
	 * @return array
	 */
	public function getWords() {
	}

	/**
	 * Get the unprocessed/literal words from the command line
	 *
	 * This is indexed the same as getWords(), but preserves any quoting and escaping from the command line
	 *
	 * @return string[]
	 */
	public function getRawWords() {
	}

	/**
	 * Get the index of the word the cursor is currently in
	 *
	 * @see getWords, getCurrentWord
	 * @return int
	 */
	public function getWordIndex() {
	}

	/**
	 * Get the character index of the user's cursor on the command line
	 *
	 * This is in the context of the full command line string, so includes word break characters.
	 * Note that some shells can only provide an approximation for character index. Under ZSH for
	 * example, this will always be the character at the start of the current word.
	 *
	 * @return int
	 */
	public function getCharIndex() {
	}

	/**
	 * Set the cursor position as a character index relative to the start of the command line
	 *
	 * @param int $index
	 */
	public function setCharIndex($index) {
	}

	/**
	 * Set characters to use as split points when breaking the command line into words
	 *
	 * This defaults to a sane value based on BASH's word break characters and shouldn't
	 * need to be changed unless your completions contain the default word break characters.
	 *
	 * @deprecated This is becoming an internal setting that doesn't make sense to expose publicly.
	 *
	 * @see wordBreaks
	 * @param string $charList - a single string containing all of the characters to break words on
	 */
	public function setWordBreaks($charList) {
	}

	/**
	 * Split the command line into words using the configured word break characters
	 *
	 * @return string[]
	 */
	protected function splitCommand() {
	}

	/**
	 * Return a token's value with escaping and quotes removed
	 *
	 * @see self::tokenizeString()
	 * @param array $token
	 * @return string
	 */
	protected function getTokenValue($token) {
	}

	/**
	 * Break a string into words, quoted strings and non-words (breaks)
	 *
	 * Returns an array of unmodified segments of $string with offset and type information.
	 *
	 * @param string $string
	 * @return array as [ [type => string, value => string, offset => int], ... ]
	 */
	protected function tokenizeString($string) {
	}

	/**
	 * Reset the computed words so that $this->splitWords is forced to run again
	 */
	protected function reset() {
	}
}
