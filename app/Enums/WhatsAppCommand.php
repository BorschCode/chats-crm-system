<?php

namespace App\Enums;

enum WhatsAppCommand: string
{
    // Text commands
    case Catalog = 'catalog';
    case Groups = 'groups';
    case Items = 'items';
    case Item = 'item';

    // Menu actions (for interactive lists)
    case MenuCatalog = 'menu_catalog';
    case MenuGroups = 'menu_groups';
    case MenuItems = 'menu_items';

    // Navigation actions
    case BackToMenu = 'back_to_menu';

    /**
     * Get the menu action ID for interactive lists
     */
    public function getMenuId(): string
    {
        return match ($this) {
            self::Catalog => self::MenuCatalog->value,
            self::Groups => self::MenuGroups->value,
            self::Items => self::MenuItems->value,
            default => $this->value,
        };
    }

    /**
     * Try to create from menu action ID
     */
    public static function fromMenuId(string $menuId): ?self
    {
        return match ($menuId) {
            'menu_catalog' => self::Catalog,
            'menu_groups' => self::Groups,
            'menu_items' => self::Items,
            'back_to_menu' => self::BackToMenu,
            default => null,
        };
    }

    /**
     * Try to create from text command
     */
    public static function fromText(string $text): ?self
    {
        return self::tryFrom(strtolower(trim($text)));
    }
}
