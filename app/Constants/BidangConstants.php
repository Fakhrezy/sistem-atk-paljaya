<?php

namespace App\Constants;

class BidangConstants
{
    /**
     * Daftar bidang dalam sistem
     *
     * @var array
     */
    public const BIDANG_LIST = [
        'keuangan' => 'Keuangan',
        'strategi_korporasi' => 'Strategi Korporasi',
        'pengelolaan_limbah_b3_transportasi' => 'Pengelolaan Limbah B3 & Transportasi',
        'sekretaris_perusahaan' => 'Sekretaris Perusahaan',
        'umum' => 'Umum',
        'tu_hukum' => 'TU & Hukum',
        'op' => 'OP',
        'teknik' => 'Teknik',
        'pemasaran' => 'Pemasaran',
        'mmk3l' => 'MMK3L',
        'spi' => 'SPI',
        'lainnya' => 'Lainnya'
    ];

    /**
     * Mendapatkan daftar bidang untuk dropdown
     *
     * @return array
     */
    public static function getBidangList(): array
    {
        return self::BIDANG_LIST;
    }

    /**
     * Mendapatkan nama bidang berdasarkan key
     *
     * @param string $key
     * @return string
     */
    public static function getBidangName(string $key): string
    {
        return self::BIDANG_LIST[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * Mendapatkan daftar key bidang saja
     *
     * @return array
     */
    public static function getBidangKeys(): array
    {
        return array_keys(self::BIDANG_LIST);
    }

    /**
     * Cek apakah bidang valid
     *
     * @param string $bidang
     * @return bool
     */
    public static function isValidBidang(string $bidang): bool
    {
        return array_key_exists($bidang, self::BIDANG_LIST);
    }
}
