<?php
/*
 * Encore Digital Group - Planning Center PHP SDK
 * Copyright (c) 2023-2024. Encore Digital Group
 */

namespace EncoreDigitalGroup\PlanningCenter\Objects\People;

use EncoreDigitalGroup\PlanningCenter\Objects\People\Attributes\EmailAttributes;
use EncoreDigitalGroup\PlanningCenter\Objects\SdkObjects\ClientResponse;
use EncoreDigitalGroup\PlanningCenter\Traits\HasPlanningCenterClient;
use Illuminate\Support\Arr;
use stdClass;

/** @api */
class Email
{
    use HasPlanningCenterClient;

    public const EMAIL_ENDPOINT = '/people/v2/emails';

    public int|string|null $id;
    public EmailAttributes $attributes;

    public static function make(?string $clientId = null, ?string $clientSecret = null): Email
    {
        $email = new self($clientId, $clientSecret);
        $email->attributes = new EmailAttributes();

        return $email;
    }

    public function get(): ClientResponse
    {
        $http = $this->client()
            ->get($this->hostname() . self::EMAIL_ENDPOINT . '/' . $this->id);

        return $this->processResponse($http);

    }

    public function forPerson(): ClientResponse
    {
        $http = $this->client()
            ->get($this->hostname() . Person::PEOPLE_ENDPOINT . '/' . $this->attributes->personId . '/emails');

        $clientResponse = new ClientResponse($http);

        foreach ($http->json('data') as $email) {
            $pcoEmail = new Email($this->clientId, $this->clientSecret);
            $pcoEmail->mapFromPco($email);
            $clientResponse->data->push($pcoEmail);
        }

        return $clientResponse;
    }

    public function update(): ClientResponse
    {
        $http = $this->client()
            ->patch($this->hostname() . self::EMAIL_ENDPOINT . '/' . $this->id, $this->mapToPco());

        return $this->processResponse($http);
    }

    private function mapFromPco(stdClass $pco): void
    {
        $this->id = $pco->id;
        $this->attributes->emailAddressId = $pco->id;
        $this->attributes->address = $pco->attributes->address;
        $this->attributes->primary = $pco->attributes->primary;
    }

    private function mapToPco(): array
    {
        $email = [
            'data' => [
                'attributes' => [
                    'address' => $this->attributes->address,
                    'primary' => $this->attributes->primary,
                ],
            ],
        ];

        return Arr::whereNotNull($email);
    }
}
