<?php

namespace App\Services;

class KhqrService
{
    /**
     * Create KHQR String
     *
     * @param string $bankAccount Bakong Account ID
     * @param string $merchantName Merchant Name
     * @param string $merchantCity Merchant City
     * @param float $amount Amount
     * @param string $currency Currency (USD or KHR)
     * @param string $storeLabel Store Label
     * @param string $phoneNumber Phone Number
     * @param string $billNumber Bill Number
     * @param string $terminalLabel Terminal Label
     * @param bool $static Static or Dynamic QR
     * @return string
     */
    public function createQr($bankAccount, $merchantName, $merchantCity, $amount, $currency, $storeLabel, $phoneNumber, $billNumber, $terminalLabel, $static = false)
    {
        // 1. Payload Format Indicator
        $qr = $this->serialize('00', '01');

        // 2. Point of Initiation Method
        $qr .= $this->serialize('01', $static ? '11' : '12');

        // 3. Merchant Account Information (29 for Bakong)
        $merchantInfo = $this->serialize('00', 'kh.com.bakong');
        $merchantInfo .= $this->serialize('01', $bankAccount);
        $qr .= $this->serialize('29', $merchantInfo);

        // 4. Merchant Category Code
        $qr .= $this->serialize('52', '5999'); // General Merchant

        // 5. Transaction Currency
        $currencyCode = strtoupper($currency) === 'KHR' ? '116' : '840'; // 840 = USD, 116 = KHR
        $qr .= $this->serialize('53', $currencyCode);

        // 6. Transaction Amount (if not static)
        if (!$static && $amount > 0) {
            $qr .= $this->serialize('54', number_format($amount, 2, '.', ''));
        }

        // 7. Country Code
        $qr .= $this->serialize('58', 'KH');

        // 8. Merchant Name
        $qr .= $this->serialize('59', $merchantName);

        // 9. Merchant City
        $qr .= $this->serialize('60', $merchantCity);

        // 10. Additional Data Field Template (62)
        $additionalData = '';
        if ($billNumber) {
            $additionalData .= $this->serialize('01', $billNumber);
        }
        if ($phoneNumber) { // Mobile Number
            $additionalData .= $this->serialize('02', $phoneNumber);
        }
        if ($storeLabel) {
            $additionalData .= $this->serialize('03', $storeLabel);
        }
        if ($terminalLabel) {
            $additionalData .= $this->serialize('07', $terminalLabel);
        }
        
        if (!empty($additionalData)) {
            $qr .= $this->serialize('62', $additionalData);
        }

        // 11. CRC (63)
        $payload = $qr . '6304';
        $crc = $this->crc16($payload);
        $qr = $payload . strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));

        return $qr;
    }

    /**
     * Generate MD5 hash for transaction checking
     */
    public function generateMd5($qr)
    {
        return md5($qr);
    }

    /**
     * Helper to format TLV (Tag-Length-Value)
     */
    private function serialize($id, $value)
    {
        if ($value === null || $value === '') {
            return '';
        }
        $length = str_pad(strlen($value), 2, '0', STR_PAD_LEFT);
        return $id . $length . $value;
    }

    /**
     * Calculate CRC16 (CCITT-FALSE / XMODEM)
     */
    private function crc16($data)
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $x = ($crc >> 8) ^ ord($data[$i]);
            $x ^= $x >> 4;
            $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
        }
        return $crc;
    }
}
