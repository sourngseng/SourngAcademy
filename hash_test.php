<?php
$l = 'HAPPYCOFARM';
$p = 'HAPPYCOFARM';
$m = 'vwq1RI6aPMJ9P7uT7ZOaSjOSA5Y=';
$t = 'TXN15205999';
$s = '5a124b93c718';
$c = 'USD';
// Target from User Step 1
$target = strtolower('E2309C8D3A812C2FE01439079706544C22A6B01DEDDE965C6E8AFAC818C69917E0500B75C3F6C4B96AAC9319CD7FBFDCFC3E0B2A5D0F1B8CA38A34E56CF1F20F');

$amounts = ['1', '1.0', '1.00'];
$secrets = [$s, 'HAPPYCOFARM', '']; // Trying different secrets? Maybe explicit secret is wrong.

foreach ($secrets as $secret) {
    echo "Testing Secret: $secret\n";
    foreach ($amounts as $a) {
        $combos = [
            'Pattern1 (L+P+M+T+A+C+S)' => $l.$p.$m.$t.$a.$c.$secret,
            'Pattern2 (L+P+M+T+A+S)' => $l.$p.$m.$t.$a.$secret,
            'Pattern3 (L+M+T+A+C+S)' => $l.$m.$t.$a.$c.$secret,
            'Pattern4 (L+P+M+T+A+C)' => $l.$p.$m.$t.$a.$c,
            'Pattern5 (L+P+T+M+A+C+S)' => $l.$p.$t.$m.$a.$c.$secret,
        ];

        foreach ($combos as $key => $str) {
            $h = hash('sha512', $str);
            if ($h === $target) {
                echo "MATCH FOUND!\n";
                echo "Key: $key\n";
                echo "Amount Used: $a\n";
                echo "Secret Used: $secret\n";
                exit;
            }
        }
    }
}
echo "No match found.\n";
