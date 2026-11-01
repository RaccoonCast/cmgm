<?php

// Stubs for surro-php-bridge

namespace {
    class PhpCellLocationRequest {
        public int $cid;

        public int $lac;

        public int $mcc;

        public int $mnc;

        /**
         * @param int $mcc
         * @param int $mnc
         * @param int $cid
         * @param int $lac
         */
        public function __construct(int $mcc, int $mnc, int $cid, int $lac) {}
    }

    class PhpIchnaeaLocationInformation {
        public float $lat;

        public float $lon;

        public int $range;

        public function __construct() {}
    }

    class PhpLocation {
        public ?int $altitude = null;

        public ?int $confidence = null;

        public int $horizontalAccuracy;

        public ?int $infoMask = null;

        public int $latitude;

        public ?int $locationType = null;

        public int $longitude;

        public ?int $reach = null;

        public ?int $score = null;

        public ?int $verticalAccuracy = null;

        public function __construct() {}
    }

    class PhpMultiCellResponse {
        public function __construct() {}

        /**
         * @return array
         */
        public function getCoarse(): array {}

        /**
         * @return array
         */
        public function getFound(): array {}

        /**
         * @return array
         */
        public function getSurrounding(): array {}
    }

    class PhpSurroundingCellsRequest {
        public int $cid;

        public int $lac;

        public ?int $limit = null;

        public int $mcc;

        public int $mnc;

        public string $rat;

        /**
         * @param int $mcc
         * @param int $mnc
         * @param int $cid
         * @param int $lac
         * @param string $rat
         * @param int|null $limit
         */
        public function __construct(int $mcc, int $mnc, int $cid, int $lac, string $rat, ?int $limit = null) {}
    }

    class PhpSurroundingTower {
        public int $accuracy;

        public int $cellId;

        public ?int $enb = null;

        public bool $isCoarseLocation;

        public bool $isExactTowerLocation;

        public float $latitude;

        public float $longitude;

        public int $mcc;

        public int $mnc;

        public ?int $pci = null;

        public int $tacId;

        public ?int $uarfcn = null;

        public function __construct() {}

        /**
         * @return \PhpLocation
         */
        public function getLocation(): \PhpLocation {}
    }

    class SurroClient {
        public function __construct() {}

        /**
         * @param int $mcc
         * @param int $mnc
         * @param int $cid
         * @param int $lac
         * @param string $rat
         * @return \PhpIchnaeaLocationInformation
         */
        public function getCellLocation(int $mcc, int $mnc, int $cid, int $lac, string $rat): \PhpIchnaeaLocationInformation {}

        /**
         * @param array $requests
         * @param string $rat
         * @param int|null $surrounding_limit
         * @return \PhpMultiCellResponse
         */
        public function getMultipleCells(array $requests, string $rat, ?int $surrounding_limit = null): \PhpMultiCellResponse {}

        /**
         * @param float $latitude
         * @param float $longitude
         * @param string $rat
         * @param string $radius
         * @return \PhpMultiCellResponse
         */
        public function getNearbyCells(float $latitude, float $longitude, string $rat, string $radius): \PhpMultiCellResponse {}

        /**
         * @param int $mcc
         * @param int $mnc
         * @param int $cid
         * @param int $lac
         * @param string $rat
         * @param int|null $limit
         * @return \PhpMultiCellResponse
         */
        public function getSurroundingCells(int $mcc, int $mnc, int $cid, int $lac, string $rat, ?int $limit = null): \PhpMultiCellResponse {}

        /**
         * @param array $requests
         * @param int|null $chunk_size
         * @param bool|null $ignore_invalid_cid
         * @return array
         */
        public function getSurroundingCellsBatch(array $requests, ?int $chunk_size = null, ?bool $ignore_invalid_cid = null): array {}

        /**
         * @param int $mcc
         * @param int $mnc
         * @param int $lac
         * @param string $rat
         * @return \PhpMultiCellResponse
         */
        public function getSurroundingTacs(int $mcc, int $mnc, int $lac, string $rat): \PhpMultiCellResponse {}
    }
}
