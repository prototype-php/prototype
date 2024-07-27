<?php

declare(strict_types=1);

final class Request
{
    /**
     * @param array<string, int32> $id
     */
    public function __construct(
        public readonly array $id,
    ) {}

    /** @return array<string, int32> */
    public static function getId(): array
    {
        /** @var int32 $id */
        $id = -1;

        return ['id' => $id];
    }
}

$request = new Request(Request::getId());

print_r($request->id['id']);