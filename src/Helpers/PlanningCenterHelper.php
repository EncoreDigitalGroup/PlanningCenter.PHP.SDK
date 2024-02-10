<?php

namespace EncoreDigitalGroup\PlanningCenter\Helpers;

class PlanningCenterHelper
{
    public static function wasSuccessful($response): bool
    {
        if ($response->sdk->outcome->success) {
            return true;
        }

        return false;
    }

    public static function wasNotSuccessful($response): bool
    {
        if (!$response->sdk->outcome->success) {
            return true;
        }

        return false;
    }

    public static function wasUnsuccessful($res): bool
    {
        return self::wasNotSuccessful($res);
    }

    public static function wasRateLimited($response): bool
    {
        if ($response->sdk->outcome->rate_limited) {
            return true;
        }

        return false;
    }

    public static function wasNotRateLimited($response): bool
    {
        if (!$response->sdk->outcome->rate_limited) {
            return true;
        }

        return false;
    }
}