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
    public function execute($argv, array $config, $type)
    {
        $maxSummaryLength = $config['commitMsg']['maxSummaryLength'];
        $maxContentLength = $config['commitMsg']['maxContentLength'];
        $firstWordImperative = $config['commitMsg']['firstWordImperative'];
        $lineAfterSummaryMustBeBlank = $config['commitMsg']['lineAfterSummaryMustBeBlank'];

        $commitMsg = file_get_contents($argv[3]);

        if (empty($commitMsg)) {
            return array('commit message cannot be empty', 1);
        }

        // always allow auto merge commits
        $isMerge = preg_match("/^Merge/", $commitMsg);
        if ($isMerge) {
            return array('', 0);
        }

        //------- CHECK IMPERATIVE WORD -------

        $isImperative = preg_match("/^([A-Z][\\S]+)(?<!ed|s)(\\b)/", $commitMsg);
        if (!$isImperative && $firstWordImperative) {
            return array(
                'first word of commit should be imperative' . PHP_EOL .
                'examples: Edit,Create,Update,Make,Change' . PHP_EOL .
                'counter examples: Edits,Created,Updated,Makes,Changes' . PHP_EOL .
                'reference: http://git-scm.com/book/en/Distributed-Git-Contributing-to-a-Project#Commit-Guidelines' .
                PHP_EOL,
                1
            );
        }

        //------- CHECK LINE LENGTHS -------

        $lines = file($argv[3]);

        if (count($lines) >= 2 &&
            strlen(trim(preg_replace('/\s+/', ' ', $lines[1]))) !== 0 &&
            $lineAfterSummaryMustBeBlank
        ) {
            return array(
                'Second line should always be empty' . PHP_EOL .
                'reference: http://git-scm.com/book/en/Distributed-Git-Contributing-to-a-Project#Commit-Guidelines' .
                PHP_EOL,
                1
            );
        }

        foreach ($lines as $index => $line) {
            if ($index === 0) {
                if (strlen($line) > $maxSummaryLength) {
                    return array(
                        'Summary line should be less than ' . $maxSummaryLength . PHP_EOL .
                        ' characters (found ' . strlen($line) . ')' . PHP_EOL .
                        'reference: http://git-scm.com/book/en/Distributed-Git-Contributing-to-a-Project#Commit-Guidelines' . PHP_EOL,
                        1
                    );
                }
            }

            // allow overly long urls in the main body
            $urlMatches = preg_match("/(?i)\\b((?:[a-z][\\w-]+:(?:\\/{1,3}|[a-z0-9%])|www\\d{0,3}[.]|[a-z0-9.\\-]+[.][a-z]{2,4}\\/)(?:[^\\s()<>]+|\\(([^\\s()<>]+|(\\([^\\s()<>]+\\)))*\\))+(?:\\(([^\\s()<>]+|(\\([^\\s()<>]+\\)))*\\)|[^\\s`!()\\[\\]{};:'\".,<>?«»“”‘’]))/u", $line);
            if ($urlMatches === 1) {
                continue;
            }

            if ($index > 1 && strlen($line) > $maxContentLength) {
                return array(
                    'No single line in commit message should be greater than ' . $maxContentLength . PHP_EOL .
                    ' characters (found line ' . ($index + 1) . ' to be ' . strlen($line) . ')' . PHP_EOL .
                    'reference: http://git-scm.com/book/en/Distributed-Git-Contributing-to-a-Project#Commit-Guidelines' . PHP_EOL,
                    1
                );
            }
        }

        return array('', 0);
    }
}
