<?php
declare(strict_types=1);

namespace Application\CodingStandard\Sniffs;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class StrictTypesDeclarationSniff implements Sniff
{
    public function register()
    {
        return array(T_OPEN_TAG);
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Only check for strict_types after <?php on first line
        if (0 < $stackPtr) {
            return;
        }

        $next = $stackPtr + 1;
        if (T_DECLARE === $tokens[$next]['code'] && 2 === $tokens[$next]['line']) {
            $statement = $phpcsFile->getTokensAsString($next, $next + 8);
            if (0 === strcmp($statement, "declare(strict_types=1);\n\n")) {
                // Valid declare statement
                return;
            }
        }

        $warning = 'Missing declare(strict_types=1);';
        $phpcsFile->addWarningOnLine($warning, 2, 'DeclareStrictTypes');
    }
}
