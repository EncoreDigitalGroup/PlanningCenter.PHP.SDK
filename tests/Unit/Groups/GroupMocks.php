<?php
/*
 * Encore Digital Group - Planning Center PHP SDK
 * Copyright (c) 2024. Encore Digital Group
 */

namespace Tests\Unit\Groups;

use EncoreDigitalGroup\PlanningCenter\Objects\Calendar\Event;
use EncoreDigitalGroup\PlanningCenter\Objects\Calendar\EventInstance;
use EncoreDigitalGroup\PlanningCenter\Objects\Calendar\TagGroup;
use EncoreDigitalGroup\PlanningCenter\Objects\Groups\Group;
use EncoreDigitalGroup\PlanningCenter\Traits\HasPlanningCenterClient;
use PHPGenesis\Http\HttpClient;
use Tests\Helpers\BaseMock;
use Tests\Helpers\ObjectType;

class GroupMocks extends BaseMock
{
    use HasPlanningCenterClient;

    public const string GROUP_ID = "1";
    public const string GROUP_NAME = "Demo Group";

    public static function setup(): void
    {
        self::useGroupCollection();
        self::useSpecificGroup();
    }

    protected static function useGroupCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Group::GROUPS_ENDPOINT => function ($request) {
                return match ($request->method()) {
                    'GET' => HttpClient::response(self::useCollectionResponse(ObjectType::Group)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);

        HttpClient::fake([
            self::HOSTNAME . Group::GROUPS_ENDPOINT . "?filter=my_groups" => function ($request) {
                return match ($request->method()) {
                    'GET' => HttpClient::response(self::useCollectionResponse(ObjectType::Group)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useSpecificGroup(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Group::GROUPS_ENDPOINT . "/1" => function ($request) {
                return match ($request->method()) {
                    'GET' => HttpClient::response(self::useSingleResponse(ObjectType::Group)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function group(): array
    {
        return [
            "type" => "Group",
            "id" => self::GROUP_ID,
            "attributes" => [
                "archived_at" => "2000-01-01T12:00:00Z",
                "can_create_conversation" => true,
                "contact_email" => "string",
                "created_at" => "2000-01-01T12:00:00Z",
                "description" => "string",
                "events_visibility" => "value",
                "header_image" => [],
                "leaders_can_search_people_database" => true,
                "location_type_preference" => "value",
                "memberships_count" => 1,
                "name" => self::GROUP_NAME,
                "public_church_center_web_url" => "string",
                "schedule" => "string",
                "virtual_location_url" => "string",
                "widget_status" => [],
            ],
            "relationships" => [
                "group_type" => [
                    "data" => [
                        "type" => "GroupType",
                        "id" => "1",
                    ],
                ],
                "location" => [
                    "data" => [
                        "type" => "Location",
                        "id" => "1",
                    ],
                ],
            ],
        ];
    }
}