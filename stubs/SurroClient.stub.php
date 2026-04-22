<?php

// Stubs for surro-php-bridge

namespace {
    class PhpIchnaeaLocationInformation {
        public float$lat;

        public float$lon;

        public int$range;

        public function __construct() {}
    }

    class PhpLocation {
        public int$altitude;

        public int$confidence;

        public int$horizontalAccuracy;

        public int$infoMask;

        public int$latitude;

        public int$locationType;

        public int$longitude;

        public int$reach;

        public int$score;

        public int$verticalAccuracy;

        public function __construct() {}
    }

    class PhpSurroundingCellsRequest {
        public int$cid;

        public int$lac;

        public int$limit = null;

        public int$mcc;

        public int$mnc;

        public string$rat;

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
        public int$accuracy;

        public int$cellId;

        public int$enb = null;

        public bool$isExactTowerLocation;

        public float$latitude;

        public float$longitude;

        public int$mcc;

        public int$mnc;

        public int$tacId;

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
         * @param float $latitude
         * @param float $longitude
         * @param string $rat
         * @param string $radius
         * @return array
         */
        public function getNearbyCells(float $latitude, float $longitude, string $rat, string $radius): array {}

        /**
         * @param int $mcc
         * @param int $mnc
         * @param int $cid
         * @param int $lac
         * @param string $rat
         * @param int|null $limit
         * @return array
         */
        public function getSurroundingCells(int $mcc, int $mnc, int $cid, int $lac, string $rat, ?int $limit = null): array {}

        /**
         * @param array $requests
         * @return array
         */
        public function getSurroundingCellsBatch(array $requests): array {}
    }
}
