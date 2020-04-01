<?php
/**
 * This class generates pronounceable passwords by alternating between consonants and vowels.
 */
class PronounceablePassword
{
    // Possible single characters
    const CONSONANTS = [
        'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r',
        's', 't', 'v', 'w', 'x', 'y', 'z'
    ];
    const VOWELS = ['a', 'e', 'i', 'o', 'u'];

    // Possible 2 character combinations
    const MULTI_CONSONANTS = [
        'bl', 'br', 'ch', 'ck', 'cl', 'cr', 'dr', 'fl', 'fr', 'gh', 'gl', 'gr',
        'kr', 'ng', 'ph', 'pl', 'pr', 'sc', 'sh', 'sk', 'sl', 'sm', 'sn', 'sp',
        'st', 'sw', 'th', 'tr', 'tw', 'wh', 'wr'
    ];
    const MULTI_VOWELS = ['ai', 'au', 'ea', 'ee', 'oi', 'oo'];

    // These consonants do not work well at the end of a password so exclude them
    const EXCLUDE_FROM_END = [
        'bl', 'br', 'ch', 'cl', 'cr', 'dr', 'fl', 'fr', 'gh', 'gl', 'gr', 'kr',
        'ph', 'pl', 'pr', 'sc', 'sl', 'sm', 'sn', 'sw', 'tr', 'tw', 'wh', 'wr'
    ];

    private $length;
    private $chars;
    private $use_mixed_case;

    /**
     * Initialize class.
     */
    public function __construct()
    {
        $this->length = 10;
        $this->use_mixed_case = false;
        $ok_for_end = array_diff(self::MULTI_CONSONANTS, self::EXCLUDE_FROM_END);
        // Organize characters by sets (consonants/vowels)
        // with single character sets in the first index
        $this->chars = [
            [self::CONSONANTS, self::MULTI_CONSONANTS, $ok_for_end],
            [self::VOWELS, self::MULTI_VOWELS]
        ];
        foreach ($this->chars as &$sets) {
            foreach ($sets as &$type) {
                shuffle($type);
            }
        }
    }

    /**
     * Get password length.
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set password length.
     *
     * @param int $length
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * Get mixed case setting.
     *
     * @return bool
     */
    public function getMixedCase()
    {
        return $this->use_mixed_case;
    }

    /**
     * Set mixed case.
     *
     * @param bool
     * @return $this
     */
    public function setMixedCase($case)
    {
        $this->use_mixed_case = $case;
        return $this;
    }

    /**
     * Generate a password.
     *
     * @return string
     */
    public function generate()
    {
        $password = '';
        // Randomly start with either vowels or consonants
        $counter = mt_rand(0, 1);
        while (($current_length = strlen($password)) < $this->length) {
            // Alternate between vowels and consonants index
            $set_index = $counter % 2;
            if ($current_length == $this->length - 1) {
                // 1 character to fill so only use single char options
                $type_index = 0;
            } else {
                // Randomly choose either single or multi character array index
                $type_index = mt_rand(0, 1);
                if ($set_index == 0 && $type_index == 1 && $current_length == $this->length - 2) {
                    // Use consonants okay for ending
                    $type_index = 2;
                }
            }
            // Get random index from selected array
            $char_index = mt_rand(0, count($this->chars[$set_index][$type_index]) - 1);
            $password .= $this->chars[$set_index][$type_index][$char_index];
            $counter++;
        }
        if ($this->use_mixed_case) {
            // Randomly pick letters to uppercase
            for ($i = 0; $i < $this->length; $i++) {
                if (mt_rand(0, 1)) {
                    $password[$i] = strtoupper($password[$i]);
                }
            }
        }
        return $password;
    }
}
