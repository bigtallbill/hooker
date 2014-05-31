<?php
/**
 * hooker
 * HookCommitMsg.php
 *
 * @author   Bill Nunney <bill@marketmesuite.com>
 * @date     31/05/2014 11:10
 * @license  http://marketmesuite.com/license.txt MMS License
 */
namespace Bigtallbill\Hooker;


class HookCommitMsg extends Hook
{
    public function execute($argv, array $config)
    {
        $maxSummaryLength = $config['commitMsg']['maxSummaryLength'];
        $maxContentLength = $config['commitMsg']['maxContentLength'];
        $firstWordImperative = $config['commitMsg']['firstWordImperative'];
        $lineAfterSummaryMustBeBlank = $config['commitMsg']['lineAfterSummaryMustBeBlank'];

        $commitMsg = file_get_contents($argv[3]);

        if (empty($commitMsg)) {
            return 'commit message cannot be empty';
        }

        //------- CHECK IMPERATIVE WORD -------

        $isImperative = preg_match("/^([A-Z][\\S]+)(?<!ed|s)(\\b)/", $commitMsg);
        if (!$isImperative && $firstWordImperative) {
            return 'first word of commit should be imperative' . PHP_EOL .
            'examples: Edit,Create,Update,Make,Change' . PHP_EOL .
            'counter examples: Edits,Created,Updated,Makes,Changes' . PHP_EOL .
            'reference: http://git-scm.com/book/en/Distributed-Git-Contributing-to-a-Project#Commit-Guidelines' . PHP_EOL;
        }

        //------- CHECK LINE LENGTHS -------

        $lines = file($argv[1]);

        if (count($lines) >= 2 &&
            strlen(trim(preg_replace('/\s+/', ' ', $lines[1]))) !== 0 &&
            $lineAfterSummaryMustBeBlank
        ) {
            return 'Second line should always be empty' . PHP_EOL .
            'reference: http://git-scm.com/book/en/Distributed-Git-Contributing-to-a-Project#Commit-Guidelines' .
            PHP_EOL;
        }

        foreach ($lines as $index => $line) {
            if ($index === 0) {
                if (strlen($line) > $maxSummaryLength) {
                    return 'Summary line should be less than ' . $maxSummaryLength . PHP_EOL .
                    ' characters (found ' . strlen($line) . ')' . PHP_EOL .
                    'reference: http://git-scm.com/book/en/Distributed-Git-Contributing-to-a-Project#Commit-Guidelines' . PHP_EOL;
                }
            }

            // allow overly long urls in the main body
            if (filter_var(trim(preg_replace('/\s+/', ' ', $line)), FILTER_VALIDATE_URL) !== false) {
                continue;
            }

            if ($index > 1 && strlen($line) > $maxContentLength) {
                return 'No single line in commit message should be greater than ' . $maxContentLength . PHP_EOL .
                ' characters (found line ' . ($index + 1) . ' to be ' . strlen($line) . ')' . PHP_EOL .
                'reference: http://git-scm.com/book/en/Distributed-Git-Contributing-to-a-Project#Commit-Guidelines' . PHP_EOL;
            }
        }

        return true;
    }
}
