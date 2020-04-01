# Pronounceable Password

A no-frills pronounceable password generator in PHP. It creates a pronounceable password by alternating between consonants and vowels.

## Usage

require "PronounceablePassword.php";

$pp = new PronounceablePassword();
$pp->setLength(15)        // Set password length to 15 (default: 10)
   ->setMixedCase(true);  // Use random mix of upper and lower case letters (default: false)

echo $pp->generate();
