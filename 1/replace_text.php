<?php

function replaceText($input, $output)
{
    $sRegex = "/(\.\.\.|\.|\?|\!|\R)( |$)/u";
    $wRegex = "/(\"|\'|(\){1,}))?((,|:|;|\-)?( ))(\"|\'|(\({1,}))?/u";

    $replacements = [
        3 => [
            'text' => '--THREE--',
            'length' => mb_strlen('--THREE--'),
        ],
        5 => [
            'text' => '--FIVE--',
            'length' => mb_strlen('--FIVE--'),
        ],
        15 => [
            'text' => '--FIFTEEN--',
            'length' => mb_strlen('--FIFTEEN--'),
        ],
    ];

    $wordPosition = 1;
    while ($line = fgets($input)) {
        $lineBak = $line;
        $lineLength = mb_strlen($line);
        $lineDiff = 0;
        $sentences = preg_split($sRegex, $line, -1,PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE );
        foreach ($sentences as $sentence) {
            $outSentence = $sentence[0];
            $sentenceLength = mb_strlen($sentence[0]);
            $words = preg_split($wRegex, $sentence[0], -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE );
            $delta = 0;
            foreach($words as $word) {
                $wordLength = mb_strlen($word[0]);
                $replacementKey = null;

                if (multipleOf($wordPosition, 3, 5)) {
                    $replacementKey = 15;
                } elseif (multipleOf($wordPosition, 3)) {
                    $replacementKey = 3;
                } elseif (multipleOf($wordPosition, 5)) {
                    $replacementKey = 5;
                }

                if($replacementKey) {
                    $replacementLength = $replacements[$replacementKey]['length'];
                    $outSentence = sprintf("%s%s%s",
                        mb_substr($outSentence, 0, $word[1]+$delta),
                        $replacements[$replacementKey]['text'],
                        mb_substr($sentence[0], $word[1]+$wordLength, $sentenceLength)
                    );
                    $delta += $replacementLength-$wordLength;

                }
                $wordPosition++;
            }

            if ($outSentence != $sentence[0]) {
                $outSentenceLength = mb_strlen($outSentence);
                $line = sprintf("%s%s%s",
                    mb_substr($line, 0, $sentence[1]+$lineDiff),
                    $outSentence,
                    mb_substr($lineBak, $sentence[1]+$sentenceLength, $lineLength)
                );
                $lineDiff+= $outSentenceLength - $sentenceLength;
            }
        }
        fwrite($output, $line);
    }
}

function multipleOf($number, ...$denominators) {
    foreach ($denominators as $denominator) {
        if (!($number%$denominator == 0)) {
            return false;
        }
    }
    return true;
}

$input = fopen("input.txt", "r");
$output = fopen("output.txt", "w+");

replaceText($input, $output);

fclose($input);
fclose($output);

echo "Done. Saved to output.txt\r\n";

