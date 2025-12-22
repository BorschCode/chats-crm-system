<?php

use App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks\ButtonReplyPayload;
use App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks\InteractivePayload;
use App\Http\Integrations\WhatsApp\DataTransferObjects\Webhooks\ListReplyPayload;

test('can parse list_reply interactive payload', function () {
    $data = [
        'type' => 'list_reply',
        'list_reply' => [
            'id' => 'group_electronics',
            'title' => 'Electronics',
            'description' => 'View items in Electronics',
        ],
    ];

    $interactive = InteractivePayload::fromArray($data);

    expect($interactive->type)->toBe('list_reply')
        ->and($interactive->getSelectedId())->toBe('group_electronics')
        ->and($interactive->getSelectedTitle())->toBe('Electronics')
        ->and($interactive->listReply)->toBeInstanceOf(ListReplyPayload::class)
        ->and($interactive->listReply->id)->toBe('group_electronics')
        ->and($interactive->listReply->title)->toBe('Electronics')
        ->and($interactive->listReply->description)->toBe('View items in Electronics')
        ->and($interactive->buttonReply)->toBeNull();
});

test('can parse button_reply interactive payload', function () {
    $data = [
        'type' => 'button_reply',
        'button_reply' => [
            'id' => 'back_to_menu',
            'text' => 'Main Menu',
        ],
    ];

    $interactive = InteractivePayload::fromArray($data);

    expect($interactive->type)->toBe('button_reply')
        ->and($interactive->getSelectedId())->toBe('back_to_menu')
        ->and($interactive->getSelectedTitle())->toBe('Main Menu')
        ->and($interactive->buttonReply)->toBeInstanceOf(ButtonReplyPayload::class)
        ->and($interactive->buttonReply->id)->toBe('back_to_menu')
        ->and($interactive->buttonReply->text)->toBe('Main Menu')
        ->and($interactive->listReply)->toBeNull();
});

test('getSelectedId returns null when no reply data present', function () {
    $data = [
        'type' => 'unknown_type',
    ];

    $interactive = InteractivePayload::fromArray($data);

    expect($interactive->getSelectedId())->toBeNull()
        ->and($interactive->getSelectedTitle())->toBeNull();
});

test('handles list_reply without description', function () {
    $data = [
        'type' => 'list_reply',
        'list_reply' => [
            'id' => 'item_laptop',
            'title' => 'Laptop',
        ],
    ];

    $interactive = InteractivePayload::fromArray($data);

    expect($interactive->listReply->description)->toBeNull()
        ->and($interactive->getSelectedId())->toBe('item_laptop');
});
